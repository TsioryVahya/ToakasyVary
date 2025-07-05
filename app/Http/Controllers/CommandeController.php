<?php

namespace App\Http\Controllers;

use App\Models\Gamme;
use App\Models\LotProduction;
use App\Models\GammeMatiere;
use App\Models\TypeBouteille;
use App\Models\MouvementStockMatierePremiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\LigneCommande;
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
        ]);

        $result = [
            'stocks_disponibles' => [],
            'faisabilite' => [],
            'stocks_manquants' => [],
            'commande' => $request->all(), // Stocker pour enregistrement ultérieur
        ];

        foreach ($request->lignes as $ligne) {
            $gamme = Gamme::with('lotProductions')->find($ligne['id_gamme']);
            $quantiteDemandee = $ligne['quantite_bouteilles'];
            $typeBouteille = TypeBouteille::where('id', $ligne['id_bouteille'])->get()[0];

            // Stocks disponibles (somme des bouteilles dans les lots de la gamme)
            $stockDisponible = DB::table('vue_reste_bouteilles_par_lot')
                    ->where('id_gamme', $gamme->id)
                    ->where('id_bouteille', $ligne['id_bouteille'])
                    ->sum('reste_bouteilles');
            $result['stocks_disponibles'][$gamme->id] = [
                'gamme' => $gamme->nom,
                'quantite' => $stockDisponible,
                'type_bouteille' => $typeBouteille->nom,
            ];
            $result['quantite_demandee'][$gamme->id] = $quantiteDemandee;

            // Vérifier faisabilité temporelle
            $joursNecessaires = $gamme->fermentation_jours + $gamme->vieillissement_jours;
            $dateLivraison = Carbon::parse($request->date_livraison);
            $datePossible = Carbon::today()->addDays($joursNecessaires);
            $faisableDansDelais = $dateLivraison->greaterThanOrEqualTo($datePossible);

            if ($stockDisponible >= $quantiteDemandee) {
                $result['faisabilite'][$gamme->id] = 'Réalisable dans le délai';
                $result['date_disponibilite'][$gamme->id] = Carbon::today();
                $result['date_livraison'][$gamme->id] = $dateLivraison;
            } else {
                if ($faisableDansDelais) $result['faisabilite'][$gamme->id] = 'Réalisable dans le délai';
                else $result['faisabilite'][$gamme->id] = 'Réalisable hors délai';

                $result['date_disponibilite'][$gamme->id] = $datePossible;
                $result['date_livraison'][$gamme->id] = $dateLivraison;
                $result['manquant'][$gamme->id] = max(0, $quantiteDemandee - $stockDisponible);
                

                if ($result['manquant'][$gamme->id] > 0) {
                    // Vérifier matières premières
                    $matieres = GammeMatiere::where('id_gamme', $gamme->id)->get();
                    foreach ($matieres as $matiere) {
                        $quantiteRequise = $matiere->quantite * $result['manquant'][$gamme->id];
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

    public function open_command(Request $request)
    {
        $commandeData = json_decode($request->input('commande'), true);
        // Créer la commande (simplifiée, sans client)
        $commande = \App\Models\Commande::create([
            'id_client' => 1, // À adapter (client par défaut ou autre logique)
            'date_commande' => now(),
            'date_livraison' => $commandeData['date_livraison'],
            'id_statut_commande' => 1,
            'total' => 0,
        ]);
        
        // Enregistrer les lignes (associer à un lot existant ou fictif)
        foreach ($commandeData['lignes'] as $ligne) {
            $lot = LotProduction::where('id_gamme', $ligne['id_gamme'])->first();
            if ($lot) {
                \App\Models\LigneCommande::create([
                    'id_commande' => $commande->id,
                    'id_lot' => $lot->id,
                    'quantite_bouteilles' => $ligne['quantite_bouteilles'],
                    'prix_unitaire' => 10.00, // À adapter
                ]);
            }
        }

        $commande->total = $commande->lignes->sum(function ($ligne) {
            return $ligne->quantite_bouteilles * $ligne->prix_unitaire;
        });
        $commande->save();

        return redirect()->route('commandes.preview')->with('success', 'Commande enregistrée avec succès.');
    }
    

    public function store(Request $request)
    {
        $commandeData = json_decode($request->input('commande'), true);
        // Créer la commande (simplifiée, sans client)
        $commande = \App\Models\Commande::create([
            'id_client' => 1, // À adapter (client par défaut ou autre logique)
            'date_commande' => now(),
            'date_livraison' => $commandeData['date_livraison'],
            'id_statut_commande' => 1,
            'total' => 0,
        ]);
        
        // Enregistrer les lignes (associer à un lot existant ou fictif)
        foreach ($commandeData['lignes'] as $ligne) {
            $lot = LotProduction::where('id_gamme', $ligne['id_gamme'])->first();
            if ($lot) {
                \App\Models\LigneCommande::create([
                    'id_commande' => $commande->id,
                    'id_lot' => $lot->id,
                    'quantite_bouteilles' => $ligne['quantite_bouteilles'],
                    'prix_unitaire' => 10.00, // À adapter
                ]);
            }
        }

        $commande->total = $commande->lignes->sum(function ($ligne) {
            return $ligne->quantite_bouteilles * $ligne->prix_unitaire;
        });
        $commande->save();

        return redirect()->route('commandes.preview')->with('success', 'Commande enregistrée avec succès.');
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
