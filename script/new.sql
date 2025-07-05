CREATE TABLE `type_mouvements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_mouvements_nom_unique` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `type_bouteilles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacite` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_bouteilles_nom_unique` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `gammes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fermentation_jours` int(11) NOT NULL,
  `vieillissement_jours` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gammes_nom_unique` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `statut_lots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `statut_lots_nom_unique` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lot_productions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_gamme` bigint(20) unsigned NOT NULL,
  `id_bouteille` bigint(20) unsigned NOT NULL,
  `date_debut` date NOT NULL,
  `date_mise_en_bouteille` date DEFAULT NULL,
  `date_commercialisation` date DEFAULT NULL,
  `nombre_bouteilles` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lot_productions_id_gamme_foreign` (`id_gamme`),
  KEY `lot_productions_id_bouteille_foreign` (`id_bouteille`),
  CONSTRAINT `lot_productions_id_bouteille_foreign` FOREIGN KEY (`id_bouteille`) REFERENCES `type_bouteilles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lot_productions_id_gamme_foreign` FOREIGN KEY (`id_gamme`) REFERENCES `gammes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `detail_lot_productions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_lot` bigint(20) unsigned NOT NULL,
  `id_employe` bigint(20) unsigned DEFAULT NULL,
  `date_enregistrement` datetime NOT NULL,
  `parametres_production` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarques` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_lot_productions_id_lot_foreign` (`id_lot`),
  KEY `detail_lot_productions_id_employe_foreign` (`id_employe`),
  CONSTRAINT `detail_lot_productions_id_employe_foreign` FOREIGN KEY (`id_employe`) REFERENCES `employes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `detail_lot_productions_id_lot_foreign` FOREIGN KEY (`id_lot`) REFERENCES `lot_productions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `detail_mouvement_produits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_employe` bigint(20) unsigned DEFAULT NULL,
  `id_lot` bigint(20) unsigned NOT NULL,
  `emplacement` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commentaire` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_mouvement_produits_id_employe_foreign` (`id_employe`),
  KEY `detail_mouvement_produits_id_lot_foreign` (`id_lot`),
  CONSTRAINT `detail_mouvement_produits_id_employe_foreign` FOREIGN KEY (`id_employe`) REFERENCES `employes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `detail_mouvement_produits_id_lot_foreign` FOREIGN KEY (`id_lot`) REFERENCES `lot_productions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `mouvement_produits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_lot` bigint(20) unsigned NOT NULL,
  `id_detail_mouvement` bigint(20) unsigned NOT NULL,
  `quantite_bouteilles` int(11) NOT NULL,
  `date_mouvement` datetime NOT NULL,
  `stock_actuel` int(11) NOT NULL DEFAULT 0,
  `seuil_minimum` int(11) NOT NULL DEFAULT 0,
  `date_mise_a_jour` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mouvement_produits_id_lot_foreign` (`id_lot`),
  KEY `mouvement_produits_id_detail_mouvement_foreign` (`id_detail_mouvement`),
  CONSTRAINT `mouvement_produits_id_detail_mouvement_foreign` FOREIGN KEY (`id_detail_mouvement`) REFERENCES `detail_mouvement_produits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mouvement_produits_id_lot_foreign` FOREIGN KEY (`id_lot`) REFERENCES `lot_productions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
