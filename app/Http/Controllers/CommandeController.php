<?php

namespace App\Http\Controllers;

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