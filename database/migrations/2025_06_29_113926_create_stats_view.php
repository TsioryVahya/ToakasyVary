<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE VIEW stats AS
        SELECT
            gammes.nom AS nom,
            commandes.total AS montant,
            ligne_commandes.quantite_bouteilles AS quantite,
            commandes.date_commande AS vente,
            commandes.date_livraison AS livraison
        FROM ventes
        JOIN commandes ON ventes.id_commande = commandes.id
        JOIN ligne_commandes ON ligne_commandes.id_commande = commandes.id
        JOIN lot_productions ON ligne_commandes.id_lot = lot_productions.id
        JOIN gammes ON lot_productions.id_gamme = gammes.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats_view');
    }
};
