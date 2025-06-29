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
