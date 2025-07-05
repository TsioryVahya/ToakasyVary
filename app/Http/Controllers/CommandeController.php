<?php

namespace App\Http\Controllers;
use Carbon\Carbon; 
use App\Models\VueDetailsCommandes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Gamme;
use App\Models\LotProduction;
use App\Models\GammeMatiere;
use App\Models\TypeBouteille;
use App\Models\MouvementStockMatierePremiere;



class CommandeController extends Controller
{
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
//         DB::table('historique_commandes')->insert([
//             'id_commandes' => $idCommande,
//             'id_status_commandes' => 3
//         ]);
//    }

    public function previewForm()
    {
        $gammes = Gamme::all();
        $typesBouteilles = TypeBouteille::all();
        return view('commandes.preview', compact('gammes', 'typesBouteilles'));
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
            $gamme = Gamme::with('lots')->find($ligne['id_gamme']);
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
}