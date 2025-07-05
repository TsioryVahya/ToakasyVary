<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       DB::statement("
            CREATE OR REPLACE VIEW vue_situation_matiere_premiere AS
            SELECT
                rep.id AS id,
                rep.nom AS nom,
                SUM(rep.qtte) AS qtte,
                MAX(rep.date_mouvement) AS date_mouvement

            FROM vue_historique_matiere_premiere AS rep
            GROUP BY rep.id, rep.nom
        "); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vue_situation_matiere_premiere');
    }
};