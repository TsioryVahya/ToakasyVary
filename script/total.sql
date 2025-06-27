-- --------------------------------------
-- Suppression de la table Stock_Produit_Fini
-- Fournisseur
CREATE TABLE Fournisseur (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  telephone VARCHAR(20),
  adresse TEXT,
  CONSTRAINT unique_nom_fournisseur UNIQUE (nom)
);

-- Type de client
CREATE TABLE Type_Client (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL UNIQUE
);

-- Client
CREATE TABLE Client (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  id_type_client INT,
  email VARCHAR(100),
  telephone VARCHAR(20),
  adresse TEXT,
  FOREIGN KEY (id_type_client) REFERENCES Type_Client(id),
  CONSTRAINT unique_nom_client UNIQUE (nom)
);

-- Gamme de produits
CREATE TABLE Gamme (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL UNIQUE
);

-- Type de mouvement
CREATE TABLE Type_Mouvement (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL UNIQUE
);

-- Matières premières
CREATE TABLE Matiere_Premiere (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  description TEXT,
  CONSTRAINT unique_nom_matiere UNIQUE (nom)
);

-- Départements
CREATE TABLE Departement (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL UNIQUE,
  description TEXT
);

-- Statut d'employé
CREATE TABLE Statut_Employe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL UNIQUE
);

-- Employés
CREATE TABLE Employe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  poste VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  telephone VARCHAR(20),
  id_statut_employe INT DEFAULT 1,
  id_departement INT NOT NULL,
  FOREIGN KEY (id_statut_employe) REFERENCES Statut_Employe(id),
  FOREIGN KEY (id_departement) REFERENCES Departement(id),
  CONSTRAINT unique_nom_employe UNIQUE (nom)
);

-- Détails des mouvements de stock des matières premières
CREATE TABLE Detail_Mouvement_Stock_Matiere_Premiere (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_fournisseur INT NULL,
  id_employe INT NULL,
  id_lot INT NULL,
  emplacement VARCHAR(100),
  lot_fournisseur VARCHAR(50),
  date_reception DATE,
  date_expiration DATE,
  commentaire TEXT,
  FOREIGN KEY (id_fournisseur) REFERENCES Fournisseur(id),
  FOREIGN KEY (id_employe) REFERENCES Employe(id),
  FOREIGN KEY (id_lot) REFERENCES Lot_Production(id)
);

-- Mouvements de stock des matières premières
CREATE TABLE Mouvement_Stock_Matiere_Premiere (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_matiere INT NOT NULL,
  id_type_mouvement INT NOT NULL,
  id_detail_mouvement INT NOT NULL,
  quantite DECIMAL(10,2) NOT NULL,
  date_mouvement DATETIME NOT NULL,
  stock_actuel DECIMAL(10,2) DEFAULT 0,
  seuil_minimum DECIMAL(10,2) DEFAULT 0,
  date_mise_a_jour DATE,
  FOREIGN KEY (id_matiere) REFERENCES Matiere_Premiere(id),
  FOREIGN KEY (id_type_mouvement) REFERENCES Type_Mouvement(id),
  FOREIGN KEY (id_detail_mouvement) REFERENCES Detail_Mouvement_Stock_Matiere_Premiere(id),
  CONSTRAINT check_quantite_positive CHECK (quantite > 0),
  CONSTRAINT check_stock_non_negatif CHECK (stock_actuel >= 0)
);

-- Recettes
CREATE TABLE Recette (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  id_gamme INT,
  description TEXT,
  fermentation_jours INT NOT NULL,
  FOREIGN KEY (id_gamme) REFERENCES Gamme(id),
  CONSTRAINT unique_nom_recette UNIQUE (nom)
);

-- Recette - Matière première (N:N)
CREATE TABLE Recette_Matiere (
  id_recette INT,
  id_matiere INT,
  quantite DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id_recette, id_matiere),
  FOREIGN KEY (id_recette) REFERENCES Recette(id),
  FOREIGN KEY (id_matiere) REFERENCES Matiere_Premiere(id),
  CONSTRAINT check_quantite_matiere_positive CHECK (quantite > 0)
);

-- Type de matériel
CREATE TABLE Type_Materiel (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL UNIQUE
);

-- Matériel
CREATE TABLE Materiel (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL,
  id_type_materiel INT NOT NULL,
  capacite DECIMAL(10,2),
  description TEXT,
  FOREIGN KEY (id_type_materiel) REFERENCES Type_Materiel(id),
  CONSTRAINT unique_code_materiel UNIQUE (code)
);

-- Type de cuve
CREATE TABLE Type_Cuve (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL UNIQUE
);

-- Cuves
CREATE TABLE Cuve (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_materiel INT,
  id_type_cuve INT NOT NULL,
  FOREIGN KEY (id_materiel) REFERENCES Materiel(id),
  FOREIGN KEY (id_type_cuve) REFERENCES Type_Cuve(id)
);

-- Type de bouteille
CREATE TABLE Type_Bouteille (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL,
  capacite DECIMAL(10,2) NOT NULL,
  materiau VARCHAR(50),
  CONSTRAINT unique_nom_bouteille UNIQUE (nom),
  CONSTRAINT check_capacite_positive CHECK (capacite > 0)
);

-- Statut de lot
CREATE TABLE Statut_Lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL UNIQUE
);

-- Lots de production
CREATE TABLE Lot_Production (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_recette INT NOT NULL,
  id_cuve INT,
  id_bouteille INT NOT NULL,
  date_debut DATE NOT NULL,
  date_mise_en_bouteille DATE,
  date_commercialisation DATE,
  volume DECIMAL(10,2) NOT NULL,
  nombre_bouteilles INT,
  id_statut_lot INT DEFAULT 1,
  FOREIGN KEY (id_recette) REFERENCES Recette(id),
  FOREIGN KEY (id_cuve) REFERENCES Cuve(id),
  FOREIGN KEY (id_bouteille) REFERENCES Type_Bouteille(id),
  FOREIGN KEY (id_statut_lot) REFERENCES Statut_Lot(id),
  CONSTRAINT check_volume_positive CHECK (volume > 0)
);

-- Détails des lots de production
CREATE TABLE Detail_Lot_Production (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_lot INT NOT NULL,
  id_employe INT NULL,
  date_enregistrement DATETIME NOT NULL,
  parametres_production TEXT,
  remarques TEXT,
  FOREIGN KEY (id_lot) REFERENCES Lot_Production(id),
  FOREIGN KEY (id_employe) REFERENCES Employe(id)
);

-- Détails des mouvements de stock des produits finis
CREATE TABLE Detail_Mouvement_Stock_Produit_Fini (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_employe INT NULL,
  id_lot INT NOT NULL,
  emplacement VARCHAR(100),
  commentaire TEXT,
  FOREIGN KEY (id_employe) REFERENCES Employe(id),
  FOREIGN KEY (id_lot) REFERENCES Lot_Production(id)
);

-- Mouvements de stock des produits finis
CREATE TABLE Mouvement_Stock_Produit_Fini (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_lot INT NOT NULL,
  id_type_mouvement INT NOT NULL,
  id_detail_mouvement INT NOT NULL,
  quantite_bouteilles INT NOT NULL,
  date_mouvement DATETIME NOT NULL,
  stock_actuel INT DEFAULT 0,
  seuil_minimum INT DEFAULT 0,
  date_mise_a_jour DATE,
  FOREIGN KEY (id_lot) REFERENCES Lot_Production(id),
  FOREIGN KEY (id_type_mouvement) REFERENCES Type_Mouvement(id),
  FOREIGN KEY (id_detail_mouvement) REFERENCES Detail_Mouvement_Stock_Produit_Fini(id),
  CONSTRAINT check_quantite_bouteilles_positive CHECK (quantite_bouteilles > 0),
  CONSTRAINT check_stock_bouteilles_non_negatif CHECK (stock_actuel >= 0)
);

-- Contrôle qualité
CREATE TABLE Controle_Qualite (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_lot INT NOT NULL,
  date_controle DATE NOT NULL,
  resultat VARCHAR(100) NOT NULL,
  remarque TEXT,
  id_employe INT,
  FOREIGN KEY (id_lot) REFERENCES Lot_Production(id),
  FOREIGN KEY (id_employe) REFERENCES Employe(id)
);

-- Statut de commande
CREATE TABLE Statut_Commande (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL UNIQUE
);

-- Commandes
CREATE TABLE Commande (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_client INT NOT NULL,
  date_commande DATE NOT NULL,
  date_livraison DATE,
  id_statut_commande INT DEFAULT 1,
  total DECIMAL(10,2) DEFAULT 0,
  FOREIGN KEY (id_client) REFERENCES Client(id),
  FOREIGN KEY (id_statut_commande) REFERENCES Statut_Commande(id),
  CONSTRAINT check_total_non_negatif CHECK (total >= 0)
);

-- Détails de commande
CREATE TABLE Ligne_Commande (
  id_commande INT,
  id_lot INT,
  quantite_bouteilles INT NOT NULL,
  prix_unitaire DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id_commande, id_lot),
  FOREIGN KEY (id_commande) REFERENCES Commande(id),
  FOREIGN KEY (id_lot) REFERENCES Lot_Production(id),
  CONSTRAINT check_quantite_bouteilles_cmd_positive CHECK (quantite_bouteilles > 0),
  CONSTRAINT check_prix_unitaire_non_negatif CHECK (prix_unitaire >= 0)
);

-- Ventes
CREATE TABLE Vente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_commande INT NOT NULL,
  date_vente DATE NOT NULL,
  montant DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (id_commande) REFERENCES Commande(id),
  CONSTRAINT check_montant_positive CHECK (montant >= 0)
);

-- -- Type de budget
-- CREATE TABLE Type_Budget (
--   id INT AUTO_INCREMENT PRIMARY KEY,
--   nom VARCHAR(50) NOT NULL UNIQUE
-- );

-- -- Budgets
-- CREATE TABLE Budget (
--   id INT AUTO_INCREMENT PRIMARY KEY,
--   periode DATE NOT NULL,
--   montant DECIMAL(10,2) NOT NULL,
--   id_type_budget INT DEFAULT 1,
--   FOREIGN KEY (id_type_budget) REFERENCES Type_Budget(id),
--   CONSTRAINT check_montant_budget_non_negatif CHECK (montant >= 0)
-- );

-- --------------------------------------
-- Déclencheurs pour stock_actuel
-- --------------------------------------

-- Déclencheur pour matières premières
DELIMITER //
CREATE TRIGGER after_mouvement_stock_matiere
BEFORE INSERT ON Mouvement_Stock_Matiere_Premiere
FOR EACH ROW
BEGIN
    DECLARE previous_stock DECIMAL(10,2);
    SELECT stock_actuel INTO previous_stock
    FROM Mouvement_Stock_Matiere_Premiere
    WHERE id_matiere = NEW.id_matiere
    ORDER BY date_mouvement DESC
    LIMIT 1;
    IF NEW.id_type_mouvement = 1 THEN -- entrée
        SET NEW.stock_actuel = COALESCE(previous_stock, 0) + NEW.quantite;
    ELSEIF NEW.id_type_mouvement = 2 THEN -- sortie
        IF COALESCE(previous_stock, 0) < NEW.quantite THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Stock insuffisant pour effectuer la sortie';
        END IF;
        SET NEW.stock_actuel = COALESCE(previous_stock, 0) - NEW.quantite;
    END IF;
END //
DELIMITER ;

-- Déclencheur pour produits finis
DELIMITER //
CREATE TRIGGER after_mouvement_stock_produit_fini
BEFORE INSERT ON Mouvement_Stock_Produit_Fini
FOR EACH ROW
BEGIN
    DECLARE previous_stock INT;
    SELECT stock_actuel INTO previous_stock
    FROM Mouvement_Stock_Produit_Fini
    WHERE id_lot = NEW.id_lot
    ORDER BY date_mouvement DESC
    LIMIT 1;
    IF NEW.id_type_mouvement = 1 THEN -- entrée
        SET NEW.stock_actuel = COALESCE(previous_stock, 0) + NEW.quantite_bouteilles;
    ELSEIF NEW.id_type_mouvement = 2 THEN -- sortie
        IF COALESCE(previous_stock, 0) < NEW.quantite_bouteilles THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Stock insuffisant pour effectuer la sortie';
        END IF;
        SET NEW.stock_actuel = COALESCE(previous_stock, 0) - NEW.quantite_bouteilles;
    END IF;
END //
DELIMITER ;

-- --------------------------------------
-- Initialisation des données
-- --------------------------------------
INSERT INTO Type_Mouvement (nom) VALUES ('entrée'), ('sortie');
INSERT INTO Departement (nom, description) VALUES 
  ('Production', 'Département en charge de la fabrication'),
  ('Logistique', 'Département en charge des stocks et livraisons'),
  ('Ventes', 'Département en charge des clients');
INSERT INTO Statut_Employe (nom) VALUES ('actif'), ('inactif'), ('en congé'), ('en formation');
INSERT INTO Employe (nom, poste, id_statut_employe, id_departement) VALUES 
  ('Jean Dupont', 'Brasseur', 1, 1),
  ('Marie Durand', 'Logisticienne', 1, 2);
INSERT INTO Fournisseur (nom, email, telephone, adresse) VALUES 
  ('AgriFournisseur', 'contact@agrifournisseur.com', '123456789', '123 Rue des Fermes');
INSERT INTO Matiere_Premiere (nom, description) VALUES 
  ('Riz', 'Riz blanc pour production'),
  ('Houblon', 'Houblon pour bière');
INSERT INTO Gamme (nom) VALUES ('Bière Artisanale');
INSERT INTO Recette (nom, id_gamme, description, fermentation_jours) VALUES 
  ('IPA Blonde', 1, 'Bière artisanale légère', 14);
INSERT INTO Recette_Matiere (id_recette, id_matiere, quantite) VALUES 
  (1, 1, 1000), -- 1000 kg de riz
  (1, 2, 5);    -- 5 kg de houblon
INSERT INTO Type_Bouteille (nom, capacite, materiau) VALUES 
  ('Bouteille 33cl', 0.33, 'verre'),
  ('Bouteille 75cl', 0.75, 'verre');
INSERT INTO Statut_Lot (nom) VALUES ('en fermentation'), ('prêt'), ('vendu');
INSERT INTO Type_Materiel (nom) VALUES ('cuve'), ('mélangeur'), ('embouteilleuse');
INSERT INTO Materiel (code, id_type_materiel, capacite, description) VALUES 
  ('CUVE001', 1, 2000, 'Cuve de fermentation');
INSERT INTO Type_Cuve (nom) VALUES ('fermentation');
INSERT INTO Cuve (id_materiel, id_type_cuve) VALUES (1, 1);
INSERT INTO Lot_Production (id_recette, id_cuve, id_bouteille, date_debut, volume, nombre_bouteilles, id_statut_lot) VALUES 
  (1, 1, 1, '2025-06-21', 1000, 3000, 1);

-- --------------------------------------
-- Script pour la traçabilité (1000 kg de riz et 3000 bouteilles)
-- --------------------------------------

-- 1. Vérification du stock de riz
SELECT 
    mp.nom AS matiere,
    COALESCE(MAX(msmp.stock_actuel), 0) AS stock_actuel,
    msmp.seuil_minimum,
    CASE 
        WHEN COALESCE(MAX(msmp.stock_actuel), 0) >= 1000 THEN 'Stock suffisant'
        ELSE 'Stock insuffisant'
    END AS statut
FROM Matiere_Premiere mp
LEFT JOIN Mouvement_Stock_Matiere_Premiere msmp ON mp.id = msmp.id_matiere
WHERE mp.nom = 'Riz'
GROUP BY mp.nom, msmp.seuil_minimum;

-- 2. Entrée de 1500 kg de riz (si stock insuffisant)
START TRANSACTION;
INSERT INTO Detail_Mouvement_Stock_Matiere_Premiere (
    id_fournisseur, id_employe, emplacement, lot_fournisseur, 
    date_reception, date_expiration, commentaire
)
VALUES (
    1, 1, 'Entrepôt B', 'LOT456', 
    '2025-06-20', '2026-06-20', 'Réception de riz'
);
INSERT INTO Mouvement_Stock_Matiere_Premiere (
    id_matiere, id_type_mouvement, id_detail_mouvement, quantite, 
    date_mouvement, seuil_minimum, date_mise_a_jour
)
VALUES (
    (SELECT id FROM Matiere_Premiere WHERE nom = 'Riz'), 
    1, LAST_INSERT_ID(), 1500, NOW(), 500, '2025-06-20'
);
COMMIT;

-- 3. Sortie de 1000 kg de riz pour le lot #1
START TRANSACTION;
INSERT INTO Detail_Mouvement_Stock_Matiere_Premiere (
    id_employe, commentaire, id_lot
)
VALUES (
    1, 'Sortie de 1000 kg de riz pour lot #1', 1
);
INSERT INTO Mouvement_Stock_Matiere_Premiere (
    id_matiere, id_type_mouvement, id_detail_mouvement, quantite, 
    date_mouvement, seuil_minimum, date_mise_a_jour
)
VALUES (
    (SELECT id FROM Matiere_Premiere WHERE nom = 'Riz'), 
    2, LAST_INSERT_ID(), 1000, NOW(), 500, '2025-06-21'
);
COMMIT;

-- 4. Ajout d’un détail dans Detail_Lot_Production pour le riz
INSERT INTO Detail_Lot_Production (
    id_lot, id_employe, date_enregistrement, remarques
)
VALUES (
    1, 1, NOW(), 'Utilisation de 1000 kg de riz pour ce lot'
);

-- 5. Vérification du stock de produits finis
SELECT 
    lp.id AS lot_id,
    r.nom AS recette,
    tb.nom AS type_bouteille,
    COALESCE(MAX(mspf.stock_actuel), 0) AS stock_actuel,
    mspf.seuil_minimum,
    CASE 
        WHEN COALESCE(MAX(mspf.stock_actuel), 0) >= 3000 THEN 'Stock suffisant'
        ELSE 'Stock insuffisant'
    END AS statut
FROM Lot_Production lp
JOIN Recette r ON lp.id_recette = r.id
JOIN Type_Bouteille tb ON lp.id_bouteille = tb.id
LEFT JOIN Mouvement_Stock_Produit_Fini mspf ON lp.id = mspf.id_lot
WHERE lp.id = 1
GROUP BY lp.id, r.nom, tb.nom, mspf.seuil_minimum;

-- 6. Entrée de 3000 bouteilles pour le lot #1
START TRANSACTION;
INSERT INTO Detail_Mouvement_Stock_Produit_Fini (
    id_employe, id_lot, emplacement, commentaire
)
VALUES (
    1, 1, 'Entrepôt A', 'Entrée de 3000 bouteilles après mise en bouteille'
);
INSERT INTO Mouvement_Stock_Produit_Fini (
    id_lot, id_type_mouvement, id_detail_mouvement, quantite_bouteilles, 
    date_mouvement, seuil_minimum, date_mise_a_jour
)
VALUES (
    1, 1, LAST_INSERT_ID(), 3000, NOW(), 1000, '2025-06-21'
);
COMMIT;

-- 7. Vérification de la traçabilité des matières premières
SELECT 
    mp.nom AS matiere,
    msmp.quantite,
    tms.nom AS type_mouvement,
    msmp.date_mouvement,
    dmsmp.emplacement,
    dmsmp.lot_fournisseur,
    f.nom AS fournisseur,
    e.nom AS employe,
    dmsmp.commentaire,
    lp.id AS lot_id,
    r.nom AS recette,
    tb.nom AS type_bouteille,
    lp.date_debut AS production_debut,
    lp.nombre_bouteilles,
    sl.nom AS statut_lot,
    msmp.stock_actuel,
    msmp.seuil_minimum,
    CASE 
        WHEN msmp.stock_actuel <= msmp.seuil_minimum * 1.1 THEN 'Alerte : Stock faible'
        ELSE 'OK'
    END AS stock_statut
FROM Mouvement_Stock_Matiere_Premiere msmp
JOIN Matiere_Premiere mp ON msmp.id_matiere = mp.id
JOIN Type_Mouvement tms ON msmp.id_type_mouvement = tms.id
JOIN Detail_Mouvement_Stock_Matiere_Premiere dmsmp ON msmp.id_detail_mouvement = dmsmp.id
LEFT JOIN Fournisseur f ON dmsmp.id_fournisseur = f.id
LEFT JOIN Employe e ON dmsmp.id_employe = e.id
LEFT JOIN Lot_Production lp ON dmsmp.id_lot = lp.id
LEFT JOIN Recette r ON lp.id_recette = r.id
LEFT JOIN Type_Bouteille tb ON lp.id_bouteille = tb.id
LEFT JOIN Statut_Lot sl ON lp.id_statut_lot = sl.id
WHERE mp.nom = 'Riz'
  AND tms.nom = 'sortie'
  AND msmp.quantite = 1000
  AND msmp.date_mouvement >= '2025-06-21'
ORDER BY msmp.date_mouvement DESC
LIMIT 1;

-- 8. Vérification de la traçabilité des produits finis
SELECT 
    lp.id AS lot_id,
    r.nom AS recette,
    tb.nom AS type_bouteille,
    mspf.quantite_bouteilles,
    tms.nom AS type_mouvement,
    mspf.date_mouvement,
    dmspf.emplacement,
    e.nom AS employe,
    dmspf.commentaire,
    mspf.stock_actuel,
    mspf.seuil_minimum,
    CASE 
        WHEN mspf.stock_actuel <= mspf.seuil_minimum * 1.1 THEN 'Alerte : Stock faible'
        ELSE 'OK'
    END AS stock_statut
FROM Mouvement_Stock_Produit_Fini mspf
JOIN Lot_Production lp ON mspf.id_lot = lp.id
JOIN Recette r ON lp.id_recette = r.id
JOIN Type_Bouteille tb ON lp.id_bouteille = tb.id
JOIN Type_Mouvement tms ON mspf.id_type_mouvement = tms.id
JOIN Detail_Mouvement_Stock_Produit_Fini dmspf ON mspf.id_detail_mouvement = dmspf.id
LEFT JOIN Employe e ON dmspf.id_employe = e.id
WHERE lp.id = 1
  AND tms.nom = 'entrée'
  AND mspf.quantite_bouteilles = 3000
  AND mspf.date_mouvement >= '2025-06-21'
ORDER BY mspf.date_mouvement DESC
LIMIT 1;

-- 9. Vérification des détails du lot
SELECT 
    lp.id AS lot_id,
    r.nom AS recette,
    e.nom AS employe,
    dlp.date_enregistrement,
    dlp.parametres_production,
    dlp.remarques
FROM Detail_Lot_Production dlp
JOIN Lot_Production lp ON dlp.id_lot = lp.id
JOIN Recette r ON lp.id_recette = r.id
LEFT JOIN Employe e ON dlp.id_employe = e.id
WHERE lp.id = 1
ORDER BY dlp.date_enregistrement DESC;