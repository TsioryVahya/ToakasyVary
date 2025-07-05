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
INSERT INTO Clients (id, nom) VALUES
  (3, 'rabe'),
  (4, 'mamela');

-- Gammes de produits
INSERT INTO Gammes (id, nom) VALUES
  (1, 'Jus Mangue'),
  (2, 'Jus Litchi');

-- Statuts de commande
INSERT INTO Statut_Commandes (nom) VALUES 
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
INSERT INTO Lot_Productions (
  id_gamme, id_bouteille, date_debut, date_mise_en_bouteille, date_commercialisation, nombre_bouteilles
) VALUES
  (1, 1, '2025-07-01', '2025-07-02', '2025-07-04', 500),
  (2, 1, '2025-07-01', '2025-07-03', '2025-07-05', 300);

-- ======= Correction de la table Commande (si pas encore faite) =======

-- Commandes
-- On suppose que les statuts 'ouvert', 'valider', 'annuler' ont les IDs 1, 2, 3
INSERT INTO Commandes (id_client, date_commande, date_livraison) VALUES
  (1, '2025-07-05', '2025-07-07'),
  (2, '2025-07-05', '2025-07-08');

-- Historique des statuts de commande
INSERT INTO historique_commandes (id_commande, id_statut_commande,date_hist) VALUES
  (1, 1 , '2025-07-07'),
  (2, 2 , '2025-07-08');


-- Détail des commandes (Ligne_Commandes)
-- Supposons que les IDs des prix soient 1 et 2
INSERT INTO Ligne_commandes (id_commande, id_lot, quantite_bouteilles, id_prix) VALUES
  (1, 4, 2, 1),
  (2, 3, 3, 2);
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
