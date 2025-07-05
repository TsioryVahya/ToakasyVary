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



CREATE VIEW vue_details_commandes AS
SELECT
    c.id as idCommande,
    c.date_commande,
    c.date_livraison,
    c.id_client,
    
    cl.nom,

    lc.id_lot,
    g.nom AS nom_gamme,
    tb.nom AS nom_bouteille,
    tb.capacite AS capacite_bouteille,
    g.id as idGamme,
    lc.quantite_bouteilles,

    p.montant AS montant_paye,
    p.date_paiement,
    stc.id as idhistorique,
    stc.id_statut_commande as idstatus,

    pr.prix_unitaire as prixUnitaire, 
    (lc.quantite_bouteilles * pr.prix_unitaire) AS montant_ligne
FROM Commandes c

JOIN Ligne_Commandes lc ON c.id = lc.id_commande
JOIN Lot_Productions lp ON lc.id_lot = lp.id
JOIN Gammes g ON lp.id_gamme = g.id
JOIN Type_Bouteilles tb ON lp.id_bouteille = tb.id
Join Clients cl on c.id_client = cl.id
Join historique_commandes stc on c.id =stc.id_commande
Join prix pr on pr.id = lc.id_prix
JOIN paiement_commandes p ON c.id = p.id_commande;
