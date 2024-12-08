-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-12-2024 a las 02:12:21
-- Versión del servidor: 8.0.40-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `VietnamNails`
--
CREATE DATABASE IF NOT EXISTS `VietnamNails` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `VietnamNails`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `companyHours`
--

DROP TABLE IF EXISTS `companyHours`;
CREATE TABLE `companyHours` (
  `id_companyHour` int NOT NULL,
  `dayOfWeek` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') COLLATE utf8mb4_general_ci NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `isTemporary` tinyint(1) DEFAULT '0',
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `companyHours`
--

INSERT INTO `companyHours` (`id_companyHour`, `dayOfWeek`, `startTime`, `endTime`, `isTemporary`, `startDate`, `endDate`) VALUES
(1, 'Monday', '09:30:00', '20:30:00', 0, NULL, NULL),
(2, 'Tuesday', '09:30:00', '20:30:00', 0, NULL, NULL),
(3, 'Wednesday', '09:30:00', '20:30:00', 0, NULL, NULL),
(4, 'Thursday', '09:30:00', '20:30:00', 0, NULL, NULL),
(5, 'Friday', '09:30:00', '20:30:00', 0, NULL, NULL),
(6, 'Saturday', '09:30:00', '15:00:00', 1, '2024-12-14', '2025-01-04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customersDetails`
--

DROP TABLE IF EXISTS `customersDetails`;
CREATE TABLE `customersDetails` (
  `id_customer` int NOT NULL,
  `fullName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phoneNumber` varchar(15) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id_employee` int NOT NULL,
  `firstName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `dataCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isActive` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `employees`
--

INSERT INTO `employees` (`id_employee`, `firstName`, `lastName`, `phone`, `dataCreated`, `isActive`) VALUES
(1, 'Hiep', 'Cao', '123456789', '2024-11-29 11:57:57', 1),
(2, 'Georgina', '', '234567890', '2024-11-29 12:02:30', 1),
(3, 'Yulia', '', '345678901', '2024-11-29 12:02:30', 1),
(4, 'Anonimo', '', '012345678', '2024-11-29 12:05:23', 1),
(5, 'Anonimo', '', '012345678', '2024-11-29 12:05:23', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations` (
  `id_reservation` int NOT NULL,
  `id_customer` int NOT NULL,
  `reservationDate` datetime NOT NULL,
  `id_employee` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservationServices`
--

DROP TABLE IF EXISTS `reservationServices`;
CREATE TABLE `reservationServices` (
  `id_reservationService` int NOT NULL,
  `id_reservation` int NOT NULL,
  `id_service` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id_service` int NOT NULL,
  `nameService` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `duration` int NOT NULL COMMENT 'Duración en minutos',
  `price` decimal(10,2) NOT NULL,
  `availableFrom` date DEFAULT NULL COMMENT 'Fecha de inicio de la disponibilidad del servicio',
  `availableUntil` date DEFAULT NULL COMMENT 'Fecha de finalización de la disponibilidad del servicio',
  `isActive` tinyint(1) DEFAULT '1' COMMENT 'Indica si el servicio está activo actualmente (1: activo, 0: inactivo)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id_service`, `nameService`, `description`, `duration`, `price`, `availableFrom`, `availableUntil`, `isActive`, `created_at`, `update_at`) VALUES
(1, 'Uñas de gel', 'Prótesis creadas especialmente para las uñas a partir de un gel acrílico que se moldea en la uña natural', 75, '15.00', NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(2, 'Semi capa', 'Tiene que ser un tratamiento de uñas que no es una capa completa', 75, '15.00', NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(3, 'Semi permanente', 'Un tipo de manicura que se hace cada 2-3 semanas', 75, '15.00', NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(4, 'Manicura', 'Tratamiento cosmético en el que se da la forma deseada a las uñas de las manos, y se maquillan con un esmalte', 75, '15.00', NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(5, 'Pedicura', 'Tratamiento cosmético superficial de las uñas de los pies', 75, '15.00', NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41'),
(6, 'Masajes', 'Manipulación de las capas superficiales y profundas de los músculos del cuerpo', 75, '15.00', NULL, NULL, 1, '2024-11-29 12:21:41', '2024-11-29 12:21:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `userName` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `isActive` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `userName`, `password`, `isActive`, `created_at`, `updated_at`) VALUES
(1, 'root', '', 1, '2024-11-29 14:59:57', '2024-11-29 14:59:57'),
(2, 'Cao', '1234', 1, '2024-11-29 14:59:57', '2024-11-29 14:59:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `workSchedules`
--

DROP TABLE IF EXISTS `workSchedules`;
CREATE TABLE `workSchedules` (
  `id_workSchedule` int NOT NULL,
  `id_employee` int NOT NULL,
  `dayOfWeek` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') COLLATE utf8mb4_general_ci NOT NULL,
  `blockType` enum('Morning','Afternoon','Full Day') COLLATE utf8mb4_general_ci NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `isActive` tinyint(1) DEFAULT '1' COMMENT 'Indica si el servicio está activo actualmente (1: activo, 0: inactivo)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `workSchedules`
--

INSERT INTO `workSchedules` (`id_workSchedule`, `id_employee`, `dayOfWeek`, `blockType`, `startTime`, `endTime`, `isActive`) VALUES
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
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `companyHours`
--
ALTER TABLE `companyHours`
  ADD PRIMARY KEY (`id_companyHour`);

--
-- Indices de la tabla `customersDetails`
--
ALTER TABLE `customersDetails`
  ADD PRIMARY KEY (`id_customer`),
  ADD UNIQUE KEY `phoneNumber` (`phoneNumber`);

--
-- Indices de la tabla `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id_employee`);

--
-- Indices de la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_employee` (`id_employee`);

--
-- Indices de la tabla `reservationServices`
--
ALTER TABLE `reservationServices`
  ADD PRIMARY KEY (`id_reservationService`),
  ADD KEY `id_reservation` (`id_reservation`),
  ADD KEY `id_service` (`id_service`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id_service`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indices de la tabla `workSchedules`
--
ALTER TABLE `workSchedules`
  ADD PRIMARY KEY (`id_workSchedule`),
  ADD KEY `id_employee` (`id_employee`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `companyHours`
--
ALTER TABLE `companyHours`
  MODIFY `id_companyHour` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `customersDetails`
--
ALTER TABLE `customersDetails`
  MODIFY `id_customer` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `employees`
--
ALTER TABLE `employees`
  MODIFY `id_employee` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservationServices`
--
ALTER TABLE `reservationServices`
  MODIFY `id_reservationService` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id_service` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `workSchedules`
--
ALTER TABLE `workSchedules`
  MODIFY `id_workSchedule` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customersDetails` (`id_customer`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE SET NULL;

--
-- Filtros para la tabla `reservationServices`
--
ALTER TABLE `reservationServices`
  ADD CONSTRAINT `reservationServices_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `reservations` (`id_reservation`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservationServices_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`) ON DELETE CASCADE;

--
-- Filtros para la tabla `workSchedules`
--
ALTER TABLE `workSchedules`
  ADD CONSTRAINT `workSchedules_ibfk_1` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;