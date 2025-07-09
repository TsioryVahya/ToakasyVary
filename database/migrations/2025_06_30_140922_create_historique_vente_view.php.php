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
        CREATE OR REPLACE VIEW historique_ventes AS
        SELECT
            clients.nom as nom_clients,
            clients.email as email_clients,
            gammes.nom AS nom_gamme,
            commandes.total AS montant,
            ligne_commandes.quantite_bouteilles AS quantite,
            commandes.date_commande AS vente,
            commandes.date_livraison AS livraison
        FROM clients
        JOIN commandes ON commandes.id_client = clients.id
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
        Schema::dropIfExists('historique_ventes');
    }
};
