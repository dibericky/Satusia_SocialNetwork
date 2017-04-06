-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generato il: Ott 09, 2016 alle 17:32
-- Versione del server: 5.5.52-cll-lve
-- Versione PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xbjuvfxh_satusiadb`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `chamber`
--

CREATE TABLE IF NOT EXISTS `chamber` (
  `idproj` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `testo` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `chat_proj`
--

CREATE TABLE IF NOT EXISTS `chat_proj` (
  `idproj` int(11) NOT NULL,
  `id_mess` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Struttura della tabella `chat_user`
--

CREATE TABLE IF NOT EXISTS `chat_user` (
  `user_1` int(11) NOT NULL,
  `user_2` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_message` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `commenti_p`
--

CREATE TABLE IF NOT EXISTS `commenti_p` (
  `id_com` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_proj` int(11) NOT NULL,
  `text` text NOT NULL,
  `date_com` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_com`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


-- --------------------------------------------------------

--
-- Struttura della tabella `fb_access`
--

CREATE TABLE IF NOT EXISTS `fb_access` (
  `id_user` int(11) NOT NULL,
  `fb_id` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Struttura della tabella `follow`
--

CREATE TABLE IF NOT EXISTS `follow` (
  `follower` int(255) NOT NULL,
  `followed` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Struttura della tabella `follow_p`
--

CREATE TABLE IF NOT EXISTS `follow_p` (
  `user` int(100) NOT NULL,
  `proj` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `interessi`
--

CREATE TABLE IF NOT EXISTS `interessi` (
  `id_user` int(11) NOT NULL,
  `interessi` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `likestab`
--

CREATE TABLE IF NOT EXISTS `likestab` (
  `idutente` int(11) NOT NULL,
  `idproj` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `message_proj`
--

CREATE TABLE IF NOT EXISTS `message_proj` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `id_proj` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` varchar(10000) NOT NULL,
  `date_message` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `message_user`
--

CREATE TABLE IF NOT EXISTS `message_user` (
  `id_mess` int(11) NOT NULL AUTO_INCREMENT,
  `id_mit` int(11) NOT NULL,
  `id_ric` int(11) NOT NULL,
  `text` text NOT NULL,
  `date_message` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mess`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=86 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `miss_password`
--

CREATE TABLE IF NOT EXISTS `miss_password` (
  `email` text NOT NULL,
  `key_msspswd` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `news_feed`
--

CREATE TABLE IF NOT EXISTS `news_feed` (
  `id_action` int(11) NOT NULL AUTO_INCREMENT,
  `action` text NOT NULL COMMENT 'create, follow_u, follow_p, post, complete, comment_p, share_p, want_help',
  `id_user_actor` int(11) NOT NULL,
  `id_proj` int(11) NOT NULL,
  `id_user_target` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_com` int(11) NOT NULL,
  `id_wh` int(11) NOT NULL,
  `date_action` datetime NOT NULL,
  PRIMARY KEY (`id_action`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=162 ;

--
-- Struttura della tabella `post_p`
--

CREATE TABLE IF NOT EXISTS `post_p` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `id_p` int(10) NOT NULL,
  `id_user` int(100) NOT NULL,
  `title_post` text NOT NULL,
  `message` text NOT NULL,
  `date_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `who_is` varchar(20) NOT NULL,
  PRIMARY KEY (`id_post`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Struttura della tabella `privacy`
--

CREATE TABLE IF NOT EXISTS `privacy` (
  `id` int(100) NOT NULL,
  `email` int(1) NOT NULL DEFAULT '0',
  `tel` int(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `progetti`
--

CREATE TABLE IF NOT EXISTS `progetti` (
  `idproj` int(255) NOT NULL AUTO_INCREMENT,
  `iduser` int(255) NOT NULL DEFAULT '0',
  `title` text NOT NULL,
  `descr` mediumtext NOT NULL,
  `categoria` text NOT NULL,
  `img` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_create` datetime NOT NULL,
  `finito` int(1) NOT NULL DEFAULT '0' COMMENT '0 = in corso,  1 = finito',
  `tag` longtext NOT NULL,
  `open` int(1) NOT NULL DEFAULT '0',
  `likes` int(100) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idproj`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `categoria` (`categoria`),
  FULLTEXT KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `remember`
--

CREATE TABLE IF NOT EXISTS `remember` (
  `id` int(11) NOT NULL,
  `string_rem` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `social_user`
--

CREATE TABLE IF NOT EXISTS `social_user` (
  `id_user` int(100) NOT NULL,
  `facebook` varchar(50) NOT NULL,
  `twitter` varchar(50) NOT NULL,
  `linkedin` varchar(50) NOT NULL,
  `gplus` varchar(100) NOT NULL,
  `personal` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Struttura della tabella `temp_utenti`
--

CREATE TABLE IF NOT EXISTS `temp_utenti` (
  `key_temp` text NOT NULL,
  `Nome` varchar(30) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `Cognome` varchar(30) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `ip_reg` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Struttura della tabella `testUtenti`
--

CREATE TABLE IF NOT EXISTS `testUtenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(20) NOT NULL,
  `Cognome` varchar(20) NOT NULL,
  `Data_Nascita` date NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;


-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE IF NOT EXISTS `utenti` (
  `id` bigint(100) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(30) NOT NULL,
  `Cognome` varchar(30) NOT NULL,
  `Data_Nascita` date NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `gotPsw` int(5) NOT NULL DEFAULT '1',
  `ip_reg` text NOT NULL,
  `data_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_last_login` text NOT NULL,
  `immagine_profilo` char(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'icon/user_default.png',
  `citta` text NOT NULL,
  `sesso` text NOT NULL,
  `professione` varchar(30) NOT NULL,
  `scuola_superiore` text NOT NULL,
  `universita` text NOT NULL,
  `biografia` mediumtext NOT NULL,
  `lingue` text NOT NULL,
  `telefono` text NOT NULL,
  `competenze` longtext NOT NULL,
  `frase_personale` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `view_notif`
--

CREATE TABLE IF NOT EXISTS `view_notif` (
  `id_user` int(11) NOT NULL,
  `date_view_general` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_view_message_user` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_view_message_proj` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_view_request` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `want_help`
--

CREATE TABLE IF NOT EXISTS `want_help` (
  `id_wh` int(120) NOT NULL AUTO_INCREMENT,
  `id_mit` int(100) NOT NULL,
  `id_ric` int(100) NOT NULL,
  `id_proj` int(100) NOT NULL,
  `view` int(11) NOT NULL COMMENT '0=inviata -1= rifiutato  1=accettato  2 = annullato  3 = abbandonato',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_wh`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
