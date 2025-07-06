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

        // 1. Insérer des matières premières pour la production de rhum
        $matieres = [
            ['id' => 1, 'nom' => 'Riz', 'description' => 'Riz pour fermentation alcoolique', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'nom' => 'Levure', 'description' => 'Levure spéciale rhum', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'nom' => 'Enzyme', 'description' => 'Enzyme pour conversion amidon', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'nom' => 'Arôme', 'description' => 'Arômes naturels pour rhum', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'nom' => 'Sucre de canne', 'description' => 'Pour ajustement et vieillissement', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach ($matieres as $matiere) {
            DB::table('matiere_premieres')->insert($matiere);
        }

        // 2. Insérer des types de bouteilles
        $bouteilles = [
            ['id' => 1, 'nom' => 'Bouteille Standard 700ml', 'capacite' => 0.7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'nom' => 'Bouteille Premium 750ml', 'capacite' => 0.75, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'nom' => 'Bouteille Collector 1L', 'capacite' => 1.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach ($bouteilles as $bouteille) {
            DB::table('type_bouteilles')->insert($bouteille);
        }

        // 3. Insérer les gammes de rhum
        $gammes = [
            [
                'id' => 1,
                'nom' => 'Rhum Bas de Gamme',
                'description' => 'Rhum jeune et économique',
                'fermentation_jours' => 3,
                'vieillissement_jours' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'nom' => 'Rhum Moyen de Gamme',
                'description' => 'Rhum vieilli en fût de chêne',
                'fermentation_jours' => 5,
                'vieillissement_jours' => 121,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'nom' => 'Rhum Haut de Gamme',
                'description' => 'Rhum premium vieilli longtemps',
                'fermentation_jours' => 10,
                'vieillissement_jours' => 275,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        foreach ($gammes as $gamme) {
            DB::table('gammes')->insert($gamme);
        }

        // 4. Insérer des lots de production de rhum (ajustés pour 1t = 100L)
        $lots = [
            // Lot bas de gamme en fermentation (8 tonnes = 800L)
            [
                'id' => 1,
                'id_gamme' => 1,
                'id_bouteille' => 1,
                'date_debut' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'date_mise_en_bouteille' => null,
                'date_commercialisation' => null,
                'nombre_bouteilles' => floor(800 / 0.7), // 800L / 0.7L
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            // Lot moyen de gamme en vieillissement (5 tonnes = 500L)
            [
                'id' => 2,
                'id_gamme' => 2,
                'id_bouteille' => 2,
                'date_debut' => Carbon::now()->subDays(100)->format('Y-m-d'),
                'date_mise_en_bouteille' => null,
                'date_commercialisation' => null,
                'nombre_bouteilles' => floor(500 / 0.75), // 500L / 0.75L
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            // Lot haut de gamme terminé (2 tonnes = 200L)
            [
                'id' => 3,
                'id_gamme' => 3,
                'id_bouteille' => 3,
                'date_debut' => Carbon::now()->subDays(400)->format('Y-m-d'),
                'date_mise_en_bouteille' =>null,//misy valeur
                'date_commercialisation' => null,
                'nombre_bouteilles' => floor(200 / 1.0), // 200L / 1.0L
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            // Lot haut de gamme en vieillissement long (1.5 tonne = 150L)
            [
                'id' => 4,
                'id_gamme' => 3,
                'id_bouteille' => 2,
                'date_debut' => Carbon::now()->subDays(200)->format('Y-m-d'),
                'date_mise_en_bouteille' =>null,
                'date_commercialisation' => null,
                'nombre_bouteilles' => floor(150 / 0.75), // 150L / 0.75L
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            // Lot  moyen de gamme termine (10 tonnes = 1000L)
            [
                'id' => 5,
                'id_gamme' => 2,
                'id_bouteille' => 2,
                'date_debut' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'date_mise_en_bouteille' => null,
                'date_commercialisation' => null,
                'nombre_bouteilles' => floor(1000 / 0.75), // 1000L / 0.75L
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        foreach ($lots as $lot) {
            DB::table('lot_productions')->insert($lot);
        }

        // 5. Insérer les relations gamme-matière (quantités pour 100L de rhum = 1 tonne de riz)
        $relations = [
            // Bas de gamme (pour 100L)
            ['id_gamme' => 1, 'id_matiere' => 1, 'quantite' => 1000.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // 1 tonne de riz
            ['id_gamme' => 1, 'id_matiere' => 2, 'quantite' => 1.5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],    // 1.5 kg levure
            ['id_gamme' => 1, 'id_matiere' => 3, 'quantite' => 0.5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],    // 0.5 kg enzyme

            // Moyen de gamme (pour 100L)
            ['id_gamme' => 2, 'id_matiere' => 1, 'quantite' => 1000.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // 1 tonne de riz
            ['id_gamme' => 2, 'id_matiere' => 2, 'quantite' => 2.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],    // 2 kg levure
            ['id_gamme' => 2, 'id_matiere' => 3, 'quantite' => 1.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],    // 1 kg enzyme
            ['id_gamme' => 2, 'id_matiere' => 5, 'quantite' => 5.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],    // 5 kg sucre

            // Haut de gamme (pour 100L)
            ['id_gamme' => 3, 'id_matiere' => 1, 'quantite' => 1000.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // 1 tonne de riz
            ['id_gamme' => 3, 'id_matiere' => 2, 'quantite' => 3.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],    // 3 kg levure
            ['id_gamme' => 3, 'id_matiere' => 3, 'quantite' => 1.5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],    // 1.5 kg enzyme
            ['id_gamme' => 3, 'id_matiere' => 4, 'quantite' => 2.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],    // 2 kg arôme
            ['id_gamme' => 3, 'id_matiere' => 5, 'quantite' => 10.0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],   // 10 kg sucre
        ];

        foreach ($relations as $relation) {
            DB::table('gamme_matieres')->insert($relation);
        }

        echo "Données de test pour la production de rhum créées avec succès!\n";
        echo "5 lots créés avec différents statuts :\n";
        echo "- 1 lot bas de gamme en fermentation\n";
        echo "- 1 lot moyen de gamme en vieillissement\n";
        echo "- 1 lot haut de gamme terminé\n";
        echo "- 1 lot haut de gamme en vieillissement long\n";
        echo "- 1 lot moyen de gamme à venir\n";
    }
}
