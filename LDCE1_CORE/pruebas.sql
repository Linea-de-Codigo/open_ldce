-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 13-04-2011 a las 17:54:18
-- Versión del servidor: 5.1.49
-- Versión de PHP: 5.3.3-1ubuntu9.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pruebas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CodGlosaRespuesta`
--

CREATE TABLE IF NOT EXISTS `CodGlosaRespuesta` (
  `idCodGlosaRespuesta` varchar(40) NOT NULL,
  `Descripcion` varchar(250) DEFAULT NULL,
  `Aplica` text,
  `Tipo` varchar(45) DEFAULT NULL,
  `DescripcionGeneral` varchar(45) NOT NULL,
  PRIMARY KEY (`idCodGlosaRespuesta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `CodGlosaRespuesta`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Facturas`
--

CREATE TABLE IF NOT EXISTS `Facturas` (
  `NoFactura` int(11) NOT NULL,
  `IdIPS` int(11) DEFAULT NULL,
  `FechaFactura` varchar(45) DEFAULT NULL,
  `IdentificacionUsuario` int(11) DEFAULT NULL,
  `TipoDocumento` int(11) DEFAULT NULL,
  PRIMARY KEY (`NoFactura`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `Facturas`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Objeciones`
--

CREATE TABLE IF NOT EXISTS `Objeciones` (
  `idObjecion` int(11) NOT NULL,
  `FechaObjecion` date NOT NULL,
  `NoFactura` int(11) NOT NULL,
  `NoRadicadoFosyga` varchar(30) NOT NULL,
  `CodigoGlosa` varchar(70) NOT NULL,
  `DescripcionGlosa` text NOT NULL,
  `TipoObjecion` int(11) NOT NULL,
  `MotivoRespuesta` int(11) NOT NULL,
  PRIMARY KEY (`idObjecion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `Objeciones`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RespuestasObjeciones`
--

CREATE TABLE IF NOT EXISTS `RespuestasObjeciones` (
  `idRespuestaObjecion` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Entidad` varchar(45) DEFAULT NULL,
  `Nit` varchar(45) DEFAULT NULL,
  `NoHabilitacion` varchar(45) DEFAULT NULL,
  `DocumentoNo` varchar(45) DEFAULT NULL,
  `Folios` int(11) DEFAULT NULL,
  `Paquete` int(11) DEFAULT NULL,
  `ServicioEvento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idRespuestaObjecion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `RespuestasObjeciones`
--

