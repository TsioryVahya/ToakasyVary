<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
        CREATE OR REPLACE VIEW vue_reste_bouteilles_par_lot AS
        SELECT
            lp.id AS id_lot,
            lp.id_gamme,
            lp.id_bouteille,
            tb.nom AS nom_bouteille,
            tb.capacite AS capacite_bouteille,
            lp.date_debut,
            lp.date_mise_en_bouteille,
            lp.date_commercialisation,
            lp.nombre_bouteilles,
            COALESCE(lp.nombre_bouteilles, 0) + COALESCE(SUM(
                CASE
                    WHEN mpc.id_commande IS NULL THEN mp.quantite_bouteilles
                    WHEN hc.id_status_commande > 1 THEN mp.quantite_bouteilles
                    ELSE 0
                END
            ), 0) AS reste_bouteilles
        FROM
            lot_productions lp
        LEFT JOIN
            mouvement_produits mp ON mp.id_lot = lp.id
        LEFT JOIN
            mouvement_produits_commandes mpc ON mpc.id_mouvement_produit = mp.id
        LEFT JOIN
            commandes c ON c.id = mpc.id_commande
        LEFT JOIN
            historique_commandes hc ON hc.id_commande = c.id
        LEFT JOIN
            type_bouteilles tb ON tb.id = lp.id_bouteille
        GROUP BY
            lp.id, lp.id_gamme, lp.id_bouteille, tb.nom, tb.capacite,
            lp.date_debut, lp.date_mise_en_bouteille, lp.date_commercialisation, lp.nombre_bouteilles
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vue_reste_bouteilles_par_lot");
    }
};
