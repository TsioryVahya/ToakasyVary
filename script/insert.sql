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