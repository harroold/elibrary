-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Sob 16. led 2021, 15:47
-- Verze serveru: 8.0.21
-- Verze PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `elibrary`
--
DROP DATABASE IF EXISTS `elibrary`;
CREATE DATABASE IF NOT EXISTS `elibrary` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci;
USE `elibrary`;

-- --------------------------------------------------------

--
-- Struktura tabulky `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_czech_ci NOT NULL,
  `author` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL,
  `content` text COLLATE utf8mb4_czech_ci,
  `cover_url` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `books_has_categories`
--

DROP TABLE IF EXISTS `books_has_categories`;
CREATE TABLE IF NOT EXISTS `books_has_categories` (
  `books_id` int NOT NULL,
  `categories_id` int NOT NULL,
  PRIMARY KEY (`books_id`,`categories_id`),
  KEY `fk_books_has_categories_categories1_idx` (`categories_id`),
  KEY `fk_books_has_categories_books_idx` (`books_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(45) COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(80) COLLATE utf8mb4_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `username` varchar(80) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `role` varchar(45) COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `user_rated_book`
--

DROP TABLE IF EXISTS `user_rated_book`;
CREATE TABLE IF NOT EXISTS `user_rated_book` (
  `id` int NOT NULL AUTO_INCREMENT,
  `stars` int DEFAULT NULL,
  `review` text COLLATE utf8mb4_czech_ci,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `users_id` int NOT NULL,
  `books_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_rated_book_users1_idx` (`users_id`),
  KEY `fk_user_rated_book_books1_idx` (`books_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `books_has_categories`
--
ALTER TABLE `books_has_categories`
  ADD CONSTRAINT `fk_books_has_categories_books` FOREIGN KEY (`books_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `fk_books_has_categories_categories1` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`);

--
-- Omezení pro tabulku `user_rated_book`
--
ALTER TABLE `user_rated_book`
  ADD CONSTRAINT `fk_user_rated_book_books1` FOREIGN KEY (`books_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `fk_user_rated_book_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
