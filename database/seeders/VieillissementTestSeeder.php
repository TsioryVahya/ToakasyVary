<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VieillissementTestSeeder extends Seeder
{
    public function run()
    {
        // Nettoyer les données existantes (seulement les tables qui existent)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Vérifier si les tables existent avant de les vider
        $tables = ['gamme_matieres', 'lot_productions', 'gammes', 'type_bouteilles', 'matiere_premieres'];
        
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
                echo "Table {$table} vidée\n";
            } else {
                echo "Table {$table} n'existe pas encore\n";
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Insérer des matières premières
        $matieres = [
            ['id' => 1, 'nom' => 'Raisin Rouge', 'description' => 'Raisin pour vin rouge', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'nom' => 'Raisin Blanc', 'description' => 'Raisin pour vin blanc', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'nom' => 'Levure', 'description' => 'Levure pour fermentation', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'nom' => 'Sucre', 'description' => 'Sucre pour ajustement', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach ($matieres as $matiere) {
            DB::table('matiere_premieres')->insert($matiere);
        }

        // 2. Insérer des types de bouteilles
        $bouteilles = [
            ['id' => 1, 'nom' => 'Bouteille Standard 750ml', 'capacite' => 0.75, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'nom' => 'Bouteille Magnum 1.5L', 'capacite' => 1.5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'nom' => 'Bouteille Demi 375ml', 'capacite' => 0.375, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach ($bouteilles as $bouteille) {
            DB::table('type_bouteilles')->insert($bouteille);
        }

        // 3. Insérer des gammes
        $gammes = [
            [
                'id' => 1,
                'nom' => 'Vin Rouge Classique',
                'description' => 'Vin rouge traditionnel',
                'fermentation_jours' => 15,
                'vieillissement_jours' => 60,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'nom' => 'Vin Blanc Premium',
                'description' => 'Vin blanc haut de gamme',
                'fermentation_jours' => 10,
                'vieillissement_jours' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'nom' => 'Vin Rouge Réserve',
                'description' => 'Vin rouge longue garde',
                'fermentation_jours' => 20,
                'vieillissement_jours' => 120,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 4,
                'nom' => 'Vin Rosé',
                'description' => 'Vin rosé léger',
                'fermentation_jours' => 8,
                'vieillissement_jours' => 20,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        foreach ($gammes as $gamme) {
            DB::table('gammes')->insert($gamme);
        }

        // 4. Insérer des lots de production
        $lots = [
            // Lots en cours de fermentation
            [
                'id' => 1,
                'id_gamme' => 1,
                'id_bouteille' => 1,
                'date_debut' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'date_mise_en_bouteille' => null,
                'date_commercialisation' => null,
                'nombre_bouteilles' => 1000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'id_gamme' => 2,
                'id_bouteille' => 1,
                'date_debut' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'date_mise_en_bouteille' => null,
                'date_commercialisation' => null,
                'nombre_bouteilles' => 800,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            
            // Lot terminé
            [
                'id' => 3,
                'id_gamme' => 4,
                'id_bouteille' => 1,
                'date_debut' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'date_mise_en_bouteille' => Carbon::now()->subDays(22)->format('Y-m-d'),
                'date_commercialisation' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'nombre_bouteilles' => 500,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            
            // Lots en vieillissement
            [
                'id' => 4,
                'id_gamme' => 1,
                'id_bouteille' => 2,
                'date_debut' => Carbon::now()->subDays(40)->format('Y-m-d'),
                'date_mise_en_bouteille' => Carbon::now()->subDays(25)->format('Y-m-d'),
                'date_commercialisation' => null,
                'nombre_bouteilles' => 600,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 5,
                'id_gamme' => 3,
                'id_bouteille' => 1,
                'date_debut' => Carbon::now()->subDays(50)->format('Y-m-d'),
                'date_mise_en_bouteille' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'date_commercialisation' => null,
                'nombre_bouteilles' => 1200,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            
            // Lots futurs
            [
                'id' => 6,
                'id_gamme' => 2,
                'id_bouteille' => 3,
                'date_debut' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'date_mise_en_bouteille' => null,
                'date_commercialisation' => null,
                'nombre_bouteilles' => 400,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 7,
                'id_gamme' => 1,
                'id_bouteille' => 1,
                'date_debut' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'date_mise_en_bouteille' => null,
                'date_commercialisation' => null,
                'nombre_bouteilles' => 900,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            
            // Lot avec chevauchement
            [
                'id' => 8,
                'id_gamme' => 4,
                'id_bouteille' => 1,
                'date_debut' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'date_mise_en_bouteille' => null,
                'date_commercialisation' => null,
                'nombre_bouteilles' => 700,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        foreach ($lots as $lot) {
            DB::table('lot_productions')->insert($lot);
        }

        // 5. Insérer les relations gamme-matière
        $relations = [
            ['id_gamme' => 1, 'id_matiere' => 1, 'quantite' => 100.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_gamme' => 1, 'id_matiere' => 3, 'quantite' => 2.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_gamme' => 2, 'id_matiere' => 2, 'quantite' => 80.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_gamme' => 2, 'id_matiere' => 3, 'quantite' => 1.5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_gamme' => 3, 'id_matiere' => 1, 'quantite' => 120.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_gamme' => 3, 'id_matiere' => 3, 'quantite' => 3.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_gamme' => 4, 'id_matiere' => 1, 'quantite' => 50.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_gamme' => 4, 'id_matiere' => 2, 'quantite' => 30.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_gamme' => 4, 'id_matiere' => 3, 'quantite' => 1.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach ($relations as $relation) {
            DB::table('gamme_matieres')->insert($relation);
        }

        echo "Données de test pour le vieillissement créées avec succès!\n";
        echo "8 lots créés avec différents statuts :\n";
        echo "- 2 lots en fermentation\n";
        echo "- 2 lots en vieillissement\n";
        echo "- 1 lot terminé\n";
        echo "- 2 lots à venir\n";
        echo "- 1 lot avec chevauchement\n";
    }
}