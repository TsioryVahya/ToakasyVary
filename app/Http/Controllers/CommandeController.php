<?php

namespace App\Http\Controllers;

use App\Models\Gamme;
use App\Models\LotProduction;
use App\Models\GammeMatiere;
use App\Models\TypeBouteille;
use App\Models\MouvementStockMatierePremiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Models\LigneCommande;
use App\Models\Commande;
use App\Models\HistoriqueCommande;
use Carbon\Carbon;

class CommandeController extends Controller
{
    public function previewForm()
    {
        $gammes = Gamme::all();
        $typesBouteilles = TypeBouteille::all();
        $clients = Client::all();
        return view('commandes.preview', compact('clients','gammes', 'typesBouteilles'));
    }


    public function preview(Request $request)
    {
        $request->validate([
            'date_livraison' => 'required|date|after:today',
            'lignes.*.id_gamme' => 'required|exists:gammes,id',
            'lignes.*.quantite_bouteilles' => 'required|integer|min:1',
            'lignes.*.id_bouteille' => 'required|integer|min:1',
            'id_client' => 'required|exists:clients,id',
        ]);
        $result = [
            'id_client' => $request->id_client,
            'stocks_disponibles' => [],
            'faisabilite' => [],
            'stocks_manquants' => [],
            'commande' => $request->all(),
            'lots_disponibles' => [], // Added to store available lots
        ];

        foreach ($request->lignes as $ligne) 
        {
            $gamme = Gamme::with('lotProductions')->find($ligne['id_gamme']);
            $quantiteDemandee = $ligne['quantite_bouteilles'];
            $typeBouteille = TypeBouteille::where('id', $ligne['id_bouteille'])->get()[0];

            // Stocks disponibles (somme des bouteilles dans les lots de la gamme)
            $stockDisponible = DB::table('vue_reste_bouteilles_par_lot')
                    ->where('id_gamme', $gamme->id)
                    ->where('id_bouteille', $ligne['id_bouteille'])
                    ->sum('reste_bouteilles');
            $result['stocks_disponibles'][$gamme->id . '-' . $ligne['id_bouteille']] = [
                'gamme' => $gamme->nom,
                'quantite' => $stockDisponible,
                'type_bouteille' => $typeBouteille->nom,
            ];
            $result['quantite_demandee'][$gamme->id . '-' . $ligne['id_bouteille']] = $quantiteDemandee;

            // Fetch available lots for this gamme-typeBouteille pair
            $lots = DB::table('vue_reste_bouteilles_par_lot')
                    ->where('id_gamme', $gamme->id)
                    ->where('id_bouteille', $ligne['id_bouteille'])
                    ->where('reste_bouteilles', '>', 0)
                    ->get(['id_lot', 'reste_bouteilles'])
                    ->toArray();
            $result['lots_disponibles'][$gamme->id . '-' . $ligne['id_bouteille']] = array_map(function ($lot) {
                return [
                    'id_lot' => $lot->id_lot,
                    'quantite_disponible' => $lot->reste_bouteilles,
                ];
            }, $lots);

            // Vérifier faisabilité temporelle
            $joursNecessaires = $gamme->fermentation_jours + $gamme->vieillissement_jours;
            $dateLivraison = Carbon::parse($request->date_livraison);
            $datePossible = Carbon::today()->addDays($joursNecessaires);
            $faisableDansDelais = $dateLivraison->greaterThanOrEqualTo($datePossible);

            if ($stockDisponible >= $quantiteDemandee) {
                $result['faisabilite'][$gamme->id . '-' . $ligne['id_bouteille']] = 'Réalisable dans le délai';
                $result['date_disponibilite'][$gamme->id . '-' . $ligne['id_bouteille']] = Carbon::today();
                $result['date_livraison'][$gamme->id . '-' . $ligne['id_bouteille']] = $dateLivraison;
            } else {
                if ($faisableDansDelais) $result['faisabilite'][$gamme->id . '-' . $ligne['id_bouteille']] = 'Réalisable dans le délai';
                else $result['faisabilite'][$gamme->id . '-' . $ligne['id_bouteille']] = 'Réalisable hors délai';

                $result['date_disponibilite'][$gamme->id . '-' . $ligne['id_bouteille']] = $datePossible;
                $result['date_livraison'][$gamme->id . '-' . $ligne['id_bouteille']] = $dateLivraison;
                $result['manquant'][$gamme->id . '-' . $ligne['id_bouteille']] = max(0, $quantiteDemandee - $stockDisponible);

                if ($result['manquant'][$gamme->id . '-' . $ligne['id_bouteille']] > 0) {
                    // Vérifier matières premières
                    $matieres = GammeMatiere::where('id_gamme', $gamme->id)->get();
                    foreach ($matieres as $matiere) {
                        $quantiteRequise = $matiere->quantite * $result['manquant'][$gamme->id . '-' . $ligne['id_bouteille']];
                        $stockMatiere = MouvementStockMatierePremiere::where('id_matiere', $matiere->id_matiere)
                            ->sum('quantite');

                        if ($stockMatiere < $quantiteRequise) {
                            $result['stocks_manquants'][] = [
                                'matiere' => $matiere->matiere->nom,
                                'quantite_manquante' => $quantiteRequise - $stockMatiere,
                                'gamme' => $gamme->nom,
                                'type_bouteille' => $typeBouteille->nom,
                            ];
                        }
                    }
                }
            }
        }
        return view('commandes.result', compact('result'));
    }

    public function store(Request $request)
    {
        // Log the incoming request data for debugging
        Log::debug('Store Request Data:', $request->all());

        $commandeData = json_decode($request->input('commande'), true);
        $lotsAllocation = $request->input('lots_allocation', []);

        // Validate that lots_allocation is not empty
        if (empty($lotsAllocation)) {
            Log::warning('No lots allocated in the request.');
            return redirect()->back()->with('error', 'Aucune allocation de lots fournie.');
        }

        // Validate total allocated quantities match requested quantities
        foreach ($commandeData['lignes'] as $index => $ligne) {
            $key = $ligne['id_gamme'] . '-' . $ligne['id_bouteille'];
            $totalAllocated = 0;
            if (isset($lotsAllocation[$key])) {
                foreach ($lotsAllocation[$key] as $lotId => $quantite) {
                    $totalAllocated += (int)$quantite;
                }
            }
            if ($totalAllocated != $ligne['quantite_bouteilles']) {
                Log::warning("Quantity mismatch for gamme {$ligne['id_gamme']}-bouteille {$ligne['id_bouteille']}: demanded {$ligne['quantite_bouteilles']}, allocated {$totalAllocated}");
                return redirect()->back()->with('error', "La quantité allouée pour la gamme {$ligne['id_gamme']} ne correspond pas à la quantité demandée.");
            }
        }

        // Start a transaction
        DB::beginTransaction();

        try {
            // Create the command
            $commande = Commande::create([
                'id_client' => 1, // À adapter
                'date_commande' => Carbon::today(),
                'date_livraison' => $commandeData['date_livraison'],
                'total' => 0,
            ]);

            Log::info('Commande created:', ['id' => $commande->id]);

            $total = 0;

            // Process each line and allocate lots
            foreach ($commandeData['lignes'] as $index => $ligne) {
                $key = $ligne['id_gamme'] . '-' . $ligne['id_bouteille'];
                if (isset($lotsAllocation[$key])) {
                    foreach ($lotsAllocation[$key] as $lotId => $quantite) {
                        if ((int)$quantite > 0) {
                            // Fetch the lot to get id_gamme
                            $lot = LotProduction::find($lotId);
                            if (!$lot) {
                                Log::error("Lot ID {$lotId} not found.");
                                throw new \Exception("Lot ID {$lotId} non trouvé.");
                            }

                            // Log all prices for the gamme to debug
                            $allPrices = DB::table('prix')
                                ->where('id_gamme', $lot->id_gamme)
                                ->get()
                                ->toArray();
                            Log::debug('All prices for gamme ID ' . $lot->id_gamme, $allPrices);

                            // Find the applicable price for the gamme
                            $today = Carbon::today();
                            Log::debug('Current date for price query:', ['today' => $today->toDateString()]);

                            $prix = DB::table('prix')
                                ->where('id_gamme', $lot->id_gamme)
                                ->where(function ($query) use ($today) {
                                    $query->whereNull('date_debut')
                                          ->whereNull('date_fin')
                                          ->orWhere(function ($query) use ($today) {
                                              $query->where('date_debut', '<=', $today)
                                                    ->where('date_fin', '>=', $today);
                                          });
                                })
                                ->orderByRaw('CASE WHEN date_debut IS NULL AND date_fin IS NULL THEN 1 ELSE 0 END, date_debut DESC')
                                ->first();

                            if (!$prix) {
                                Log::error("No valid price found for gamme ID {$lot->id_gamme}.");
                                throw new \Exception("Aucun prix valide trouvé pour la gamme ID {$lot->id_gamme}. Vérifiez les dates dans la table prix.");
                            }

                            Log::info('Price selected for gamme ID ' . $lot->id_gamme, (array)$prix);

                            // Create ligne_commande
                            $ligneCommande = LigneCommande::create([
                                'id_commande' => $commande->id,
                                'id_lot' => $lotId,
                                'quantite_bouteilles' => $quantite,
                                'id_prix' => $prix->id,
                            ]);

                            Log::info('LigneCommande created:', [
                                'id_commande' => $commande->id,
                                'id_lot' => $lotId,
                                'quantite' => $quantite,
                                'id_prix' => $prix->id
                            ]);

                            $total += $quantite * $prix->prix_unitaire;
                        }
                    }
                }
            }

            // Update the command total
            $commande->total = $total;
            $commande->save();

            Log::info('Commande updated with total:', ['id' => $commande->id, 'total' => $total]);

            // Insert into historique_commandes
            $historique = HistoriqueCommande::create([
                'id_commande' => $commande->id,
                'id_status_commande' => 1,
                'date_hist' => Carbon::today(),
            ]);

            Log::info('HistoriqueCommande created:', [
                'id_commande' => $commande->id,
                'id_status_commandes' => 1
            ]);

            DB::commit();

            return redirect()->route('commandes.preview')->with('success', 'Commande enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving commande:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement de la commande: ' . $e->getMessage());
        }
    }
    public function commandes()
    {
         $commandes = DB::select("
            SELECT v1.*
            FROM vue_details_commandes v1
            INNER JOIN (
                SELECT idCommande, MAX(idhistorique) as max_hist
                FROM vue_details_commandes
                GROUP BY idCommande
            ) v2 ON v1.idCommande = v2.idCommande AND v1.idhistorique = v2.max_hist
            WHERE v1.idstatus = 1
            GROUP BY v1.id_client, v1.nom, v1.date_commande, v1.date_livraison, v1.idstatus
        ");    
        return view('commandes', compact('commandes'));
    }
    public function valider(Request $request){
        $request->validate([
            'idCommande' => 'required|nullable|integer|in:1,2,3', // Rend le filtre optionnel 
        ]);
        $idCommande=$request->idCommande;
        $result = DB::select("
            SELECT
                CASE 
                    WHEN montant_paye < (prix_unitaire * quantite_bouteilles) THEN 0
                    WHEN montant_paye = (prix_unitaire * quantite_bouteilles) THEN 1 
                END AS validation
            FROM vue_details_commandes 
            WHERE id_commande = ?
        ", [$idCommande]);

        // Vérification si un résultat a été retourné
        if (empty($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Commande non trouvée'
            ], 404);
        }

        $validation = $result[0]->validation;

        // Traitement selon la valeur de validation
        if ($validation === 0) {
              echo "erreur";
        } elseif ($validation === 1) {
            
            DB::table('Mouvement_Produits')->insert([
                'id_commandes' => $idCommande,
                'id_status_commandes' => 2
            ]);

            DB::table('historique_commandes')->insert([
                'id_commandes' => $idCommande,
                'id_status_commandes' => 2
            ]);

            DB::table('historique_commandes')->insert([
                'id_commandes' => $idCommande,
                'id_status_commandes' => 2
            ]);

        }
   }
   public function annuler(Request $request){
        $request->validate([
            'idCommande' => 'required|nullable|integer|in:1,2,3', // Rend le filtre optionnel 
        ]);
        $idCommande=$request->idCommande;
        DB::table('historique_commandes')->insert([
            'id_commandes' => $idCommande,
            'id_status_commandes' => 3
        ]);
    }
}
