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
        CREATE OR REPLACE VIEW vue_reste_bouteilles_par_lot_avec_date AS
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
                AND mp.date_mouvement <= CURRENT_DATE
            LEFT JOIN
                mouvement_produits_commandes mpc ON mpc.id_mouvement_produit = mp.id
            LEFT JOIN
                commandes c ON c.id = mpc.id_commande
                AND c.date_commande <= CURRENT_DATE
            LEFT JOIN
                historique_commandes hc ON hc.id_commande = c.id 
                AND hc.date_hist = (
                    SELECT MAX(hc2.date_hist) 
                    FROM historique_commandes hc2 
                    WHERE hc2.id_commande = c.id
                    -- Filtre l'historique jusqu'à la date spécifiée
                    AND hc2.date_hist <= CURRENT_DATE
                )
            LEFT JOIN
                type_bouteilles tb ON tb.id = lp.id_bouteille
            GROUP BY
                lp.id, lp.id_gamme, lp.id_bouteille, tb.nom, tb.capacite,
                lp.date_debut, lp.date_mise_en_bouteille, lp.date_commercialisation, lp.nombre_bouteilles;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vue_reste_bouteilles_par_lot_avec_date");
    }
};
