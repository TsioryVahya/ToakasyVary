Select sum(tb.capacite*lp.nombre_bouteilles)as litre_bouteilles , sum(nombre_bouteilles) from lot_productions lp join type_bouteilles tb 
on lp.id_bouteille = tb.id
where lp.id_gamme=1
AND lp.date_mise_en_bouteille >='2025-06-15' AND lp.date_mise_en_bouteille <='2025-06-16';

SELECT 
    DATE(lp.date_mise_en_bouteille) as date,
    SUM(CASE WHEN lp.id_gamme = 1 THEN tb.capacite * lp.nombre_bouteilles ELSE 0 END) as litre_gamme1,
    SUM(CASE WHEN lp.id_gamme = 2 THEN tb.capacite * lp.nombre_bouteilles ELSE 0 END) as litre_gamme2,
    SUM(CASE WHEN lp.id_gamme = 3 THEN tb.capacite * lp.nombre_bouteilles ELSE 0 END) as litre_gamme3,
    SUM(lp.nombre_bouteilles) as nombre_bouteilles
FROM 
    lot_productions lp
JOIN 
    type_bouteilles tb ON lp.id_bouteille = tb.id
WHERE 
    lp.date_mise_en_bouteille BETWEEN '2025-06-14' AND '2025-06-16'
GROUP BY 
    DATE(lp.date_mise_en_bouteille)
ORDER BY 
    date

INSERT INTO gammes (
    nom,
    description,
    fermentation_jours,
    vieillissement_jours,
    created_at,
    updated_at
) VALUES (
    'moyen',
    'rien',
    7,
    90,
    NOW(),
    NOW()
);
INSERT INTO lot_productions (
    id_gamme,
    id_bouteille,
    date_debut,
    date_mise_en_bouteille,
    date_commercialisation,
    nombre_bouteilles,
    created_at,
    updated_at
) VALUES (
    2,                        -- ID de la gamme
    1,                        -- ID de la bouteille
    '2025-06-01',             -- Date de début de fabrication
    '2025-06-15',             -- Date de mise en bouteille (facultatif)
    '2025-07-01',             -- Date de commercialisation (facultatif)
    650,                     -- Nombre de bouteilles (facultatif)
    NOW(),                    -- created_at (timestamp actuel)
    NOW()                     -- updated_at (timestamp actuel)
);
INSERT INTO type_bouteilles (nom, capacite, created_at, updated_at) VALUES
('Bouteille 50cl', 0.50, NOW(), NOW()),
('Bouteille 75cl', 0.75, NOW(), NOW()),
('Magnum 1.5L',    1.50, NOW(), NOW());
