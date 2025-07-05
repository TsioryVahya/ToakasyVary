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
            CREATE VIEW vue_debut_production_lot AS
            SELECT
                lot_productions.id AS id_lot,
                lot_productions.id_gamme AS id_gamme,
                gammes.nom AS nom_gamme,
                lot_productions.date_debut AS date_debut,
                lot_productions.nombre_bouteilles AS nombre_bouteilles,
                matiere_premieres.id AS id_matiere,
                matiere_premieres.nom AS nom_matiere,
                gamme_matieres.quantite AS quantite_unitaire,
                (gamme_matieres.quantite * lot_productions.nombre_bouteilles) AS quantite_totale

            FROM lot_productions
            JOIN gammes ON gammes.id = lot_productions.id_gamme
            JOIN gamme_matieres ON gamme_matieres.id_gamme = lot_productions.id_gamme
            JOIN matiere_premieres ON matiere_premieres.id = gamme_matieres.id_matiere
        "); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vue_debut_production_lot");
    }
};