-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 23. Sep 2013 um 09:41
-- Server Version: 5.5.29
-- PHP-Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `evaluation`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `answertoquestion`
--

CREATE TABLE `answertoquestion` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `answertext` varchar(1024) CHARACTER SET utf8 DEFAULT NULL,
  `questionId` int(12) NOT NULL,
  `questionnaireId` int(12) NOT NULL,
  `answernumber` int(2) DEFAULT NULL,
  `answerhash` varchar(52) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `answertext` (`answertext`(255)),
  KEY `questionId` (`questionId`),
  KEY `questionnaireId` (`questionnaireId`),
  KEY `answerhash` (`answerhash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `participant`
--

CREATE TABLE `participant` (
  `hash` varchar(48) COLLATE utf8_bin NOT NULL,
  `questionnaireId` int(12) NOT NULL,
  PRIMARY KEY (`hash`),
  KEY `questionnaireId` (`questionnaireId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `participant`
--

INSERT INTO `participant` (`hash`, `questionnaireId`) VALUES
('a17b969cee0970f33cde965b1e1971d1d33c04d2', 34);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `question`
--

CREATE TABLE `question` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `text` varchar(1024) NOT NULL,
  `category` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'VL',
  `rangeMin` float NOT NULL,
  `rangeMax` float NOT NULL,
  `rangeStep` float NOT NULL,
  `type` varchar(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'radio',
  `prio` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

--
-- Daten für Tabelle `question`
--

INSERT INTO `question` (`id`, `text`, `category`, `rangeMin`, `rangeMax`, `rangeStep`, `type`, `prio`) VALUES
(1, 'Studiengang:', 'VL', 0, 0, 0, 'text', 100),
(2, 'Semester:', 'VL', 1, 20, 1, 'musel', 99),
(3, 'Ich habe X% der Vorlesungen besucht', 'VL', 0, 100, 5, 'musel', 7),
(4, 'Studiengang:', 'PR', 0, 0, 0, 'text', 100),
(5, 'Semester:', 'PR', 1, 20, 1, 'musel', 99),
(59, 'Der inhaltliche Aufbau ist logisch nachvollziehbar', 'VL', 0, 0, 0, 'radio', 69),
(60, 'Ein Bezug zwischen Theorie und Praxis/Anwendung wird hergestellt', 'VL', 0, 0, 0, 'radio', 68),
(61, 'Die / der Lehrende spricht verständlich(Lautstärke, Deutlichkeit)', 'VL', 0, 0, 0, 'radio', 78),
(62, 'Der / die Lehrende kann Kompliziertes versändlich machen', 'VL', 0, 0, 0, 'radio', 77),
(63, 'Die Veranstaltung hat mein Interesse an der Thematik geweckt', 'VL', 0, 0, 0, 'radio', 9),
(64, 'Zum Mitdenken und Durchdenken des Stoffes/Themas wird angeregt', 'VL', 0, 0, 0, 'radio', 73),
(65, 'Die / der Lehrende wirkt vorbereitet', 'VL', 0, 0, 0, 'radio', 79),
(66, 'Die Materialien zur Vorlesung sind hilfreich (Skripte, Folien, etc.)', 'VL', 0, 0, 0, 'radio', 59),
(67, 'Medien (Tafel, OHP, Beamer) werden sinnvoll eingesetzt', 'VL', 0, 0, 0, 'radio', 88),
(68, 'Die / der Lehrende ist kooperativ und aufgeschlossen', 'VL', 0, 0, 0, 'radio', 74),
(69, 'Das Tempo des Kurses ist angemessen', 'VL', 0, 0, 0, 'radio', 67),
(70, 'Der Umfang ist angemessen', 'VL', 0, 0, 0, 'radio', 66),
(71, 'Der Schwierigkeitsgrad ist angemessen', 'VL', 0, 0, 0, 'radio', 65),
(72, 'Außerhalb der Veranstaltung findet eine gute Betreuung statt', 'VL', 0, 0, 0, 'radio', 57),
(73, 'Die / der Lehrende fördert Fragen und aktive Mitarbeit', 'VL', 0, 0, 0, 'radio', 76),
(74, 'Auf Beiträge der Teilnehmer/-innen gibt es ein hilfreiches Feedback', 'VL', 0, 0, 0, 'radio', 75),
(75, 'Die Prüfungsanforderungen sind transparent', 'VL', 0, 0, 0, 'radio', 64),
(76, 'Ich lerne viel in der Veranstaltung', 'VL', 0, 0, 0, 'radio', 8),
(77, 'Die aktive Beteiligung der Studierenden bewerte ich als sehr gut', 'VL', 0, 0, 0, 'radio', 72),
(78, 'Die Ausstattung der Hörsäle ist sehr gut', 'VL', 0, 0, 0, 'radio', 89),
(79, 'Der Aufwand in Stunden/Woche für Vor-/Nachbereitung beträgt', 'VL', 0, 50, 1, 'musel', 56),
(81, 'Ich gebe der Vorlesung die Gesamtnote(Noten von 1 bis 5)', 'VL', 1, 5, 0.5, 'musel', 3),
(82, 'Ich gebe diese Note weil', 'VL', 0, 0, 0, 'tarea', 2),
(83, 'Mir hat besonders gefallen', 'VL', 0, 0, 0, 'tarea', 5),
(84, 'Mir hat nicht gefallen', 'VL', 0, 0, 0, 'tarea', 4),
(85, 'Die Laborausstattung ist sehr gut', 'PR', 0, 0, 0, 'radio', 0),
(86, 'Die Unterlagen zur Vorbereitung sind hilfreich und verständlich', 'PR', 0, 0, 0, 'radio', 0),
(87, 'Die Anforderungen sind transparent', 'PR', 0, 0, 0, 'radio', 0),
(88, 'Der Bezug zur Vorlesung ist gegeben', 'PR', 0, 0, 0, 'radio', 0),
(89, 'Die Betreuenden gehen aufgeschlossen auf meine Fragen ein', 'PR', 0, 0, 0, 'radio', 0),
(90, 'Die Verfügbarkeit der Betreuenden ist gegeben', 'PR', 0, 0, 0, 'radio', 0),
(91, 'Der Umfang der Versuche/Übungen ist angemessen', 'PR', 0, 0, 0, 'radio', 0),
(93, 'Das Feedback ist hilfreich', 'PR', 0, 0, 0, 'radio', 0),
(94, 'Der Aufwand für Vor- und Nachbereitung in Stunden beträgt insgesamt', 'PR', 0, 50, 1, 'musel', 0),
(95, 'Ich gebe dem Praktikum die Gesamtnote', 'PR', 1, 5, 0.5, 'musel', 0),
(96, 'Ich gebe diese Note weil', 'PR', 0, 0, 0, 'tarea', 0),
(97, 'Mir hat besonders gefallen', 'PR', 0, 0, 0, 'tarea', 0),
(98, 'Mir hat nicht gefallen', 'PR', 0, 0, 0, 'tarea', 0),
(99, 'Der / die Lehrende motiviert die Teilehmenden zum Selbststudium', 'VL', 0, 0, 0, 'radio', 58);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `questionnaire`
--

CREATE TABLE `questionnaire` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `profId` int(12) NOT NULL,
  `expirationDate` date NOT NULL,
  `semester` varchar(20) NOT NULL,
  `courseName` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `category` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'VL',
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `profId` (`profId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Daten für Tabelle `questionnaire`
--

INSERT INTO `questionnaire` (`id`, `profId`, `expirationDate`, `semester`, `courseName`, `category`) VALUES
(34, 7, '2013-09-30', 'WiSe 2013/2014', 'TestModul', 'VL');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `submitters`
--

CREATE TABLE `submitters` (
  `answerhash` varchar(52) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `questionnaireId` int(12) NOT NULL,
  PRIMARY KEY (`answerhash`),
  KEY `questionnaireId` (`questionnaireId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `username` varchar(12) COLLATE utf8_bin NOT NULL,
  `password` varchar(256) COLLATE utf8_bin NOT NULL,
  `role` varchar(12) COLLATE utf8_bin NOT NULL,
  `fullName` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fullName` (`fullName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`, `fullName`) VALUES
(7, 'testprof', 'test', 'admin', 'Prof. Dr.  Max Mustertest'),
(8, 'secretary', 'secretary', 'user', 'Studierendensekretariat');

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `answertoquestion`
--
ALTER TABLE `answertoquestion`
  ADD CONSTRAINT `answertoquestion_ibfk_1` FOREIGN KEY (`questionId`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `answertoquestion_ibfk_2` FOREIGN KEY (`questionnaireId`) REFERENCES `questionnaire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `participant_ibfk_3` FOREIGN KEY (`questionnaireId`) REFERENCES `questionnaire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD CONSTRAINT `questionnaire_ibfk_1` FOREIGN KEY (`profId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `submitters`
--
ALTER TABLE `submitters`
  ADD CONSTRAINT `submitters_ibfk_1` FOREIGN KEY (`questionnaireId`) REFERENCES `questionnaire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submitters_ibfk_2` FOREIGN KEY (`answerhash`) REFERENCES `answertoquestion` (`answerhash`) ON DELETE CASCADE ON UPDATE CASCADE;
