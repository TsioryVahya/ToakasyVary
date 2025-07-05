<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW vieillissement AS
            SELECT 
                lp.id AS lot_id,
                lp.id_gamme,
                lp.id_bouteille,
                lp.date_debut,
                lp.date_mise_en_bouteille,
                lp.date_commercialisation,
                lp.nombre_bouteilles,
                g.nom AS nom_gamme,
                g.description AS description_gamme,
                g.fermentation_jours,
                g.vieillissement_jours
            FROM lot_productions lp
            JOIN gammes g ON lp.id_gamme = g.id
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vieillissement");
    }
};