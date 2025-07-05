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
            CREATE VIEW vue_historique_matiere_premiere AS
            SELECT
                matiere_premieres.id AS id,
                matiere_premieres.nom AS nom,
                CASE
                    WHEN id_type_mouvement = 1 THEN -Mouvement_Stock_matiere_premieres.quantite
                    ELSE Mouvement_Stock_matiere_premieres.quantite
                END AS qtte,
                Mouvement_Stock_matiere_premieres.date_mouvement AS date_mouvement

            FROM Mouvement_Stock_matiere_premieres
            JOIN matiere_premieres ON Mouvement_Stock_matiere_premieres.id_matiere = matiere_premieres.id
        "); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vue_historique_matiere_premiere');
    }
};

