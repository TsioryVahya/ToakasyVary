SELECT
	Gamme.nom as nom,
    Commande.total as montant,
    Ligne_Commande.quantite_bouteilles as quantite,
    Commande.date_commande as vente,
    Commande.date_livraison as livraison
FROM Vente
JOIN Commande ON Vente.id_commande = Commande.id
JOIN Ligne_Commande ON Ligne_Commande.id_commande = Commande.id
JOIN Lot_Production ON Ligne_Commande.id_lot = Lot_Production.id
JOIN Gamme ON Lot_Production.id_gamme = Gamme.id

CREATE VIEW stats AS
SELECT
	Gamme.nom as nom,
    Commande.total as montant,
    Ligne_Commande.quantite_bouteilles as quantite,
    Commande.date_commande as vente,
    Commande.date_livraison as livraison
FROM Vente
JOIN Commande ON Vente.id_commande = Commande.id
JOIN Ligne_Commande ON Ligne_Commande.id_commande = Commande.id
JOIN Lot_Production ON Ligne_Commande.id_lot = Lot_Production.id
JOIN Gamme ON Lot_Production.id_gamme = Gamme.id


CREATE VIEW stats_completes AS
SELECT
    gammes.nom AS gamme_nom,
    commandes.total AS montant_facture,
    ligne_commandes.quantite_bouteilles AS quantite_vendue,
    commandes.date_commande AS date_vente,
    commandes.date_livraison AS date_livraison,
    SUM(gamme_matieres.quantite * COALESCE(prix_materiels.prix, 0)) AS cout_matiere_unitaire,
    SUM(gamme_matieres.quantite * COALESCE(prix_materiels.prix, 0)) * ligne_commandes.quantite_bouteilles AS cout_matiere_total,
    (ligne_commandes.quantite_bouteilles * ligne_commandes.prix_unitaire) -
    (SUM(gamme_matieres.quantite * COALESCE(prix_materiels.prix, 0)) * ligne_commandes.quantite_bouteilles) AS marge_brute
FROM
    commandes
JOIN
    ligne_commandes ON commandes.id = ligne_commandes.id_commande
JOIN
    lot_productions ON ligne_commandes.id_lot = lot_productions.id
JOIN
    gammes ON lot_productions.id_gamme = gammes.id
JOIN
    gamme_matieres ON gammes.id = gamme_matieres.id_gamme
LEFT JOIN
    prix_materiels ON gamme_matieres.id_matiere = prix_materiels.id_matiere_premiere
GROUP BY
    commandes.id,
    gammes.nom,
    commandes.total,
    ligne_commandes.quantite_bouteilles,
    ligne_commandes.prix_unitaire,
    commandes.date_commande,
    commandes.date_livraison;


CREATE VIEW vieillissement AS
SELECT 
    lp.id AS lot_id,
    lp.id_gamme,
    lp.id_bouteille,
    lp.date_debut,
    lp.date_mise_en_bouteille,
    lp.date_commercialisation,
    lp.nombre_bouteilles,
    g.nom AS nom_gamme,
    g.description AS description_gamme,
    g.fermentation_jours,
    g.vieillissement_jours
FROM Lot_Production lp
JOIN Gamme g ON lp.id_gamme = g.id;