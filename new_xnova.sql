-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 19-06-2012 a las 18:23:05
-- Versión del servidor: 5.5.24
-- Versión de PHP: 5.3.10-1ubuntu3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `new_xnova`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admins`
--
-- Creación: 19-06-2012 a las 16:22:32
-- Última actualización: 19-06-2012 a las 16:22:32
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `user_id` int(10) unsigned NOT NULL,
  `auth_level` tinyint(1) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--
-- Creación: 19-06-2012 a las 15:16:30
-- Última actualización: 19-06-2012 a las 15:16:30
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `key` varchar(15) NOT NULL,
  `value` varchar(15) NOT NULL,
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--
-- Creación: 19-06-2012 a las 15:14:43
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('8a185be1da43f713fe75982560821285', '127.0.0.1', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.19 (KHTML, like Gecko) Ubuntu/12.04 Chromium/18.0.1025.151 Chrome/18.0.102', 1340120705, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
