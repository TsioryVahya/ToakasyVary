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
        CREATE VIEW vue_details_commandes AS
            SELECT
                c.id,
                c.date_commande,
                c.date_livraison,
                c.id_client,
                c.id_statut_commande,
                
                lc.id_lot,
                g.nom AS nom_gamme,
                tb.nom AS nom_bouteille,
                tb.capacite AS capacite_bouteille,
                
                lc.quantite_bouteilles,
                lc.id_prix AS prix_unitaire,
                (lc.quantite_bouteilles * lc.id_prix) AS montant_ligne,
                
                p.montant AS montant_paye,
                p.date_paiement
                
            FROM Commandes c
                
            JOIN Ligne_Commandes lc ON c.id = lc.id_commande
            JOIN Lot_Productions lp ON lc.id_lot = lp.id
            JOIN Gammes g ON lp.id_gamme = g.id
            JOIN Type_Bouteilles tb ON lp.id_bouteille = tb.id
            Join Client cl on c.id_client = cl.id
            JOIN paiement_commandes p ON c.id = p.id_commande;
  
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
