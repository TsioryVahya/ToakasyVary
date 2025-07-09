<?php

namespace App\Http\Controllers;
use App\Models\Gamme;
use App\Models\LotProduction;
use App\Models\GammeMatiere;
use App\Models\TypeBouteille;
use App\Models\MouvementStockMatierePremiere;
use App\Models\VueDetailsCommandes;
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
                'id_statut_commande' => 1,
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
        return view('production.commandes', compact('commandes'));
    }
    public function valider(Request $request){
        $request->validate([
            'idCommande' => 'required|nullable|integer|in:1,2,3', // Rend le filtre optionnel
        ]);
        $idCommande=$request->idCommande;
        $result = DB::select("
            SELECT
                CASE
                    WHEN montant_paye < (montant_ligne) THEN 0
                    WHEN montant_paye >= (montant_ligne) THEN 1
                END AS validation
            FROM vue_details_commandes
            WHERE idCommande = ?
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
           // Récupération de toutes les données en UNE SEULE requête
           $commandeData = DB::table('vue_details_commandes')
           ->select(
            'idCommande',
            'id_lot',
            'quantite_bouteilles',
            'date_livraison',
            DB::raw('SUM(montant_paye) as montant_total') // Ajout du SUM ici
        )
        ->where('idCommande', $idCommande)
        ->groupBy('idCommande', 'id_lot', 'quantite_bouteilles', 'date_livraison') // Groupement nécessaire avec SUM
        ->first();

    if (!$commandeData) {
    throw new \Exception("Commande introuvable");
    }

    // Assignation des valeurs
    $valid = $commandeData->id_lot;
    $quantite = $commandeData->quantite_bouteilles;
    $date = Carbon::parse($commandeData->date_livraison);
    $montant = $commandeData->montant_total; // Correction de la variable ici
                DB::table('Mouvement_Produits')->insert([
                    'id_lot' => $valid,
                    'id_detail_mouvement'=>2,
                    'quantite_bouteilles' => -$quantite,
                    'date_mouvement'=> $date
                ]);

                DB::table('historique_commandes')->insert([
                    'id_commande' => $idCommande,
                    'id_statut_commande' => 2,
                    'date_hist'=>$date
                ]);

                DB::table('ventes')->insert([
                    'id_commande' => $idCommande,
                    'date_vente' => $date,
                    'montant' =>$montant
                ]);

            }
   }

//    public function annuler(Request $request){
//         $request->validate([
//             'idCommande' => 'required|nullable|integer|in:1,2,3', // Rend le filtre optionnel
//         ]);
//         $idCommande=$request->idCommande;
//         $resultss = DB::select("
//             Select date_livraison from vue_details_commandes where idCommande=?
//         ", [$idCommande]);
//         $date= $resultss[0]->date_livraison;
//         $resultss = DB::select("
//             Select id_gamme from vue_details_commandes where idCommande=?
//         ", [$idCommande]);
//         $date= $resultss[0]->date_livraison;
//         if(){}
//         DB::table('historique_commandes')->insert([
//             'id_commandes' => $idCommande,
//             'id_status_commandes' => 3
//         ]);
//     }
public function annuler(Request $request)
    {
        $request->validate([
            'idCommande' => 'required|integer|exists:commandes,id',
        ]);

        $idCommande = $request->idCommande;

        // Récupération des données nécessaires
        $details = DB::table('vue_details_commandes')
                    ->where('idCommande', $idCommande)
                    ->first();

        if (!$details) {
            return back()->with('error', 'Commande non trouvée');
        }

        // Vérification des conditions d'annulation
        $dateLivraison = Carbon::parse($details->date_livraison);
        $now = Carbon::now();
        $peutAnnuler = false;

        switch ($details->idGamme) {
            case 1: // Gamme 1 - 1 semaine avant livraison
                $peutAnnuler = $now->diffInDays($dateLivraison, false) >= 7;
                $messageErreur = "Annulation impossible : doit être faite au moins 1 semaine avant la livraison";
                break;

            case 2: // Gamme 2 - 1 mois avant livraison
                $peutAnnuler = $now->diffInMonths($dateLivraison, false) >= 1;
                $messageErreur = "Annulation impossible : doit être faite au moins 1 mois avant la livraison";
                break;

            case 3: // Gamme 3 - 3 mois avant livraison
                $peutAnnuler = $now->diffInMonths($dateLivraison, false) >= 3;
                $messageErreur = "Annulation impossible : doit être faite au moins 3 mois avant la livraison";
                break;

            default:
                return back()->with('error', 'Gamme non reconnue');
        }

        if (!$peutAnnuler) {
            return back()->with('error', $messageErreur);
        }

        // Insertion dans l'historique si les conditions sont remplies
        DB::table('historique_commandes')->insert([
            'id_commande' => $idCommande,
            'id_statut_commande' => 3, // Statut "Annulée"
            'date_hist' => $now,
        ]);

        return back()->with('success', 'Commande annulée avec succès');
    }


    public function sortirStockSelonCommande(int $idCommande)
    {
        // 1. Récupérer la commande
        $commande = DB::table('commandes')->where('id', $idCommande)->first();
        if (!$commande) {
            return "Commande non trouvée";
        }

        $dateCommande = Carbon::parse($commande->date_commande);
        $quantiteCommande = $commande->total; // OU récupérer la quantité totale de bouteilles à sortir (adapter selon ta structure)

        // 2. Récupérer les lots disponibles avant la date de commande, triés par date_mise_en_bouteille asc
        $lotsDisponibles = DB::table('vue_reste_bouteilles_par_lot')
            ->where('date_mise_en_bouteille', '<=', $dateCommande)
            ->where('reste_bouteilles', '>', 0)
            ->orderBy('date_mise_en_bouteille', 'asc')
            ->get();

        $quantiteRestante = $quantiteCommande;

        foreach ($lotsDisponibles as $lot) {
            if ($quantiteRestante <= 0) {
                break; // plus rien à sortir
            }

            $stockRestant = $lot->reste_bouteilles;

            if ($stockRestant >= $quantiteRestante) {
                // On sort la totalité de la quantité restante de ce lot
                DB::table('mouvement_produits')->insert([
                    'id_lot' => $lot->id,
                    'id_detail_mouvement' => 2, // A adapter selon ta logique (ex: sortie)
                    'quantite_bouteilles' => -$quantiteRestante,
                    'date_mouvement' => $dateCommande,
                ]);
                $quantiteRestante = 0;
                break;
            } else {
                // On vide ce lot, on soustrait tout son stock restant
                DB::table('mouvement_produits')->insert([
                    'id_lot' => $lot->id,
                    'id_detail_mouvement' => 2,
                    'quantite_bouteilles' => -$stockRestant,
                    'date_mouvement' => $dateCommande,
                ]);
                $quantiteRestante -= $stockRestant;
            }
        }

        if ($quantiteRestante > 0) {
            return "Stock insuffisant : il manque $quantiteRestante bouteilles pour satisfaire la commande.";
        }

        // Optionnel : changer le statut de la commande (exemple)
        DB::table('historique_commandes')->insert([
            'id_commande' => $idCommande,
            'id_statut_commande' => 2, // statut "validée" ou "en cours"
            'date_hist' => Carbon::now(),
        ]);

        return "Sortie de stock réalisée avec succès pour la commande $idCommande.";
    }
}
