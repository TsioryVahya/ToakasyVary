-- 1. Type Clients
INSERT INTO type_clients (nom) VALUES
('Particulier'),
('Professionnel');

-- 2. Statut Commandes
INSERT INTO statut_commandes (nom) VALUES
('En attente'),
('Confirmée'),
('Livrée');

-- 3. Type Bouteilles
INSERT INTO type_bouteilles (nom, capacite) VALUES
('Bouteille 75cl', 0.75);

-- 4. Gammes
INSERT INTO gammes (nom, description, fermentation_jours, vieillissement_jours) VALUES
('Classique', 'Gamme classique', 5, 10),
('Premium', 'Gamme haut de gamme', 10, 20),
('Bio', 'Gamme biologique', 7, 15);

-- 5. Lots de production
INSERT INTO lot_productions (id_gamme, id_bouteille, date_debut, nombre_bouteilles, created_at, updated_at) VALUES
(1, 1, '2025-01-01', 1000, NOW(), NOW()),
(2, 1, '2025-02-01', 700, NOW(), NOW()),
(3, 1, '2025-03-01', 800, NOW(), NOW());

-- 6. Clients
INSERT INTO clients (nom, id_type_client, email, telephone, adresse, created_at, updated_at) VALUES
('Client A', 1, 'a@example.com', '0600000001', 'Adresse A', NOW(), NOW()),
('Client B', 2, 'b@example.com', '0600000002', 'Adresse B', NOW(), NOW());

-- 7. Commandes
INSERT INTO commandes (id_client, date_commande, total, created_at, updated_at) VALUES
(1, '2025-01-10', 1000, NOW(), NOW()),
(2, '2025-02-15', 1400, NOW(), NOW()),
(1, '2025-03-20', 1600, NOW(), NOW());

-- 8. Lignes de commande
INSERT INTO ligne_commandes (id_commande, id_lot, quantite_bouteilles, id_prix, created_at, updated_at) VALUES
(1, 1, 100, 4, NOW(), NOW()),
(2, 2, 140, 3, NOW(), NOW()),
(3, 3, 160, 3, NOW(), NOW());

-- 9. Ventes
INSERT INTO ventes (id_commande, date_vente, montant, created_at, updated_at) VALUES
(1, '2025-01-12', 1000.00, NOW(), NOW()),
(2, '2025-02-17', 1400.00, NOW(), NOW()),
(3, '2025-03-25', 1600.00, NOW(), NOW());





INSERT INTO matiere_premieres (nom, description) VALUES
('Sucre', 'Sucre blanc'),
('Levure', 'Levure active'),
('Eau', 'Eau pure');
INSERT INTO prix_materiels (id_matiere_premiere, prix, created_at, updated_at) VALUES
(1, 0.50, NOW(), NOW()),  -- Sucre à 0.50€/kg
(2, 1.20, NOW(), NOW()),  -- Levure à 1.20€/kg
(3, 0.05, NOW(), NOW());  -- Eau à 0.05€/litre
INSERT INTO gamme_matiere (id_gamme, id_matiere, quantite) VALUES
(1, 1, 0.2),  -- Gamme Classique utilise 0.2 kg sucre par bouteille
(1, 2, 0.01), -- 0.01 kg levure par bouteille
(1, 3, 0.75), -- 0.75 litre eau par bouteille

(2, 1, 0.25),  -- Gamme Premium : 0.25 kg sucre
(2, 2, 0.015), -- 0.015 kg levure
(2, 3, 0.75),  -- 0.75 litre eau

(3, 1, 0.18),  -- Gamme Bio : 0.18 kg sucre
(3, 2, 0.012), -- 0.012 kg levure
(3, 3, 0.75);  -- 0.75 litre eau
