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
                c.id as idCommande,
                c.date_commande,
                c.date_livraison,
                c.id_client,
                
                cl.nom,

                lc.id_lot,
                g.nom AS nom_gamme,
                tb.nom AS nom_bouteille,
                tb.capacite AS capacite_bouteille,
                g.id as idGamme,
                lc.quantite_bouteilles,

                p.montant AS montant_paye,
                p.date_paiement,
                stc.id as idhistorique,
                stc.id_status_commande as idstatus,

                pr.prix_unitaire as prixUnitaire, 
                (lc.quantite_bouteilles * pr.prix_unitaire) AS montant_ligne
            FROM Commandes c

            JOIN Ligne_Commandes lc ON c.id = lc.id_commande
            JOIN Lot_Productions lp ON lc.id_lot = lp.id
            JOIN Gammes g ON lp.id_gamme = g.id
            JOIN Type_Bouteilles tb ON lp.id_bouteille = tb.id
            Join Clients cl on c.id_client = cl.id
            Join historique_commandes stc on c.id =stc.id_commande
            Join prix pr on pr.id = lc.id_prix
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
