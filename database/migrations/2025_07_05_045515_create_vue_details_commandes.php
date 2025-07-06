<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE OR REPLACE VIEW vue_details_commandes AS
            SELECT
                c.id,
                c.date_commande,
                c.date_livraison,
                c.id_client,
                hc.id_status_commande,
                
                lc.id_lot,
                g.nom AS nom_gamme,
                tb.nom AS nom_bouteille,
                tb.capacite AS capacite_bouteille,
                
                lc.quantite_bouteilles,
                pr.prix_unitaire,
                (lc.quantite_bouteilles * pr.prix_unitaire) AS montant_ligne,
                
                p.montant AS montant_paye,
                p.date_paiement
                
            FROM commandes c
                
            JOIN ligne_commandes lc ON c.id = lc.id_commande
            JOIN lot_productions lp ON lc.id_lot = lp.id
            JOIN gammes g ON lp.id_gamme = g.id
            JOIN type_bouteilles tb ON lp.id_bouteille = tb.id
            JOIN clients cl ON c.id_client = cl.id
            JOIN prix pr ON lc.id_prix = pr.id
            LEFT JOIN historique_commandes hc ON hc.id_commande = c.id 
                AND hc.date_hist = (
                    SELECT MAX(hc2.date_hist) 
                    FROM historique_commandes hc2 
                    WHERE hc2.id_commande = c.id
                )
            LEFT JOIN paiement_commandes p ON c.id = p.id_commande;
  
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_ventes');
    }
};
