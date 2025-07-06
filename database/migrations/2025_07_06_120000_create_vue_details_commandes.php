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
        FROM commandes c
        JOIN ligne_commandes lc ON c.id = lc.id_commande
        JOIN lot_productions lp ON lc.id_lot = lp.id
        JOIN gammes g ON lp.id_gamme = g.id
        JOIN type_bouteilles tb ON lp.id_bouteille = tb.id
        JOIN clients cl ON c.id_client = cl.id
        JOIN historique_commandes stc ON c.id = stc.id_commande
        JOIN prix pr ON pr.id = lc.id_prix
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
