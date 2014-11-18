-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 18 Lis 2014, 22:45
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `parser_jazdynowy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kierunki`
--

CREATE TABLE IF NOT EXISTS `kierunki` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linia_id` int(10) unsigned DEFAULT NULL,
  `nr_kier` int(10) unsigned DEFAULT NULL,
  `kierunek` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `linia_id` (`linia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `linie`
--

CREATE TABLE IF NOT EXISTS `linie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linia` varchar(8) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `data` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `odjazdy`
--

CREATE TABLE IF NOT EXISTS `odjazdy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typ_dnia_id` int(10) unsigned DEFAULT NULL,
  `linia_id` int(10) unsigned DEFAULT NULL,
  `przyst_id` int(10) unsigned DEFAULT NULL,
  `kier_id` int(10) unsigned DEFAULT NULL,
  `kurs_nr` int(10) unsigned NOT NULL,
  `odjazd` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `godz` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `min` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `oznaczenia` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `stan` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `typ_dnia_id` (`typ_dnia_id`),
  KEY `linia_id` (`linia_id`),
  KEY `przyst_id` (`przyst_id`),
  KEY `kier_id` (`kier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oznaczenia_1`
--

CREATE TABLE IF NOT EXISTS `oznaczenia_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linia_id` int(10) unsigned DEFAULT NULL,
  `kier_id` int(10) unsigned DEFAULT NULL,
  `tekst` varchar(90) DEFAULT NULL,
  `oznaczenie` varchar(2) DEFAULT NULL,
  `opis` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oznaczenia_old`
--

CREATE TABLE IF NOT EXISTS `oznaczenia_old` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linia_id` int(10) unsigned DEFAULT NULL,
  `kier_id` int(10) unsigned DEFAULT NULL,
  `oznaczenia` text CHARACTER SET utf8 COLLATE utf8_polish_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przystanki`
--

CREATE TABLE IF NOT EXISTS `przystanki` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nazwa_pelna` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `nazwa1` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `nazwa2` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_urzedowy` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `nz` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `trasy_przejazdu`
--

CREATE TABLE IF NOT EXISTS `trasy_przejazdu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linia_id` int(10) unsigned NOT NULL,
  `kier_id` int(10) unsigned NOT NULL,
  `przyst_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typy_dni`
--

CREATE TABLE IF NOT EXISTS `typy_dni` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nazwa` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
