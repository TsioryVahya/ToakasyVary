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
        DB::statement("CREATE OR REPLACE VIEW stats_completes AS
            SELECT
                gammes.nom AS nom,
                commandes.total AS montant,
                ligne_commandes.quantite_bouteilles AS bouteille_vendue,
                commandes.date_commande AS date_vente,
                commandes.date_livraison AS date_livraison,
                SUM(gamme_matieres.quantite * COALESCE(prix_materiels.prix, 0)) AS cout_matiere_unitaire,
                SUM(gamme_matieres.quantite * COALESCE(prix_materiels.prix, 0)) * ligne_commandes.quantite_bouteilles AS depense_total,
                (ligne_commandes.quantite_bouteilles * ligne_commandes.prix_unitaire) -
                (SUM(gamme_matieres.quantite * COALESCE(prix_materiels.prix, 0)) * ligne_commandes.quantite_bouteilles) AS marge
            FROM
                commandes
            JOIN
                ligne_commandes ON commandes.id = ligne_commandes.id_commande
            JOIN
                lot_productions ON ligne_commandes.id_lot = lot_productions.id
            JOIN
                gammes ON lot_productions.id_gamme = gammes.id
            JOIN
                gamme_matieres ON gammes.id = gamme_matieres.id_gamme
            LEFT JOIN
                prix_materiels ON gamme_matieres.id_matiere = prix_materiels.id_matiere_premiere
            GROUP BY
                commandes.id,
                gammes.nom,
                commandes.total,
                ligne_commandes.quantite_bouteilles,
                ligne_commandes.prix_unitaire,
                commandes.date_commande,
                commandes.date_livraison;
            ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS stats_completes");
    }
};
