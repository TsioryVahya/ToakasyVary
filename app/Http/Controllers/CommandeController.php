<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\VueDetailsCommandes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
