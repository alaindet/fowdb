-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';

SET NAMES utf8mb4;

CREATE TABLE `cards` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sorted_id` int(11) unsigned DEFAULT NULL,
  `back_side` tinyint(1) NOT NULL DEFAULT '0',
  `narp` tinyint(4) NOT NULL,
  `clusters_id` smallint(5) unsigned NOT NULL,
  `sets_id` smallint(5) unsigned NOT NULL,
  `num` smallint(6) NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute_bit` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_bit` bigint(20) unsigned NOT NULL DEFAULT '0',
  `divinity` tinyint(1) DEFAULT NULL,
  `rarity` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `attribute_cost` char(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `free_cost` tinyint(4) DEFAULT NULL,
  `total_cost` tinyint(4) DEFAULT NULL,
  `atk` smallint(6) DEFAULT NULL,
  `def` smallint(6) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `flavor_text` text COLLATE utf8mb4_unicode_ci,
  `artist_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legality_bit` bigint(20) unsigned DEFAULT '0',
  `image_path` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumb_path` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cards2game_clusters` (`clusters_id`),
  KEY `fk_cards2game_sets` (`sets_id`),
  CONSTRAINT `fk_cards2game_clusters` FOREIGN KEY (`clusters_id`) REFERENCES `game_clusters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cards2game_sets` FOREIGN KEY (`sets_id`) REFERENCES `game_sets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `card_attributes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bit` tinyint(1) unsigned NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_pseudo_attribute` tinyint(1) NOT NULL,
  `display` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `card_back_sides` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `card_narps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` tinyint(3) unsigned NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `card_rarities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `card_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bit` tinyint(1) unsigned NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `game_clusters` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `game_formats` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `bit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_multi_cluster` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `game_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_validity` date DEFAULT NULL,
  `version` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comprehensive_rules_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `game_rulings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cards_id` int(11) unsigned NOT NULL,
  `date` date NOT NULL,
  `is_errata` tinyint(1) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_game_rulings2cards` (`cards_id`),
  CONSTRAINT `fk_game_rulings2cards` FOREIGN KEY (`cards_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `game_sets` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `clusters_id` smallint(5) unsigned NOT NULL,
  `code` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_release` date DEFAULT NULL,
  `is_spoiler` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_game_sets2game_clusters` (`clusters_id`),
  CONSTRAINT `fk_game_sets2game_clusters` FOREIGN KEY (`clusters_id`) REFERENCES `game_clusters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `pivot_cluster_format` (
  `formats_id` smallint(5) unsigned NOT NULL,
  `clusters_id` smallint(5) unsigned NOT NULL,
  KEY `formats_id index` (`formats_id`),
  KEY `fk_pivot_cluster_format2game_clusters` (`clusters_id`),
  CONSTRAINT `fk_pivot_cluster_format2game_clusters` FOREIGN KEY (`clusters_id`) REFERENCES `game_clusters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pivot_cluster_format2game_formats` FOREIGN KEY (`formats_id`) REFERENCES `game_formats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `play_restrictions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cards_id` int(11) unsigned NOT NULL COMMENT 'Insert FIRST PRINT IDs here',
  `formats_id` int(11) NOT NULL,
  `deck` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=main,1=side,2=stones,3=runes,4=strangers',
  `copies` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=none,1=1 copy,etc.',
  PRIMARY KEY (`id`),
  KEY `fk_play_restrictions2cards` (`cards_id`),
  CONSTRAINT `fk_play_restrictions2cards` FOREIGN KEY (`cards_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles_id` smallint(5) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_users2user_roles` (`roles_id`),
  CONSTRAINT `fk_users2user_roles` FOREIGN KEY (`roles_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `user_roles` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2019-11-08 08:54:44
