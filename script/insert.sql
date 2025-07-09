INSERT INTO departements (nom, description, created_at, updated_at)
VALUES
    ('Production', 'Département responsable de la production', NOW(), NOW()),
    ('Commerciales', 'Département des ventes et marketing', NOW(), NOW()),
    ('Logistiques', 'Département de gestion de stock', NOW(), NOW());

INSERT INTO statut_employes (nom, created_at, updated_at)
VALUES
    ('Actif', NOW(), NOW()),
    ('Inactif', NOW(), NOW()),
    ('En congé', NOW(), NOW());
INSERT INTO employes (nom, poste, email, telephone, id_statut_employe, id_departement, created_at, updated_at)
VALUES
    ('Jean Dupont', 'Manager', 'jean.dupont@example.com', '0123456789', 1, 1, NOW(), NOW()),
    ('Marie Durand', 'Technicien', 'marie.durand@example.com', '0987654321', 1, 1, NOW(), NOW()),
    ('Pierre Martin', 'Commercial', 'pierre.martin@example.com', '0345678901', 1, 2, NOW(), NOW()),
    ('Sophie Lefèvre', 'Recruteur', 'sophie.lefevre@example.com', '0765432109', 1, 3, NOW(), NOW());



    -- ======= Ajout des données de référence =======

-- Clients
INSERT INTO clients (id, nom) VALUES
  (1, 'Andry Rabe'),
  (2, 'Miora Ranaivo');

-- Gammes de produits
INSERT INTO gammes (id, nom) VALUES
  (1, 'Haut de gamme'),
  (2, 'Moyen de gamme'),
  (3, 'Bas de gamme');

-- Statuts de commande
INSERT INTO statut_commandes (nom) VALUES
  ('ouvert'),
  ('valider'),
  ('annuler');

-- Prix unitaire par gamme
INSERT INTO prix (id_gamme, prix_unitaire, date_debut, date_fin) VALUES
  (1, 1500.00, '2025-07-01', NULL),
    (2, 1800.00, '2025-07-01', NULL);
    INSERT INTO Type_Bouteilles (id, nom, capacite) VALUES
    (1, 'Petite', 0.5),
    (2, 'Grande', 1.0);

-- Lots de production
INSERT INTO lot_Productions (
  id_gamme, id_bouteille, date_debut, date_mise_en_bouteille, date_commercialisation, nombre_bouteilles
) VALUES
  (1, 1, '2025-07-01', '2025-07-02', '2025-07-04', 500),
  (2, 1, '2025-07-01', '2025-07-03', '2025-07-05', 300);

-- ======= Correction de la table Commande (si pas encore faite) =======

-- Commandes
-- On suppose que les statuts 'ouvert', 'valider', 'annuler' ont les IDs 1, 2, 3
INSERT INTO commandes (id,id_client, date_commande, date_livraison) VALUES
  (1,1, '2025-07-05', '2025-07-07'),
  (2,2, '2025-07-05', '2025-07-08');

-- Historique des statuts de commande
INSERT INTO historique_commandes (id_commande, id_status_commande,date_hist) VALUES
  (1, 1 , '2025-07-07'),
  (2, 1 , '2025-07-08');


-- Détail des commandes (Ligne_Commandes)
-- Supposons que les IDs des prix soient 1 et 2
INSERT INTO ligne_commandes (id_commande, id_lot, quantite_bouteilles, id_prix) VALUES
  (1, 2, 2, 1),
  (2, 1, 3, 2);
INSERT INTO paiement_commandes (id_commande, montant, date_paiement) VALUES
  ( 1, 4500, '2025-07-05'),
  ( 2, 5400, '2025-07-06')
  INSERT INTO Detail_Mouvement_Produits (id_employe, id_lot, emplacement, commentaire) VALUES
  (1, 4, 'Entrepôt principal - Rayon A1', 'Déplacement pour stockage initial.');

UPDATE commandes
SET date_livraison = '2025-07-05'
WHERE id = 1;

INSERT INTO Commandes (id_client, date_commande, date_livraison) VALUES
  (1, '2025-07-01', '2025-07-03'),
  (2, '2025-07-02', '2025-07-04'),
  (3, '2025-07-03', '2025-07-05'),
  (4, '2025-07-04', '2025-07-06'),
  (1, '2025-07-05', '2025-07-07'),
  (2, '2025-07-06', '2025-07-08'),
  (3, '2025-07-07', '2025-07-09'),
  (4, '2025-07-08', '2025-07-10');



///
INSERT INTO detail_mouvement_produits (id_employe, id_lot, emplacement, commentaire, created_at, updated_at)
VALUES
-- Lot 1 : préparé par Rakoto Jean
(11, 1, 'Entrepôt A - Rayon 3', 'Mise en stock après embouteillage terminée le 06 juillet.', NOW(), NOW()),

-- Lot 2 : contrôle qualité par Rabe Marie
(12, 2, 'Zone de contrôle', 'Échantillons prélevés pour analyse qualité.', NOW(), NOW()),

-- Lot 3 : supervision par Andriamanga Lala
(13, 3, 'Cave de vieillissement', 'Produit en phase de vieillissement terminé, prêt à commercialiser.', NOW(), NOW()),

-- Lot 4 : réceptionné par Razafindrakoto Hery
(14, 4, 'Entrepôt B - Réserve haute', 'Attente de tests finaux avant commercialisation.', NOW(), NOW()),

-- Lot 5 : opération sur ligne par Rakotomalala Mamy
(15, 5, 'Ligne 2 - Prêt à expédier', 'Produits finalisés et prêts à expédier.', NOW(), NOW()),

-- Lot 6 : préparé par Rakoto Jean
(11, 6, 'Entrepôt A - Rayon 1', 'Nouvelle production enregistrée, en attente de validation.', NOW(), NOW());


INSERT INTO mouvement_produits (
    id_lot, id_detail_mouvement, quantite_bouteilles,
    date_mouvement, stock_actuel, seuil_minimum, date_mise_a_jour,
    created_at, updated_at
)
VALUES
    (1, 7, 1142, '2025-07-06', 1142, 100, '2025-07-06', NOW(), NOW()),
    (2, 8, 666,  '2025-04-02', 666,  100, '2025-04-02', NOW(), NOW()),
    (3, 9, 200,  '2024-06-11', 200,  100, '2024-06-11', NOW(), NOW()),
    (4, 10, 200, '2024-12-21', 200,  100, '2024-12-21', NOW(), NOW()),
    (5, 11, 1333,'2025-02-09', 1333, 100, '2025-02-09', NOW(), NOW()),
    (6, 12, 1500,'2025-05-05', 1500, 100, '2025-05-05', NOW(), NOW());

INSERT INTO mouvement_produits_commandes (
    id_mouvement_produit, id_commande, quantite, date_association, created_at, updated_at
)
VALUES
    (13, 1, 40,  '2025-03-14 10:00:00', NOW(), NOW()),
    (14, 2, 133, '2025-06-12 10:00:00', NOW(), NOW()),
    (15, 3, 350, '2025-05-13 10:00:00', NOW(), NOW());


INSERT INTO detail_lot_productions (
    id_lot, id_employe, date_enregistrement, parametres_production, remarques, created_at, updated_at
)
VALUES
-- Lot 1 : Rakoto Jean
(1, 11, '2025-07-06 08:30:00', 'Température : 28°C, Humidité : 65%', 'Production conforme', NOW(), NOW()),

-- Lot 2 : Rabe Marie
(2, 12, '2025-04-02 09:00:00', 'pH = 4.2, Température : 25°C', 'Contrôle qualité validé', NOW(), NOW()),

-- Lot 3 : Andriamanga Lala
(3, 13, '2024-06-11 10:00:00', 'Fermentation lente, 10 jours', 'Mise en cave correcte', NOW(), NOW()),

-- Lot 4 : Razafindrakoto Hery
(4, 14, '2024-12-21 11:00:00', 'Double distillation', 'À vérifier avant commercialisation', NOW(), NOW()),

-- Lot 5 : Rakotomalala Mamy
(5, 15, '2025-02-09 08:45:00', 'Température ligne : 30°C', 'Produit clair et stable', NOW(), NOW()),

-- Lot 6 : Rakoto Jean
(6, 11, '2025-05-05 07:50:00', 'Recette standard', 'Rien à signaler', NOW(), NOW());

