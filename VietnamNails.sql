-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-12-2024 a las 21:38:13
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vietnamnails`
--
CREATE DATABASE IF NOT EXISTS `vietnamnails` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `vietnamnails`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `companyhours`
--

DROP TABLE IF EXISTS `companyhours`;
CREATE TABLE IF NOT EXISTS `companyhours` (
  `id_companyHour` int(11) NOT NULL AUTO_INCREMENT,
  `dayOfWeek` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `isTemporary` tinyint(1) DEFAULT 0,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`id_companyHour`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `companyhours`
--

INSERT INTO `companyhours` (`id_companyHour`, `dayOfWeek`, `startTime`, `endTime`, `isTemporary`, `startDate`, `endDate`) VALUES
(1, 'Monday', '09:30:00', '20:30:00', 0, NULL, NULL),
(2, 'Tuesday', '09:30:00', '20:30:00', 0, NULL, NULL),
(3, 'Wednesday', '09:30:00', '20:30:00', 0, NULL, NULL),
(4, 'Thursday', '09:30:00', '20:30:00', 0, NULL, NULL),
(5, 'Friday', '09:30:00', '20:30:00', 0, NULL, NULL),
(6, 'Saturday', '09:30:00', '15:00:00', 1, '2024-12-14', '2025-01-04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customersdetails`
--

DROP TABLE IF EXISTS `customersdetails`;
CREATE TABLE IF NOT EXISTS `customersdetails` (
  `id_customer` int(11) NOT NULL AUTO_INCREMENT,
  `fullName` varchar(100) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  PRIMARY KEY (`id_customer`),
  UNIQUE KEY `phoneNumber` (`phoneNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id_employee` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `dataCreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isActive` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_employee`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `employees`
--

INSERT INTO `employees` (`id_employee`, `firstName`, `lastName`, `phone`, `dataCreated`, `isActive`, `created_at`) VALUES
(1, 'Hiep', 'Cao', '123456789', '2024-12-10 22:35:29', 1, '2024-12-11 15:24:48'),
(2, 'Georgina', '', '234567890', '2024-12-11 13:29:43', 1, '2024-12-11 15:24:48'),
(3, 'Yulia', '', '345678901', '2024-11-29 12:02:30', 1, '2024-12-11 15:24:48'),
(4, 'Anonimo', '', '012345678', '2024-11-29 12:05:23', 1, '2024-12-11 15:24:48'),
(5, 'Anonimo', '', '012345678', '2024-11-29 12:05:23', 1, '2024-12-11 15:24:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `offers`
--

DROP TABLE IF EXISTS `offers`;
CREATE TABLE IF NOT EXISTS `offers` (
  `id_offer` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `offer_type` enum('weekly','monthly','blackfriday','special') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `final_price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_offer`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `offers`
--

INSERT INTO `offers` (`id_offer`, `title`, `description`, `offer_type`, `start_date`, `end_date`, `final_price`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'Oferta', 'Manos y pies semipermanente', '', '2024-12-11', '2025-04-17', 25.00, 1, '2024-12-11 14:43:05', '2024-12-13 18:50:51'),
(6, 'Pedicura sin pintura y Manos Semipermanente', 'Pedicura completa, dejando tus pies suaves y cuidados. Para tus manos, un esmaltado semipermanente que garantiza un acabado impecable y duradero', '', '2024-12-13', '2025-04-17', 30.00, 1, '2024-12-13 18:33:25', '2024-12-13 18:51:21'),
(8, '¡Jubiladas, Uñas de Gel con un Toque de Elegancia!', 'Dale a tus manos el cuidado que merecen con nuestras uñas de gel. Un toque elegante y duradero, diseñado especialmente para ti.\r\n\r\n', '', '2024-12-13', '2025-04-17', 20.00, 1, '2024-12-13 18:44:26', '2024-12-13 18:53:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `offer_services`
--

DROP TABLE IF EXISTS `offer_services`;
CREATE TABLE IF NOT EXISTS `offer_services` (
  `id_offer_service` int(11) NOT NULL AUTO_INCREMENT,
  `id_offer` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  PRIMARY KEY (`id_offer_service`),
  KEY `id_offer` (`id_offer`),
  KEY `id_service` (`id_service`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `offer_services`
--

INSERT INTO `offer_services` (`id_offer_service`, `id_offer`, `id_service`) VALUES
(7, 4, 3),
(8, 4, 5),
(11, 6, 3),
(12, 6, 5),
(15, 8, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id_reservation` int(11) NOT NULL AUTO_INCREMENT,
  `id_customer` int(11) NOT NULL,
  `reservationDate` datetime NOT NULL,
  `id_employee` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_reservation`),
  KEY `id_customer` (`id_customer`),
  KEY `id_employee` (`id_employee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservationservices`
--

DROP TABLE IF EXISTS `reservationservices`;
CREATE TABLE IF NOT EXISTS `reservationservices` (
  `id_reservationService` int(11) NOT NULL AUTO_INCREMENT,
  `id_reservation` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  PRIMARY KEY (`id_reservationService`),
  KEY `id_reservation` (`id_reservation`),
  KEY `id_service` (`id_service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serviceimages`
--

DROP TABLE IF EXISTS `serviceimages`;
CREATE TABLE IF NOT EXISTS `serviceimages` (
  `id_serviceImage` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `imageUrl` varchar(2083) NOT NULL COMMENT 'URL asociada a la imagen',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id_service` int(11) NOT NULL AUTO_INCREMENT,
  `nameService` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL COMMENT 'Duración en minutos',
  `price` decimal(10,2) NOT NULL,
  `availableFrom` date DEFAULT NULL COMMENT 'Fecha de inicio de la disponibilidad del servicio',
  `availableUntil` date DEFAULT NULL COMMENT 'Fecha de finalización de la disponibilidad del servicio',
  `isActive` tinyint(1) DEFAULT 1 COMMENT 'Indica si el servicio está activo actualmente (1: activo, 0: inactivo)',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `update_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_service`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id_service`, `nameService`, `description`, `duration`, `price`, `availableFrom`, `availableUntil`, `isActive`, `created_at`, `update_at`) VALUES
(1, 'Uñas de gel', 'Prótesis creadas especialmente para las uñas a partir de un gel acrílico que se moldea en la uña natural', 75, 15.00, NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(2, 'Semi capa', 'Tiene que ser un tratamiento de uñas que no es una capa completa', 75, 15.00, NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(3, 'Semi permanente', 'Un tipo de manicura que se hace cada 2-3 semanas', 75, 15.00, NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(4, 'Manicura', 'Tratamiento cosmético en el que se da la forma deseada a las uñas de las manos, y se maquillan con un esmalte', 75, 15.00, NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(5, 'Pedicura', 'Tratamiento cosmético superficial de las uñas de los pies', 75, 15.00, NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(6, 'Masajes', 'Manipulación de las capas superficiales y profundas de los músculos del cuerpo', 75, 15.00, NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `special_days`
--

DROP TABLE IF EXISTS `special_days`;
CREATE TABLE IF NOT EXISTS `special_days` (
  `id_special_day` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL,
  `is_open` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_special_day`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `special_days`
--

INSERT INTO `special_days` (`id_special_day`, `date`, `description`, `opening_time`, `closing_time`, `is_open`, `created_at`) VALUES
(1, '2024-12-24', 'Nochebuena', '09:00:00', '14:00:00', 1, '2024-12-10 21:13:15'),
(2, '2024-12-25', 'Navidad', '00:00:00', '00:00:00', 0, '2024-12-10 21:13:15'),
(3, '2024-12-31', 'Nochevieja', '09:00:00', '15:00:00', 1, '2024-12-10 21:13:15'),
(4, '2024-01-01', 'Año Nuevo', '00:00:00', '00:00:00', 0, '2024-12-10 21:13:15'),
(5, '2024-12-14', 'Sábado Fiestas Navideñas', '09:00:00', '15:00:00', 1, '2024-12-10 21:19:35'),
(6, '2024-12-21', 'Sábado Fiestas Navideñas', '09:00:00', '15:00:00', 1, '2024-12-10 21:19:35'),
(7, '2024-12-28', 'Sábado Fiestas Navideñas', '09:00:00', '15:00:00', 1, '2024-12-10 21:19:35'),
(8, '2025-01-04', 'Sábado Fiestas Navideñas', '09:00:00', '15:00:00', 1, '2024-12-10 21:19:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(10) NOT NULL,
  `password` varchar(10) NOT NULL,
  `isActive` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `userName`, `password`, `isActive`, `created_at`, `updated_at`) VALUES
(1, 'root', '', 1, '2024-11-29 14:59:57', '2024-11-29 14:59:57'),
(2, 'Cao', '1234', 1, '2024-11-29 14:59:57', '2024-11-29 14:59:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `workschedules`
--

DROP TABLE IF EXISTS `workschedules`;
CREATE TABLE IF NOT EXISTS `workschedules` (
  `id_workSchedule` int(11) NOT NULL AUTO_INCREMENT,
  `id_employee` int(11) NOT NULL,
  `dayOfWeek` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `blockType` enum('Morning','Afternoon','Full Day') NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `isActive` tinyint(1) DEFAULT 1 COMMENT 'Indica si el servicio está activo actualmente (1: activo, 0: inactivo)',
  PRIMARY KEY (`id_workSchedule`),
  KEY `id_employee` (`id_employee`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `workschedules`
--

INSERT INTO `workschedules` (`id_workSchedule`, `id_employee`, `dayOfWeek`, `blockType`, `startTime`, `endTime`, `isActive`) VALUES
(1, 1, 'Monday', 'Full Day', '09:30:00', '20:30:00', 1),
(2, 1, 'Tuesday', 'Full Day', '09:30:00', '20:30:00', 1),
(3, 1, 'Wednesday', 'Full Day', '09:30:00', '20:30:00', 1),
(4, 1, 'Thursday', 'Full Day', '09:30:00', '20:30:00', 1),
(5, 1, 'Friday', 'Full Day', '09:30:00', '20:30:00', 1),
(6, 2, 'Monday', 'Morning', '10:00:00', '13:00:00', 1),
(7, 2, 'Monday', 'Afternoon', '15:30:00', '20:15:00', 1),
(8, 2, 'Tuesday', 'Morning', '10:00:00', '13:00:00', 1),
(9, 2, 'Tuesday', 'Afternoon', '15:30:00', '20:15:00', 1),
(10, 2, 'Wednesday', 'Morning', '10:00:00', '13:00:00', 1),
(11, 2, 'Wednesday', 'Afternoon', '15:30:00', '20:15:00', 1),
(12, 2, 'Thursday', 'Morning', '10:00:00', '13:00:00', 1),
(13, 2, 'Thursday', 'Afternoon', '15:30:00', '20:15:00', 1),
(14, 2, 'Friday', 'Morning', '10:00:00', '13:00:00', 1),
(15, 2, 'Friday', 'Afternoon', '15:30:00', '20:15:00', 1),
(16, 3, 'Monday', 'Full Day', '09:30:00', '16:00:00', 1),
(17, 3, 'Tuesday', 'Full Day', '09:30:00', '16:00:00', 1),
(18, 3, 'Wednesday', 'Full Day', '09:30:00', '16:00:00', 1),
(19, 3, 'Thursday', 'Full Day', '09:30:00', '19:30:00', 1),
(20, 3, 'Friday', 'Full Day', '09:30:00', '16:00:00', 1),
(21, 4, 'Monday', 'Full Day', '09:30:00', '20:30:00', 1),
(22, 4, 'Tuesday', 'Full Day', '09:30:00', '20:30:00', 1),
(23, 4, 'Wednesday', 'Full Day', '09:30:00', '20:30:00', 1),
(24, 4, 'Thursday', 'Full Day', '09:30:00', '20:30:00', 1),
(25, 4, 'Friday', 'Full Day', '09:30:00', '20:30:00', 1),
(26, 5, 'Monday', 'Full Day', '09:30:00', '20:30:00', 1),
(27, 5, 'Tuesday', 'Full Day', '09:30:00', '20:30:00', 1),
(28, 5, 'Wednesday', 'Full Day', '09:30:00', '20:30:00', 1),
(29, 5, 'Thursday', 'Full Day', '09:30:00', '20:30:00', 1),
(30, 5, 'Friday', 'Full Day', '09:30:00', '20:30:00', 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `offer_services`
--
ALTER TABLE `offer_services`
  ADD CONSTRAINT `offer_services_ibfk_1` FOREIGN KEY (`id_offer`) REFERENCES `offers` (`id_offer`) ON DELETE CASCADE,
  ADD CONSTRAINT `offer_services_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customersdetails` (`id_customer`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE SET NULL;

--
-- Filtros para la tabla `reservationservices`
--
ALTER TABLE `reservationservices`
  ADD CONSTRAINT `reservationServices_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `reservations` (`id_reservation`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservationServices_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`) ON DELETE CASCADE;

--
-- Filtros para la tabla `workschedules`
--
ALTER TABLE `workschedules`
  ADD CONSTRAINT `workSchedules_ibfk_1` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
