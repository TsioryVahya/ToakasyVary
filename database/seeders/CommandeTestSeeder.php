<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommandeTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier d'abord la structure de la table clients
        $clientColumns = DB::getSchemaBuilder()->getColumnListing('clients');
        
        // Insérer des clients de test selon la structure existante
        $clients = [
            [
                'nom' => 'Jean Martin',
                'email' => 'jean.martin@email.com',
                'telephone' => '0261234567',
                'adresse' => '123 Avenue de l\'Indépendance, Antananarivo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nom' => 'Marie Rakoto',
                'email' => 'marie.rakoto@email.com',
                'telephone' => '0341234567',
                'adresse' => '456 Rue Rainitovo, Fianarantsoa',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nom' => 'Pierre Andriamanjato',
                'email' => 'pierre.andriamanjato@email.com',
                'telephone' => '0321234567',
                'adresse' => '789 Boulevard Joffre, Toamasina',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nom' => 'Sophie Rasoamalala',
                'email' => 'sophie.rasoamalala@email.com',
                'telephone' => '0371234567',
                'adresse' => '321 Rue Colbert, Mahajanga',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nom' => 'Paul Randrianarisoa',
                'email' => 'paul.randrianarisoa@email.com',
                'telephone' => '0381234567',
                'adresse' => '654 Avenue Philibert Tsiranana, Toliara',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('clients')->insert($clients);

        // Vérifier si la table statut_commandes existe
        if (DB::getSchemaBuilder()->hasTable('statut_commandes')) {
            // Insérer des statuts de commande
            $statuts = [
                ['nom' => 'En attente', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['nom' => 'Validée', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['nom' => 'Annulée', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['nom' => 'Livrée', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            ];

            DB::table('statut_commandes')->insert($statuts);
        }

        // Insérer des commandes de test (JUILLET 2025)
        $commandes = [
            [
                'id_client' => 1,
                'date_commande' => '2025-07-01',
                'date_livraison' => '2025-07-05',
                'total' => 450000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 2,
                'date_commande' => '2025-07-02',
                'date_livraison' => '2025-07-08',
                'total' => 280000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 3,
                'date_commande' => '2025-07-03',
                'date_livraison' => '2025-07-10',
                'total' => 650000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 4,
                'date_commande' => '2025-07-04',
                'date_livraison' => '2025-07-12',
                'total' => 320000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 5,
                'date_commande' => '2025-07-05',
                'date_livraison' => '2025-07-15',
                'total' => 180000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 1,
                'date_commande' => '2025-06-28',
                'date_livraison' => '2025-07-02',
                'total' => 520000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 2,
                'date_commande' => '2025-06-25',
                'date_livraison' => '2025-07-01',
                'total' => 390000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 3,
                'date_commande' => '2025-06-30',
                'date_livraison' => '2025-07-06',
                'total' => 740000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 4,
                'date_commande' => '2025-06-20',
                'date_livraison' => '2025-06-28',
                'total' => 260000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 5,
                'date_commande' => '2025-06-15',
                'date_livraison' => '2025-06-25',
                'total' => 480000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            // Commandes de mai 2025
            [
                'id_client' => 1,
                'date_commande' => '2025-05-15',
                'date_livraison' => '2025-05-22',
                'total' => 380000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 2,
                'date_commande' => '2025-05-10',
                'date_livraison' => '2025-05-18',
                'total' => 220000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 3,
                'date_commande' => '2025-05-05',
                'date_livraison' => '2025-05-12',
                'total' => 560000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            // Commandes d'avril 2025
            [
                'id_client' => 4,
                'date_commande' => '2025-04-20',
                'date_livraison' => '2025-04-28',
                'total' => 420000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_client' => 5,
                'date_commande' => '2025-04-15',
                'date_livraison' => '2025-04-23',
                'total' => 340000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('commandes')->insert($commandes);

        // Vérifier si la table prix existe avant d'insérer
        if (DB::getSchemaBuilder()->hasTable('prix')) {
            // Insérer des prix pour les gammes
            $prix = [
                [
                    'id_gamme' => 1,
                    'prix_unitaire' => 45000.00,
                    'date_debut' => '2025-01-01',
                    'date_fin' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id_gamme' => 2,
                    'prix_unitaire' => 35000.00,
                    'date_debut' => '2025-01-01',
                    'date_fin' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id_gamme' => 3,
                    'prix_unitaire' => 55000.00,
                    'date_debut' => '2025-01-01',
                    'date_fin' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ];

            DB::table('prix')->insert($prix);
        }

        // Vérifier si la table ligne_commandes existe
        if (DB::getSchemaBuilder()->hasTable('ligne_commandes')) {
            // Insérer des lignes de commande
            $ligneCommandes = [
                // Commande 1
                ['id_commande' => 1, 'id_lot' => 1, 'quantite_bouteilles' => 10, 'id_prix' => 1],
                
                // Commande 2
                ['id_commande' => 2, 'id_lot' => 2, 'quantite_bouteilles' => 8, 'id_prix' => 2],
                
                // Commande 3
                ['id_commande' => 3, 'id_lot' => 1, 'quantite_bouteilles' => 12, 'id_prix' => 1],
                ['id_commande' => 3, 'id_lot' => 3, 'quantite_bouteilles' => 2, 'id_prix' => 3],
                
                // Commande 4
                ['id_commande' => 4, 'id_lot' => 2, 'quantite_bouteilles' => 6, 'id_prix' => 2],
                ['id_commande' => 4, 'id_lot' => 1, 'quantite_bouteilles' => 4, 'id_prix' => 1],
                
                // Commande 5
                ['id_commande' => 5, 'id_lot' => 3, 'quantite_bouteilles' => 3, 'id_prix' => 3],
                ['id_commande' => 5, 'id_lot' => 2, 'quantite_bouteilles' => 1, 'id_prix' => 2],
                
                // Commande 6
                ['id_commande' => 6, 'id_lot' => 1, 'quantite_bouteilles' => 8, 'id_prix' => 1],
                ['id_commande' => 6, 'id_lot' => 3, 'quantite_bouteilles' => 4, 'id_prix' => 3],
                
                // Commande 7
                ['id_commande' => 7, 'id_lot' => 2, 'quantite_bouteilles' => 11, 'id_prix' => 2],
                
                // Commande 8
                ['id_commande' => 8, 'id_lot' => 1, 'quantite_bouteilles' => 15, 'id_prix' => 1],
                ['id_commande' => 8, 'id_lot' => 2, 'quantite_bouteilles' => 2, 'id_prix' => 2],
                
                // Commande 9
                ['id_commande' => 9, 'id_lot' => 3, 'quantite_bouteilles' => 5, 'id_prix' => 3],
                
                // Commande 10
                ['id_commande' => 10, 'id_lot' => 1, 'quantite_bouteilles' => 7, 'id_prix' => 1],
                ['id_commande' => 10, 'id_lot' => 2, 'quantite_bouteilles' => 3, 'id_prix' => 2],
                ['id_commande' => 10, 'id_lot' => 3, 'quantite_bouteilles' => 2, 'id_prix' => 3],
                
                // Commandes supplémentaires
                ['id_commande' => 11, 'id_lot' => 1, 'quantite_bouteilles' => 9, 'id_prix' => 1],
                ['id_commande' => 11, 'id_lot' => 2, 'quantite_bouteilles' => 2, 'id_prix' => 2],
                
                ['id_commande' => 12, 'id_lot' => 2, 'quantite_bouteilles' => 6, 'id_prix' => 2],
                ['id_commande' => 12, 'id_lot' => 3, 'quantite_bouteilles' => 1, 'id_prix' => 3],
                
                ['id_commande' => 13, 'id_lot' => 1, 'quantite_bouteilles' => 10, 'id_prix' => 1],
                ['id_commande' => 13, 'id_lot' => 3, 'quantite_bouteilles' => 2, 'id_prix' => 3],
                
                ['id_commande' => 14, 'id_lot' => 2, 'quantite_bouteilles' => 8, 'id_prix' => 2],
                ['id_commande' => 14, 'id_lot' => 1, 'quantite_bouteilles' => 2, 'id_prix' => 1],
                
                ['id_commande' => 15, 'id_lot' => 3, 'quantite_bouteilles' => 4, 'id_prix' => 3],
                ['id_commande' => 15, 'id_lot' => 2, 'quantite_bouteilles' => 2, 'id_prix' => 2],
            ];

            DB::table('ligne_commandes')->insert($ligneCommandes);
        }

        // Vérifier si la table historique_commandes existe
        if (DB::getSchemaBuilder()->hasTable('historique_commandes')) {
            // Insérer l'historique des commandes
            $historiqueCommandes = [
                // Commandes livrées (juin 2025)
                ['id_commande' => 6, 'id_status_commande' => 1, 'date_hist' => '2025-06-28', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 6, 'id_status_commande' => 2, 'date_hist' => '2025-06-29', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 6, 'id_status_commande' => 4, 'date_hist' => '2025-07-02', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 7, 'id_status_commande' => 1, 'date_hist' => '2025-06-25', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 7, 'id_status_commande' => 2, 'date_hist' => '2025-06-26', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 7, 'id_status_commande' => 4, 'date_hist' => '2025-07-01', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 8, 'id_status_commande' => 1, 'date_hist' => '2025-06-30', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 8, 'id_status_commande' => 2, 'date_hist' => '2025-07-01', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 8, 'id_status_commande' => 4, 'date_hist' => '2025-07-06', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 9, 'id_status_commande' => 1, 'date_hist' => '2025-06-20', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 9, 'id_status_commande' => 2, 'date_hist' => '2025-06-21', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 9, 'id_status_commande' => 4, 'date_hist' => '2025-06-28', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 10, 'id_status_commande' => 1, 'date_hist' => '2025-06-15', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 10, 'id_status_commande' => 2, 'date_hist' => '2025-06-16', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 10, 'id_status_commande' => 4, 'date_hist' => '2025-06-25', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                // Commandes livrées (mai 2025)
                ['id_commande' => 11, 'id_status_commande' => 1, 'date_hist' => '2025-05-15', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 11, 'id_status_commande' => 2, 'date_hist' => '2025-05-16', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 11, 'id_status_commande' => 4, 'date_hist' => '2025-05-22', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 12, 'id_status_commande' => 1, 'date_hist' => '2025-05-10', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 12, 'id_status_commande' => 2, 'date_hist' => '2025-05-11', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 12, 'id_status_commande' => 4, 'date_hist' => '2025-05-18', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 13, 'id_status_commande' => 1, 'date_hist' => '2025-05-05', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 13, 'id_status_commande' => 2, 'date_hist' => '2025-05-06', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 13, 'id_status_commande' => 4, 'date_hist' => '2025-05-12', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                // Commandes livrées (avril 2025)
                ['id_commande' => 14, 'id_status_commande' => 1, 'date_hist' => '2025-04-20', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 14, 'id_status_commande' => 2, 'date_hist' => '2025-04-21', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 14, 'id_status_commande' => 4, 'date_hist' => '2025-04-28', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 15, 'id_status_commande' => 1, 'date_hist' => '2025-04-15', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 15, 'id_status_commande' => 2, 'date_hist' => '2025-04-16', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 15, 'id_status_commande' => 4, 'date_hist' => '2025-04-23', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                // Commandes en cours (juillet 2025)
                ['id_commande' => 1, 'id_status_commande' => 1, 'date_hist' => '2025-07-01', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 1, 'id_status_commande' => 2, 'date_hist' => '2025-07-02', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 2, 'id_status_commande' => 1, 'date_hist' => '2025-07-02', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 2, 'id_status_commande' => 2, 'date_hist' => '2025-07-03', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 3, 'id_status_commande' => 1, 'date_hist' => '2025-07-03', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                
                ['id_commande' => 4, 'id_status_commande' => 1, 'date_hist' => '2025-07-04', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['id_commande' => 4, 'id_status_commande' => 3, 'date_hist' => '2025-07-05', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Annulée
                
                ['id_commande' => 5, 'id_status_commande' => 1, 'date_hist' => '2025-07-05', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ];

            DB::table('historique_commandes')->insert($historiqueCommandes);
        }

        // Insérer des ventes (pour les commandes livrées)
        if (DB::getSchemaBuilder()->hasTable('ventes')) {
            $ventes = [
                // Ventes de juillet 2025
                [
                    'id_commande' => 6,
                    'date_vente' => '2025-07-02',
                    'montant' => 520000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id_commande' => 7,
                    'date_vente' => '2025-07-01',
                    'montant' => 390000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id_commande' => 8,
                    'date_vente' => '2025-07-06',
                    'montant' => 740000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                
                // Ventes de juin 2025
                [
                    'id_commande' => 9,
                    'date_vente' => '2025-06-28',
                    'montant' => 260000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id_commande' => 10,
                    'date_vente' => '2025-06-25',
                    'montant' => 480000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                
                // Ventes de mai 2025
                [
                    'id_commande' => 11,
                    'date_vente' => '2025-05-22',
                    'montant' => 380000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id_commande' => 12,
                    'date_vente' => '2025-05-18',
                    'montant' => 220000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id_commande' => 13,
                    'date_vente' => '2025-05-12',
                    'montant' => 560000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                
                // Ventes d'avril 2025
                [
                    'id_commande' => 14,
                    'date_vente' => '2025-04-28',
                    'montant' => 420000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id_commande' => 15,
                    'date_vente' => '2025-04-23',
                    'montant' => 340000.00,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ];

            DB::table('ventes')->insert($ventes);
        }

        echo "Données de test pour commandes et ventes créées avec succès!\n";
        echo "Période couverte : Avril 2025 - Juillet 2025\n";
        echo "- 15 commandes au total\n";
        echo "- 10 commandes livrées (avec ventes)\n";
        echo "- 3 commandes en cours\n";
        echo "- 1 commande annulée\n";
        echo "- 1 commande en attente\n";
    }
}