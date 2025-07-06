<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateAllTables extends Migration
{
    public function up()
    {
        // Table Fournisseur
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->string('email', 100)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->text('adresse')->nullable();
            $table->timestamps();
        });

        // Table Type_Client
        Schema::create('type_clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();
            $table->timestamps();
        });

        // Table Client
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->foreignId('id_type_client')->nullable()->constrained('type_clients')->onDelete('set null');
            $table->string('email', 100)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->text('adresse')->nullable(); // Correction ici
            $table->timestamps();
        });

        // Table Type_Mouvement
        Schema::create('type_mouvements', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();
            $table->timestamps();
        });

        // Table Matiere_Premiere
        Schema::create('matiere_premieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Table Departement
        Schema::create('departements', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Table Statut_Employe
        Schema::create('statut_employes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();
            $table->timestamps();
        });

        // Table Employe
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->string('poste', 100);
            $table->string('email', 100)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->foreignId('id_statut_employe')->nullable()->default(1)->constrained('statut_employes')->onDelete('set null');
            $table->foreignId('id_departement')->constrained('departements')->onDelete('cascade');
            $table->timestamps();
        });

        

        // Table Type_Materiel
        Schema::create('type_materiels', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();
            $table->timestamps();
        });

        // Table Materiel
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('id_type_materiel')->constrained('type_materiels')->onDelete('cascade');
            $table->decimal('capacite', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Table Type_Bouteille
        Schema::create('type_bouteilles', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();
            $table->decimal('capacite', 10, 2);
            $table->timestamps();
        });

        // Table Statut_Lot
        Schema::create('statut_lots', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();
            $table->timestamps();
        });

        // Table Gamme
        Schema::create('gammes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->text('description')->nullable();
            $table->integer('fermentation_jours');
            $table->integer('vieillissement_jours');
            $table->timestamps();
        });

        // Table Lot_Production
        Schema::create('lot_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_gamme')->constrained('gammes')->onDelete('cascade');
            $table->foreignId('id_bouteille')->constrained('type_bouteilles')->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_mise_en_bouteille')->nullable();
            $table->date('date_commercialisation')->nullable();
            $table->integer('nombre_bouteilles')->nullable();
            $table->timestamps();
        });

        // Table Detail_Mouvement_Stock_Matiere_Premiere
        Schema::create('detail_mouvement_stock_matiere_premieres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_fournisseur')->nullable()->constrained('fournisseurs')->onDelete('set null');
            $table->foreignId('id_employe')->nullable()->constrained('employes')->onDelete('set null');
            $table->foreignId('id_lot')->nullable()->constrained('lot_productions')->onDelete('set null');
            $table->date('date_reception')->nullable();
            $table->date('date_expiration')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });

        // Table Mouvement_Stock_Matiere_Premiere
        Schema::create('mouvement_stock_matiere_premieres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_matiere')->constrained('matiere_premieres')->onDelete('cascade');
            $table->foreignId('id_type_mouvement')->constrained('type_mouvements')->onDelete('cascade');
            $table->foreignId('id_detail_mouvement')->constrained('detail_mouvement_stock_matiere_premieres')->onDelete('cascade');
            $table->decimal('quantite', 10, 2);
            $table->dateTime('date_mouvement');
            $table->timestamps();
        });

        // Table Detail_Lot_Production
        Schema::create('detail_lot_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lot')->constrained('lot_productions')->onDelete('cascade');
            $table->foreignId('id_employe')->nullable()->constrained('employes')->onDelete('set null');
            $table->dateTime('date_enregistrement');
            $table->text('parametres_production')->nullable();
            $table->text('remarques')->nullable();
            $table->timestamps();
        });

        // Table Detail_Mouvement_Produits
        Schema::create('detail_mouvement_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_employe')->nullable()->constrained('employes')->onDelete('set null');
            $table->foreignId('id_lot')->constrained('lot_productions')->onDelete('cascade');
            $table->string('emplacement', 100)->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });

        // Table Mouvement_Produits
        Schema::create('mouvement_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lot')->constrained('lot_productions')->onDelete('cascade');
            $table->foreignId('id_detail_mouvement')->constrained('detail_mouvement_produits')->onDelete('cascade');
            $table->integer('quantite_bouteilles');
            $table->dateTime('date_mouvement');
            $table->integer('stock_actuel')->default(0);
            $table->integer('seuil_minimum')->default(0);
            $table->date('date_mise_a_jour')->nullable();
            $table->timestamps();
        });

        // Table Controle_Qualite
        Schema::create('controle_qualites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lot')->constrained('lot_productions')->onDelete('cascade');
            $table->date('date_controle');
            $table->string('resultat', 100);
            $table->text('remarque')->nullable();
            $table->foreignId('id_employe')->nullable()->constrained('employes')->onDelete('set null');
            $table->timestamps();
        });

        // Table Statut_Commande
        Schema::create('statut_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();
            $table->timestamps();
        });

        // Table Commande
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_client')->constrained('clients')->onDelete('cascade');
            $table->date('date_commande');
            $table->date('date_livraison')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });
        //table commande_paiments
        Schema::create('paiement_commandes', function (Blueprint $table) {
            $table->id(); // Clé primaire personnalisée
            $table->foreignId('id_commande')->constrained('commandes')->onDelete('cascade');
            $table->integer('montant');
            $table->date('date_paiement')->nullable();
            $table->timestamps();

        });
        
        Schema::create('prix', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->foreignId('id_gamme')->constrained('gammes')->onDelete('cascade'); // lien avec la table "gammes"
            $table->decimal('prix_unitaire', 10, 2);
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->timestamps();

        });
        // Table Ligne_Commande
        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->foreignId('id_commande')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('id_lot')->constrained('lot_productions')->onDelete('cascade');
            $table->integer('quantite_bouteilles');
            $table->foreignId('id_prix')->constrained('prix')->onDelete('cascade');
            $table->primary(['id_commande', 'id_lot']);
            $table->timestamps();
        });
        
        // Table Mouvement_Produits_Commande
            Schema::create('mouvement_produits_commandes', function (Blueprint $table) {
                $table->foreignId('id_mouvement_produit')->constrained('mouvement_produits')->onDelete('cascade');
                $table->foreignId('id_commande')->constrained('commandes')->onDelete('cascade');
                $table->integer('quantite');
                $table->dateTime('date_association')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->primary(['id_mouvement_produit', 'id_commande']);
                $table->timestamps();
            });
        

        // Table Vente
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_commande')->constrained('commandes')->onDelete('cascade');
            $table->date('date_vente');
            $table->decimal('montant', 10, 2);
            $table->timestamps();
        });

        //hitorique commande 
        Schema::create('historique_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_commande')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('id_status_commande')->constrained('statut_commandes')->onDelete('cascade');
            $table->timestamps();
        });
        // Table Gamme_Matiere
        Schema::create('gamme_matieres', function (Blueprint $table) {
            $table->foreignId('id_gamme')->constrained('gammes')->onDelete('cascade');
            $table->foreignId('id_matiere')->constrained('matiere_premieres')->onDelete('cascade');
            $table->decimal('quantite', 10, 2);
            $table->primary(['id_gamme', 'id_matiere']);
            $table->timestamps();
        });
       
    }

    public function down()
    {
        Schema::dropIfExists('gamme_matieres');
        Schema::dropIfExists('ventes');
        Schema::dropIfExists('mouvement_produits_commandes');
        Schema::dropIfExists('prix');
        Schema::dropIfExists('ligne_commandes');
        Schema::dropIfExists('commandes');
        Schema::dropIfExists('historique_commandes');
        Schema::dropIfExists('statut_commandes');
        Schema::dropIfExists('paiement_commandes');
        Schema::dropIfExists('controle_qualites');
        Schema::dropIfExists('mouvement_produits');
        Schema::dropIfExists('detail_mouvement_produits');
        Schema::dropIfExists('detail_lot_productions');
        Schema::dropIfExists('mouvement_stock_matiere_premieres');
        Schema::dropIfExists('detail_mouvement_stock_matiere_premieres');
        Schema::dropIfExists('lot_productions');
        Schema::dropIfExists('gammes');
        Schema::dropIfExists('statut_lots');
        Schema::dropIfExists('type_bouteilles');
        Schema::dropIfExists('materiels');
        Schema::dropIfExists('type_materiels');
        Schema::dropIfExists('employes');
        Schema::dropIfExists('statut_employes');
        Schema::dropIfExists('departements');
        Schema::dropIfExists('matiere_premieres');
        Schema::dropIfExists('type_mouvements');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('type_clients');
        Schema::dropIfExists('fournisseurs');
    }
}