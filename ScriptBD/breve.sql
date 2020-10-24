-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-10-2020 a las 05:05:27
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `breve`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sender_latitude` varchar(50) NOT NULL,
  `sender_longitude` varchar(50) NOT NULL,
  `sender_address` varchar(255) NOT NULL,
  `sender_neighborhood` varchar(255) NOT NULL,
  `receiver_latitude` varchar(50) NOT NULL,
  `receiver_longitude` varchar(50) NOT NULL,
  `receiver_address` varchar(255) NOT NULL,
  `receiver_neighborhood` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `brever_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Id del brever que toma un servicio	',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `admin_notifications`
--

INSERT INTO `admin_notifications` (`id`, `service_id`, `brever_id`, `status`, `created_at`, `updated_at`) VALUES
(7, 2, 0, 1, '2020-06-11 17:42:26', '2020-06-13 01:28:17'),
(8, 7, 0, 1, '2020-06-26 01:50:00', '2020-06-26 01:52:10'),
(9, 8, 0, 0, '2020-06-26 05:27:36', '2020-06-26 05:27:36'),
(10, 10, 0, 1, '2020-06-29 23:00:43', '2020-06-29 23:26:06'),
(11, 11, 0, 0, '2020-06-29 23:05:48', '2020-06-29 23:05:48'),
(12, 12, 0, 0, '2020-06-30 02:14:59', '2020-06-30 02:14:59'),
(13, 14, 0, 0, '2020-07-05 16:56:39', '2020-07-05 16:56:39'),
(14, 14, 17, 0, '2020-07-05 17:06:47', '2020-07-05 17:06:47'),
(15, 13, 17, 1, '2020-07-05 17:13:37', '2020-07-05 18:05:34'),
(16, 16, 0, 1, '2020-07-09 01:02:29', '2020-07-09 01:07:14'),
(18, 20, 0, 0, '2020-07-10 00:10:10', '2020-07-10 00:10:10'),
(19, 21, 0, 0, '2020-07-10 00:59:10', '2020-07-10 00:59:10'),
(20, 22, 0, 0, '2020-07-10 01:04:24', '2020-07-10 01:04:24'),
(21, 25, 0, 1, '2020-07-16 20:51:39', '2020-07-23 20:54:44'),
(22, 25, 12, 1, '2020-07-16 21:02:13', '2020-08-27 19:27:51'),
(23, 26, 0, 1, '2020-07-16 21:07:02', '2020-07-23 20:55:33'),
(24, 26, 12, 1, '2020-07-16 21:07:41', '2020-08-11 01:08:05'),
(25, 27, 12, 1, '2020-07-16 22:27:39', '2020-07-23 20:55:21'),
(26, 27, 12, 1, '2020-07-16 22:27:40', '2020-08-13 20:20:46'),
(29, 32, 0, 1, '2020-09-25 19:34:35', '2020-09-25 19:39:53'),
(30, 33, 0, 1, '2020-10-06 02:24:06', '2020-10-06 02:27:01'),
(31, 34, 0, 0, '2020-10-06 02:50:59', '2020-10-06 02:50:59'),
(32, 35, 0, 0, '2020-10-08 23:54:40', '2020-10-08 23:54:40'),
(33, 36, 0, 0, '2020-10-12 18:26:48', '2020-10-12 18:26:48'),
(34, 40, 2, 0, '2020-10-21 18:40:14', '2020-10-21 18:40:14'),
(35, 39, 2, 0, '2020-10-21 18:40:27', '2020-10-21 18:40:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `balances`
--

CREATE TABLE `balances` (
  `id` int(11) NOT NULL,
  `brever_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'Domicilio Breve. Recaudo. Recarga.',
  `brever_commission` double NOT NULL,
  `brever_balance` double NOT NULL,
  `breve_commission` double NOT NULL,
  `breve_balance` double NOT NULL,
  `service_id` int(11) NOT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `balances`
--

INSERT INTO `balances` (`id`, `brever_id`, `type`, `brever_commission`, `brever_balance`, `breve_commission`, `breve_balance`, `service_id`, `transfer_id`, `date`, `description`, `created_at`, `updated_at`) VALUES
(19, 17, 'Domicilio Breve', 3750, 3750, 1250, 1250, 1, NULL, '2020-06-11', NULL, '2020-06-11 17:30:49', '2020-06-11 17:30:49'),
(20, 17, 'Recarga', 10000, 13750, 0, 1250, 0, NULL, '2020-06-11', NULL, '2020-06-11 17:37:11', '2020-06-11 17:37:11'),
(21, 17, 'Domicilio Breve', 6000, 11750, 2000, 3250, 3, NULL, '2020-06-12', NULL, '2020-06-12 01:02:00', '2020-06-12 01:02:00'),
(22, 12, 'Domicilio Breve', 4500, -1500, 1500, 4750, 5, NULL, '2020-06-13', NULL, '2020-06-13 22:10:52', '2020-06-13 22:10:52'),
(23, 17, 'Descuento', 0, 1750, 10000, 14750, 0, NULL, '2020-06-26', 'pruebas', '2020-06-26 01:33:21', '2020-06-26 01:33:21'),
(24, 17, 'Recarga', 10000, 11750, 0, 14750, 0, NULL, '2020-06-26', 'retribución de saldo', '2020-06-26 01:34:01', '2020-06-26 01:34:01'),
(25, 17, 'Descuento', 0, 6750, 5000, 19750, 0, NULL, '2020-06-26', 'variso', '2020-06-26 01:35:05', '2020-06-26 01:35:05'),
(26, 12, 'Domicilio Breve', 4500, -3000, 1500, 21250, 7, NULL, '2020-06-26', NULL, '2020-06-26 02:00:27', '2020-06-26 02:00:27'),
(27, 10, 'Domicilio Breve', 3750, 3750, 1250, 22500, 8, NULL, '2020-06-26', NULL, '2020-06-26 05:31:48', '2020-06-26 05:31:48'),
(28, 12, 'Recarga', 20000, 17000, 0, 22500, 0, NULL, '2020-06-30', 'varios', '2020-06-30 02:31:57', '2020-06-30 02:31:57'),
(29, 10, 'Transferencia de Saldo (Crédito)', 10000, 13750, 0, 22500, 0, 1, '2020-06-30', 'Transferencia de Saldo de parte de Felipe Echeverry', '2020-06-30 02:32:13', '2020-06-30 02:32:13'),
(30, 12, 'Transferencia de Saldo (Débito)', 10000, 7000, 0, 22500, 0, 1, '2020-06-30', 'Transferencia de Saldo para Brever uno', '2020-06-30 02:32:13', '2020-06-30 02:32:13'),
(31, 12, 'Domicilio Breve', 3750, 10750, 1250, 23750, 10, NULL, '2020-07-03', NULL, '2020-07-03 03:04:28', '2020-07-03 03:04:28'),
(32, 17, 'Domicilio Breve', 37500, -5750, 12500, 36250, 13, NULL, '2020-07-05', NULL, '2020-07-05 19:58:06', '2020-07-05 19:58:06'),
(33, 17, 'Domicilio Breve', 4500, -1250, 1500, 37750, 14, NULL, '2020-07-05', NULL, '2020-07-05 20:38:49', '2020-07-05 20:38:49'),
(35, 12, 'Domicilio Breve', 4500, 15250, 1500, 40500, 21, NULL, '2020-07-10', NULL, '2020-07-10 01:08:54', '2020-07-10 01:08:54'),
(36, 12, 'Domicilio Breve', 4500, 13750, 1500, 42000, 23, NULL, '2020-07-10', NULL, '2020-07-10 01:13:57', '2020-07-10 01:13:57'),
(37, 12, 'Domicilio Breve', 3750, 12500, 1250, 43250, 17, NULL, '2020-07-16', NULL, '2020-07-16 12:40:08', '2020-07-16 12:40:08'),
(38, 12, 'Domicilio Breve', 3750, 11250, 1250, 44500, 24, NULL, '2020-07-16', NULL, '2020-07-16 12:40:36', '2020-07-16 12:40:36'),
(39, 12, 'Domicilio Breve', 3750, 15000, 1250, 45750, 20, NULL, '2020-07-16', NULL, '2020-07-16 12:47:56', '2020-07-16 12:47:56'),
(40, 10, 'Transferencia de Saldo (Crédito)', 10000, 23750, 0, 45750, 0, 2, '2020-07-16', 'Transferencia de Saldo de parte de Felipe Echeverry', '2020-07-16 12:51:04', '2020-07-16 12:51:04'),
(41, 12, 'Transferencia de Saldo (Débito)', 10000, 5000, 0, 45750, 0, 2, '2020-07-16', 'Transferencia de Saldo para Brever uno', '2020-07-16 12:51:04', '2020-07-16 12:51:04'),
(42, 12, 'Domicilio Breve', 3750, 3750, 1250, 47000, 25, NULL, '2020-07-16', NULL, '2020-07-16 21:03:02', '2020-07-16 21:03:02'),
(43, 12, 'Domicilio Breve', 3750, 2500, 1250, 48250, 26, NULL, '2020-07-16', NULL, '2020-07-16 21:12:00', '2020-07-16 21:12:00'),
(44, 12, 'Domicilio Breve', 3750, 6250, 1250, 49500, 15, NULL, '2020-07-16', NULL, '2020-07-16 22:54:49', '2020-07-16 22:54:49'),
(45, 12, 'Domicilio Breve', 3750, 10000, 1250, 50750, 16, NULL, '2020-07-16', NULL, '2020-07-16 22:55:06', '2020-07-16 22:55:06'),
(46, 12, 'Domicilio Breve', 4500, 8500, 1500, 52250, 27, NULL, '2020-07-16', NULL, '2020-07-16 23:43:46', '2020-07-16 23:43:46'),
(49, 2, 'Domicilio Breve', 6375, -2125, 2125, 54375, 26, NULL, '2020-08-11', NULL, '2020-08-11 01:29:19', '2020-08-11 01:29:19'),
(50, 2, 'Domicilio Breve', 5625, 3500, 1875, 56250, 2, NULL, '2020-08-12', NULL, '2020-08-12 14:24:02', '2020-08-12 14:24:02'),
(51, 2, 'Domicilio Breve', 3750, 7250, 1250, 57500, 1, NULL, '2020-08-12', NULL, '2020-08-12 14:24:42', '2020-08-12 14:24:42'),
(52, 2, 'Domicilio Breve', 3750, 11000, 1250, 58750, 4, NULL, '2020-08-13', NULL, '2020-08-13 20:24:22', '2020-08-13 20:24:22'),
(53, 12, 'Domicilio Breve', 3750, 12250, 1250, 60000, 28, NULL, '2020-08-27', NULL, '2020-08-27 20:17:33', '2020-08-27 20:17:33'),
(54, 2, 'Descuento', 0, -4000, 15000, 75000, 0, NULL, '2020-08-31', 'prueba', '2020-08-31 17:45:16', '2020-08-31 17:45:16'),
(55, 2, 'Descuento', 0, -9000, 5000, 80000, 0, NULL, '2020-08-31', 'prue', '2020-08-31 17:45:29', '2020-08-31 17:45:29'),
(56, 2, 'Recarga', 2000, -7000, 0, 80000, 0, NULL, '2020-08-31', 'gh', '2020-08-31 17:45:41', '2020-08-31 17:45:41'),
(57, 2, 'Recarga', 9500, 2500, 0, 80000, 0, NULL, '2020-08-31', 'gf', '2020-08-31 17:45:50', '2020-08-31 17:45:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `service_id`, `title`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, 17, 1, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-06-11 17:20:26', '2020-06-11 17:22:56'),
(2, 0, 1, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-06-11 17:20:26', '2020-06-11 17:20:26'),
(3, 17, 1, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-11 17:30:49', '2020-06-11 17:30:49'),
(4, 0, 1, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-11 17:30:49', '2020-06-11 17:30:49'),
(5, 2, 2, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-06-11 17:46:08', '2020-08-11 00:56:14'),
(6, 6, 2, 'Su servicio ha sido asignado', 'feather icon-check-circle', 1, '2020-06-11 17:46:08', '2020-06-11 17:46:21'),
(7, 17, 3, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-06-12 01:00:31', '2020-06-12 01:01:22'),
(8, 21, 3, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-06-12 01:00:31', '2020-06-12 01:00:31'),
(9, 17, 3, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-12 01:02:00', '2020-06-12 01:02:00'),
(10, 21, 3, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-12 01:02:00', '2020-06-12 01:02:00'),
(11, 12, 5, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-06-13 20:49:29', '2020-06-13 20:51:25'),
(12, 0, 5, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-06-13 20:49:29', '2020-06-13 20:49:29'),
(13, 12, 5, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-13 22:10:52', '2020-06-13 22:10:52'),
(14, 0, 5, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-13 22:10:52', '2020-06-13 22:10:52'),
(15, 12, 7, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-06-26 01:50:53', '2020-06-26 01:54:18'),
(16, 20, 7, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-06-26 01:50:54', '2020-06-26 01:50:54'),
(17, 12, 7, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-26 02:00:27', '2020-06-26 02:00:27'),
(18, 20, 7, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-26 02:00:27', '2020-06-26 02:00:27'),
(19, 10, 8, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-06-26 05:30:22', '2020-06-26 05:31:06'),
(20, 4, 8, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-06-26 05:30:22', '2020-06-26 05:30:22'),
(21, 10, 8, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-26 05:31:48', '2020-06-26 05:31:48'),
(22, 4, 8, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-06-26 05:31:48', '2020-06-26 05:31:48'),
(23, 12, 10, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-06-29 23:25:37', '2020-06-30 02:19:23'),
(24, 20, 10, 'Su servicio ha sido asignado', 'feather icon-check-circle', 1, '2020-06-29 23:25:37', '2020-07-05 16:57:06'),
(25, 12, 10, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-03 03:04:28', '2020-07-03 03:04:28'),
(26, 20, 10, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-03 03:04:28', '2020-07-03 03:04:28'),
(27, 20, 14, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-05 17:06:47', '2020-07-05 17:06:47'),
(28, 0, 13, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-05 17:13:37', '2020-07-05 17:13:37'),
(29, 17, 13, 'Su servicio ha sido completado', 'feather icon-check-circle', 1, '2020-07-05 19:58:06', '2020-07-10 00:50:15'),
(30, 0, 13, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-05 19:58:06', '2020-07-05 19:58:06'),
(31, 17, 14, 'Su servicio ha sido completado', 'feather icon-check-circle', 1, '2020-07-05 20:38:49', '2020-07-10 00:48:18'),
(32, 20, 14, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-05 20:38:49', '2020-07-05 20:38:49'),
(33, 12, 15, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-07-09 00:57:09', '2020-07-09 00:57:31'),
(34, 0, 15, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-09 00:57:09', '2020-07-09 00:57:09'),
(35, 12, 16, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-07-09 01:14:43', '2020-07-09 01:16:16'),
(36, 4, 16, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-09 01:14:43', '2020-07-09 01:14:43'),
(37, 2, 2, 'Su servicio ha sido completado', 'feather icon-check-circle', 1, '2020-07-09 01:31:04', '2020-08-11 00:56:14'),
(38, 6, 2, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-09 01:31:04', '2020-07-09 01:31:04'),
(39, 12, 17, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-07-09 01:31:39', '2020-07-09 23:56:29'),
(40, 0, 17, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-09 01:31:39', '2020-07-09 01:31:39'),
(41, 12, 21, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-07-10 01:06:30', '2020-07-10 01:06:35'),
(42, 20, 21, 'Su servicio ha sido asignado', 'feather icon-check-circle', 1, '2020-07-10 01:06:30', '2020-07-15 05:10:13'),
(43, 12, 22, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-07-10 01:06:51', '2020-07-10 01:09:29'),
(44, 20, 22, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-10 01:06:51', '2020-07-10 01:06:51'),
(45, 12, 21, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-10 01:08:54', '2020-07-10 01:08:54'),
(46, 20, 21, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-10 01:08:54', '2020-07-10 01:08:54'),
(47, 12, 23, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-07-10 01:11:13', '2020-07-10 01:13:18'),
(48, 0, 23, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-10 01:11:13', '2020-07-10 01:11:13'),
(49, 12, 20, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-07-10 01:12:41', '2020-07-10 01:12:49'),
(50, 4, 20, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-10 01:12:41', '2020-07-10 01:12:41'),
(51, 12, 23, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-10 01:13:57', '2020-07-10 01:13:57'),
(52, 0, 23, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-10 01:13:57', '2020-07-10 01:13:57'),
(53, 2, 22, 'Su servicio ha sido declinado', 'feather icon-alert-circle', 1, '2020-07-15 05:12:01', '2020-08-11 00:51:26'),
(54, 12, 24, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-07-16 12:39:17', '2020-07-16 12:40:25'),
(55, 0, 24, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-16 12:39:17', '2020-07-16 12:39:17'),
(56, 12, 17, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 12:40:08', '2020-07-16 12:40:08'),
(57, 0, 17, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 12:40:08', '2020-07-16 12:40:08'),
(58, 12, 24, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 12:40:36', '2020-07-16 12:40:36'),
(59, 0, 24, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 12:40:36', '2020-07-16 12:40:36'),
(60, 12, 20, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 12:47:56', '2020-07-16 12:47:56'),
(61, 4, 20, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 12:47:56', '2020-07-16 12:47:56'),
(62, 4, 25, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-16 21:02:13', '2020-07-16 21:02:13'),
(63, 12, 25, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 21:03:02', '2020-07-16 21:03:02'),
(64, 4, 25, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 21:03:02', '2020-07-16 21:03:02'),
(65, 20, 26, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-16 21:07:41', '2020-07-16 21:07:41'),
(66, 12, 26, 'Su servicio ha sido completado', 'feather icon-check-circle', 1, '2020-07-16 21:12:00', '2020-07-16 22:54:11'),
(67, 20, 26, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 21:12:00', '2020-07-16 21:12:00'),
(68, 0, 27, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-16 22:27:39', '2020-07-16 22:27:39'),
(69, 0, 27, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-16 22:27:40', '2020-07-16 22:27:40'),
(70, 12, 15, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 22:54:49', '2020-07-16 22:54:49'),
(71, 0, 15, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 22:54:49', '2020-07-16 22:54:49'),
(72, 12, 16, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 22:55:06', '2020-07-16 22:55:06'),
(73, 4, 16, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 22:55:06', '2020-07-16 22:55:06'),
(74, 12, 27, 'Su servicio ha sido completado', 'feather icon-check-circle', 1, '2020-07-16 23:43:46', '2020-07-16 23:44:38'),
(75, 0, 27, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-07-16 23:43:46', '2020-07-16 23:43:46'),
(76, 12, 28, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-07-26 23:48:36', '2020-07-26 23:50:35'),
(77, 0, 28, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-07-26 23:48:36', '2020-07-26 23:48:36'),
(78, 2, 26, 'Su servicio ha sido completado', 'feather icon-check-circle', 1, '2020-08-11 01:06:47', '2020-08-11 01:06:53'),
(79, 16, 26, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-11 01:06:47', '2020-08-11 01:06:47'),
(80, 2, 26, 'Su servicio ha sido completado', 'feather icon-check-circle', 1, '2020-08-11 01:25:30', '2020-08-11 01:29:05'),
(81, 16, 26, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-11 01:25:30', '2020-08-11 01:25:30'),
(82, 2, 26, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-11 01:29:19', '2020-08-11 01:29:19'),
(83, 16, 26, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-11 01:29:19', '2020-08-11 01:29:19'),
(84, 2, 1, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 0, '2020-08-12 14:19:32', '2020-08-12 14:19:32'),
(85, 0, 1, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-08-12 14:19:32', '2020-08-12 14:19:32'),
(86, 2, 2, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 0, '2020-08-12 14:23:26', '2020-08-12 14:23:26'),
(87, 6, 2, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-08-12 14:23:26', '2020-08-12 14:23:26'),
(88, 2, 2, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-12 14:24:02', '2020-08-12 14:24:02'),
(89, 6, 2, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-12 14:24:02', '2020-08-12 14:24:02'),
(90, 2, 1, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-12 14:24:42', '2020-08-12 14:24:42'),
(91, 0, 1, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-12 14:24:42', '2020-08-12 14:24:42'),
(92, 2, 4, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 0, '2020-08-13 20:16:03', '2020-08-13 20:16:03'),
(93, 0, 4, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-08-13 20:16:03', '2020-08-13 20:16:03'),
(94, 2, 4, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-13 20:24:22', '2020-08-13 20:24:22'),
(95, 0, 4, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-13 20:24:22', '2020-08-13 20:24:22'),
(96, 2, 5, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 0, '2020-08-27 20:15:17', '2020-08-27 20:15:17'),
(97, 0, 5, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-08-27 20:15:17', '2020-08-27 20:15:17'),
(98, 2, 5, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-27 20:15:57', '2020-08-27 20:15:57'),
(99, 0, 5, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-27 20:15:57', '2020-08-27 20:15:57'),
(100, 12, 28, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-27 20:17:33', '2020-08-27 20:17:33'),
(101, 0, 28, 'Su servicio ha sido completado', 'feather icon-check-circle', 0, '2020-08-27 20:17:33', '2020-08-27 20:17:33'),
(102, 2, 14, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 0, '2020-09-25 21:12:34', '2020-09-25 21:12:34'),
(103, 20, 14, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-09-25 21:12:34', '2020-09-25 21:12:34'),
(104, 2, 14, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 0, '2020-09-26 00:48:17', '2020-09-26 00:48:17'),
(105, 20, 14, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-09-26 00:48:17', '2020-09-26 00:48:17'),
(106, 2, 13, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 1, '2020-09-26 00:50:08', '2020-10-21 18:30:55'),
(107, 0, 13, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-09-26 00:50:08', '2020-09-26 00:50:08'),
(108, 10, 33, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 0, '2020-10-06 03:07:30', '2020-10-06 03:07:30'),
(109, 0, 33, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-10-06 03:07:30', '2020-10-06 03:07:30'),
(110, 10, 37, 'Tienes un nuevo servicio asignado', 'feather icon-plus-square', 0, '2020-10-12 18:52:30', '2020-10-12 18:52:30'),
(111, 0, 37, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-10-12 18:52:30', '2020-10-12 18:52:30'),
(112, 1, 40, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-10-21 18:40:14', '2020-10-21 18:40:14'),
(113, 0, 39, 'Su servicio ha sido asignado', 'feather icon-check-circle', 0, '2020-10-21 18:40:26', '2020-10-21 18:40:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remember_data`
--

CREATE TABLE `remember_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `alias_admin` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `neighborhood` varchar(255) NOT NULL,
  `address_opc` varchar(255) DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `remember_data`
--

INSERT INTO `remember_data` (`id`, `user_id`, `alias`, `alias_admin`, `name`, `phone`, `neighborhood`, `address_opc`, `type`, `admin`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mis Datos', NULL, 'Luisana Marin', '04266379981', 'Juan Griego', 'juan griego/calle bermudez', 'sender', 0, '2020-07-09 02:51:39', '2020-07-09 02:51:39'),
(2, 1, 'Freddy Valle', NULL, 'Freddy Millan', '04169951608', 'el valle', 'El valle del espiritu santo', 'receiver', 0, '2020-07-09 02:51:39', '2020-07-09 02:51:39'),
(5, 4, 'uno', NULL, 'dood', '0436', 'dodo', 'dkkds', 'sender', 0, '2020-07-10 00:10:10', '2020-07-10 00:10:10'),
(6, 4, 'soso', NULL, 'dodo', '324', 'dodod', 'jjd', 'receiver', 0, '2020-07-10 00:10:10', '2020-07-10 00:10:10'),
(7, 20, 'Casa', NULL, 'Felipe Echeverry', '3193144437', 'Napoles', 'Carrera 77A #3A-41', 'sender', 0, '2020-07-10 00:59:10', '2020-07-10 00:59:10'),
(8, 20, 'SANTIAGO', NULL, 'Santiago Ocampo', '3183703025', 'El lido', 'Carrera 47 #1-70', 'receiver', 0, '2020-07-10 00:59:10', '2020-07-10 00:59:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `sender_phone` varchar(50) DEFAULT NULL,
  `sender_neighborhood` varchar(255) DEFAULT NULL,
  `sender_address` varchar(255) DEFAULT NULL,
  `sender_address_opc` varchar(255) DEFAULT NULL,
  `sender_latitude` varchar(100) DEFAULT NULL,
  `sender_longitude` varchar(100) DEFAULT NULL,
  `receiver` varchar(255) DEFAULT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `receiver_phone` varchar(50) DEFAULT NULL,
  `receiver_neighborhood` varchar(255) DEFAULT NULL,
  `receiver_address` varchar(255) DEFAULT NULL,
  `receiver_address_opc` varchar(255) DEFAULT NULL,
  `receiver_latitude` varchar(100) DEFAULT NULL,
  `receiver_longitude` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `article` varchar(255) DEFAULT NULL,
  `equipment_type` varchar(100) DEFAULT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = Sin Confirmar. 1 = Confirmado.',
  `refund_amount` double DEFAULT NULL,
  `rate` double DEFAULT 0,
  `additional_cost` double DEFAULT 0,
  `total` double DEFAULT 0,
  `observations` text DEFAULT NULL,
  `brever_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = Pendiente. 1 = Asignado. 2 = Iniciado. 3= Confirmado. 4 = Completado. 5 = Cancelado',
  `rate_status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id`, `user_id`, `client_name`, `sender`, `sender_name`, `sender_phone`, `sender_neighborhood`, `sender_address`, `sender_address_opc`, `sender_latitude`, `sender_longitude`, `receiver`, `receiver_name`, `receiver_phone`, `receiver_neighborhood`, `receiver_address`, `receiver_address_opc`, `receiver_latitude`, `receiver_longitude`, `date`, `time`, `article`, `equipment_type`, `payment_type`, `payment_method`, `payment_status`, `refund_amount`, `rate`, `additional_cost`, `total`, `observations`, `brever_id`, `status`, `rate_status`, `created_at`, `updated_at`) VALUES
(1, 0, NULL, NULL, NULL, NULL, 'Limonar', 'centro comercial cosmocentro', NULL, '3.4140011', '-76.5474462', NULL, NULL, NULL, 'cali', 'premier limonar', NULL, '3.3944556', '-76.54449919999999', '2020-06-11', '17:15:00', NULL, 'maletin', 'inicio', 'transferencia', 1, NULL, 5000, 0, 5000, NULL, 2, 4, 1, '2020-06-11 17:19:02', '2020-08-12 14:24:42'),
(2, 6, NULL, NULL, 'Ajuju', '3183703025', 'Napoles', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', 'Cra 77a #3a-41', '3.4516467', '-76.5319854', NULL, 'Juania', '3183703025', 'Lido', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'Cra 5 #5-40', '3.4536466999999997', '-76.5299854', '2020-06-12', '18:41:00', 'Botella', 'maletin', 'inicio', 'transferencia', 0, 0, 5000, 2500, 7500, NULL, 2, 4, 1, '2020-06-11 17:42:26', '2020-08-12 14:24:02'),
(3, 21, NULL, NULL, 'Luisa Cardona', '7888983830', 'Lido', 'Cra. 47 #1-70, Cali, Valle del Cauca, Colombia', 'Cra 47 #1-70', '3.4187662', '-76.5529573', NULL, 'Samuel', '9898909986', 'Chipichape', 'Av. 6 Nte. #46, Cali, Valle del Cauca, Colombia', 'C.c Chipichape', '3.4754783', '-76.52696399999999', '2020-06-18', '09:57:00', 'Telefono', 'maletin', 'final', 'efectivo', 0, NULL, 8000, 0, 8000, 'Ser muy cuidadoso', 17, 0, 1, '2020-06-12 00:59:58', '2020-06-12 01:02:00'),
(4, 0, NULL, NULL, NULL, NULL, 'limonar', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', NULL, '3.4516467', '-76.5319854', NULL, NULL, NULL, 'cali', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', NULL, '3.4536466999999997', '-76.5299854', '2020-06-13', '08:00:00', NULL, 'maletin', 'inicio', 'transferencia', 0, 0, 5000, 0, 5000, NULL, 2, 4, 1, '2020-06-13 01:20:27', '2020-08-13 20:24:22'),
(5, 0, NULL, NULL, NULL, NULL, 'Primero de mayo', 'Cra. 57a #13-45, Cali, Valle del Cauca, Colombia', NULL, '3.4037686', '-76.5355637', NULL, NULL, NULL, 'Cien Palos', 'Cra. 19 #17-57, Cali, Valle del Cauca, Colombia', NULL, '3.4393461', '-76.5228789', '2020-06-13', '16:45:00', 'Paquete con Documentos', 'maletin', 'inicio', 'efectivo', 0, NULL, 6000, 0, 6000, 'Dirección Inicio: CRA 57A NRO  13-47 PRIMERO DE MAYO\r\nCelular: 311 3037204 y 318 8060257  \r\nNombre de quién envía: ANA GUACA\r\nArtículo a transportar: PAQUETE CON DOCUMENTOS \r\nHora de recogida: HOY \r\nDirección Final: CARRERA 19 # 17-57 CIEN PALOS \r\nNombre destinatario: DRA. SANDRA GOMEZ y número de 📱destinatario: 311 3153838\r\nEl servicio lo pagan en: CRA 57A NRO 13-47 PRIMERO DE MAYO \r\n\r\n*Tarifa $6000*', 2, 4, 1, '2020-06-13 20:49:13', '2020-08-27 20:15:57'),
(6, 0, 'Eduardo gonzles', NULL, 'uno', '04269315724', 'lourdes', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', 'Urb santa martha', '3.4516467', '-76.5319854', NULL, 'aks', '4269315724', 'dobe', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'kakaka', '3.4536466999999997', '-76.5299854', '2020-06-27', '08:00:00', 'desayuno', 'maletin', 'inicio', 'transferencia', 0, NULL, 5000, 0, 5000, NULL, NULL, 5, 1, '2020-06-26 01:30:15', '2020-06-27 13:05:02'),
(7, 20, NULL, NULL, 'Juan Perez', '1234567890', 'Lido', 'Cra. 47 #1-70, Cali, Valle del Cauca, Colombia', 'cra 47 #1-70', '3.4187535', '-76.5529631', NULL, 'cra 77a #3a-41', '3183703025', 'Napoles', 'Cra. 77a ##3a-16, Cali, Valle del Cauca, Colombia', 'Cra 77a #3a-41', '3.3878138', '-76.5478864', '2020-06-26', '09:47:00', 'Linterna', 'maletin', 'final', 'efectivo', 0, NULL, 6000, 0, 6000, 'Ir directo, no en ruta', 12, 4, 1, '2020-06-26 01:50:00', '2020-06-26 02:00:27'),
(8, 4, NULL, NULL, 'ale', '04269315724', 'dhdh', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', 'Urb santa martha', '3.4516467', '-76.5319854', NULL, 'ak', '0123455677', 'cali', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'Urb santa martha', '3.4536466999999997', '-76.5299854', '2020-06-27', '08:00:00', 'desayuno', 'maletin', 'inicio', 'transferencia', 0, NULL, 5000, 0, 5000, NULL, 10, 4, 1, '2020-06-26 05:27:36', '2020-06-26 05:31:47'),
(9, 0, 'luisana', NULL, NULL, NULL, 'Juan Griego', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', NULL, '3.4516467', '-76.5319854', NULL, NULL, NULL, 'El Valle', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', NULL, '3.4536466999999997', '-76.5299854', '2020-06-28', '10:00:00', 'var', 'maletin; maleta; canasta', 'inicio', 'transferencia', 0, NULL, 5000, 0, 5000, 'xxxx', NULL, 5, 1, '2020-06-27 02:19:31', '2020-06-28 15:05:03'),
(10, 20, NULL, NULL, 'dsda', '23432', 'dsf', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', '5401 NW 102nd Ave, Ste 103', '3.4516467', '-76.5319854', NULL, '5401 NW 102nd Ave', '4269315724', 'cali', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'cra 58 # 93-41', '3.4536466999999997', '-76.5299854', '2020-06-30', '08:00:00', 'desayuno', 'maletin', 'inicio', 'transferencia', 0, NULL, 5000, 0, 5000, 'njh', 12, 4, 1, '2020-06-29 23:00:43', '2020-07-03 03:04:28'),
(11, 20, NULL, NULL, 'a', '7542463347', 'as', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', '5401 NW 102nd Ave, Ste 103', '3.4516467', '-76.5319854', NULL, '5401 NW 102nd Ave', '3137797191', 'cali', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'Av. Las pilas', '3.4536466999999997', '-76.5299854', '2020-06-30', '08:00:00', 'desayuno', 'maletin', 'inicio', 'transferencia', 0, NULL, 5000, 0, 5000, NULL, NULL, 5, 1, '2020-06-29 23:05:48', '2020-06-30 13:05:04'),
(12, 20, NULL, NULL, 'a', '0404', 'a', 'Cra. 77a #3a-41, Cali, Valle del Cauca, Colombia', 'a', '3.3878138', '-76.5478864', NULL, 'aa', '4269315724', 'b', 'Cra. 47 #1-70, Cali, Valle del Cauca, Colombia', '5401 NW 102nd Ave', '3.4187535', '-76.5529631', '2020-06-30', '08:12:00', 'var', 'maletin', 'inicio', 'transferencia', 0, NULL, 6000, 0, 6000, NULL, NULL, 5, 1, '2020-06-30 02:14:59', '2020-06-30 13:15:03'),
(13, 0, 'R&A STORE', NULL, 'Felipe Chaves', '3183703025', 'Nueva tequendama', 'dikey store', 'Carrera 53 #7-54', '3.4125821', '-76.5444933', NULL, 'Antonio Herrera', '3174916444', 'Salomia', 'Parque del Sagrado Corazón, Por Carrera 1H, Cra. 1G #s/n, Cali, Valle del Cauca, Colombia', 'Calle 46A #1G-70', '3.4675833273359937', '-76.5060142273697', '2020-07-05', '14:00:00', NULL, 'maletin; maleta', 'inicio', 'reembolso', 0, 42000, 8000, 0, 50000, NULL, 2, 1, 1, '2020-07-05 16:44:41', '2020-09-26 00:50:08'),
(14, 20, NULL, NULL, 'Felipe Echeverry', '3193144437', 'Napoles', 'CARRERA 77A #3A-41 cali', 'Carrera 77A #3A-41', '3.3878138', '-76.5478864', NULL, 'Santiago Ocampo', '3183703025', 'El lido', 'Carrera 47 #1-70 cali', 'Carrera 47 #1-70 apto 103E', '3.4187535', '-76.5529631', '2020-07-05', '13:30:00', 'Celulares', 'maletin; maleta', 'inicio', 'transferencia', 0, NULL, 6000, 0, 6000, NULL, NULL, 0, 1, '2020-07-05 16:56:39', '2020-09-26 00:49:04'),
(15, 0, 'doodod', NULL, NULL, NULL, 'dodo', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', NULL, '3.4516467', '-76.5319854', NULL, NULL, NULL, 'ddod', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', NULL, '3.4536466999999997', '-76.5299854', '2020-07-09', '08:00:00', NULL, 'Maletin', 'inicio', 'transferencia', 0, NULL, 5000, 0, 5000, NULL, 2, 2, 1, '2020-07-09 00:56:01', '2020-09-26 00:49:55'),
(16, 4, NULL, NULL, 'alexis valera', '0436', 'dodo', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', 'dodo', '3.4516467', '-76.5319854', NULL, 'dodo', '324', 'dodod', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'dodo', '3.4536466999999997', '-76.5299854', '2020-07-09', '08:01:00', 'ff', 'Maletin', 'inicio', 'transferencia', 0, NULL, 5000, 0, 5000, NULL, 12, 4, 1, '2020-07-09 01:02:29', '2020-07-16 22:55:06'),
(17, 0, 'dede', NULL, NULL, NULL, 'de', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', NULL, '3.4516467', '-76.5319854', NULL, NULL, NULL, 'de', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', NULL, '3.4536466999999997', '-76.5299854', '2020-07-09', '08:12:00', NULL, 'Maletin; MB', 'inicio', 'reembolso', 0, 1000, 5000, 0, 6000, NULL, 12, 4, 1, '2020-07-09 01:13:39', '2020-07-16 12:40:08'),
(18, 0, 'doodod', NULL, NULL, NULL, 'fofof', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', NULL, '3.4516467', '-76.5319854', NULL, NULL, NULL, 'fofof', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', NULL, '3.4536466999999997', '-76.5299854', '2020-07-09', '08:29:00', NULL, 'Maletin; MB', 'inicio', 'transferencia', 0, NULL, 5000, 0, 5000, NULL, NULL, 5, 1, '2020-07-09 01:30:31', '2020-07-09 13:30:03'),
(20, 4, NULL, NULL, 'dood', '0436', 'dodo', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', 'dkkds', '3.4516467', '-76.5319854', NULL, 'dodo', '324', 'dodod', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'jjd', '3.4536466999999997', '-76.5299854', '2020-07-10', '08:07:00', 'dd', 'Maletin', 'inicio', 'transferencia', 0, NULL, 5000, 0, 5000, NULL, 12, 4, 1, '2020-07-10 00:10:10', '2020-07-16 12:47:56'),
(21, 20, NULL, NULL, 'Felipe Echeverry', '3193144437', 'Napoles', 'Carrera 77A #3A-41 cali', 'Carrera 77A #3A-41', '3.3878138', '-76.5478864', NULL, 'Santiago Ocampo', '3183703025', 'El lido', 'Carrera 47 #1-70 cali', 'Carrera 47 #1-70', '3.4187535', '-76.5529631', '2020-07-10', '08:00:00', 'Celulares', 'Maletin; MB', 'final', 'transferencia', 0, 50000, 6000, 0, 6000, NULL, 12, 4, 1, '2020-07-10 00:59:10', '2020-07-10 01:08:54'),
(22, 20, NULL, NULL, 'Felipe Echeverry', '3193144437', 'Napoles', 'Carrera 77A #3A-41 cali', 'Carrera 77A #3A-41', '3.3878138', '-76.5478864', NULL, 'Marta Echeverry', '3183705678', 'Antonio Nariño', 'calle 38 #38-19 cali', 'Calle 38 #38-19', '3.4187945', '-76.50689349999999', '2020-07-10', '08:00:00', 'Celular', 'Maletin; MB', 'inicio', 'efectivo', 0, 50000, 7000, 0, 7000, NULL, 12, 5, 1, '2020-07-10 01:04:24', '2020-07-15 05:12:01'),
(23, 0, 'doodod', NULL, NULL, NULL, 'ccc', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', NULL, '3.4516467', '-76.5319854', NULL, NULL, NULL, 'ddd', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', NULL, '3.4536466999999997', '-76.5299854', '2020-07-10', '08:04:00', 'nnxn', 'Maletin', 'inicio', 'reembolso', 0, 1000, 5000, 0, 6000, NULL, 12, 4, 1, '2020-07-10 01:05:07', '2020-07-10 01:13:57'),
(24, 0, 'vv', NULL, 'a', '0436', 'iiu', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', NULL, '3.4516467', '-76.5319854', NULL, NULL, NULL, 'iui', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', NULL, '3.4536466999999997', '-76.5299854', '2020-07-16', '12:28:00', NULL, 'Maletin', 'inicio', 'reembolso', 0, 12000, 5000, 0, 17000, NULL, 12, 4, 1, '2020-07-16 12:29:08', '2020-07-16 12:40:36'),
(25, 4, NULL, NULL, 'dood', '0436', 'dodo', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', 'dkkds', '3.4516467', '-76.5319854', NULL, 'dodo', '324', 'dodod', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'jjd', '3.4536466999999997', '-76.5299854', '2020-07-16', '19:00:00', 'varios', 'Maletin', 'inicio', 'reembolso', 0, 10000, 5000, 0, 15000, NULL, 2, 5, 1, '2020-07-16 20:51:39', '2020-08-11 00:25:38'),
(26, 16, NULL, NULL, 'Felipe Echeverry', '3193144437', 'Napoles', 'Dikey Store', 'Carrera 77A #3A-41', '3.4125821', '-76.5444933', NULL, 'Santiago Ocampo', '3183703025', 'El lido', 'Carrera 77a #3a-41 cali', 'Carrera 47 #1-70', '3.3878138', '-76.5478864', '2020-07-16', '17:04:00', 'Celular', 'Maletin; MB', 'inicio', 'reembolso', 0, 50000, 5000, 3500, 58500, NULL, 2, 4, 1, '2020-07-16 21:07:02', '2020-08-11 01:29:19'),
(27, 0, 'LENA REPOSTERIA', NULL, 'Vanessa Lasso', '3156678400', 'Melendez', 'cra 92 #3a-30 cali', 'carrera 92# 3a-30  Unidad residencial horizontes b bloque 8 apto 101', '3.3772027', '-76.5481741', NULL, 'Jorge Arce', '320 7906672', 'El Ingenio', 'Cra. 83a ##16-31, Cali, Valle del Cauca, Colombia', 'Carrera 83a #16-31 apto 505', '3.385219', '-76.5280238', '2020-07-16', '17:40:00', 'masa lista  para pandebono 3 unidades', 'MB; Canasta', 'inicio', 'efectivo', 0, 0, 6000, 0, 6000, '#TodasMienten', 12, 4, 1, '2020-07-16 22:10:03', '2020-07-16 23:43:46'),
(28, 0, 'kk', NULL, NULL, NULL, 'k', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', NULL, '3.4516467', '-76.5319854', NULL, NULL, NULL, 'dodod', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', NULL, '3.4536466999999997', '-76.5299854', '2020-07-27', '08:47:00', NULL, 'Maletin', 'inicio', 'transferencia', 0, 0, 5000, 0, 5000, NULL, 12, 4, 1, '2020-07-26 23:48:25', '2020-08-27 20:17:33'),
(29, 4, NULL, NULL, NULL, NULL, 'prueba', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', NULL, '3.4516467', '-76.5319854', NULL, NULL, NULL, 'prueba', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', NULL, '3.4536466999999997', '-76.5299854', '2020-08-12', '00:34:00', NULL, 'Maletin', 'inicio', 'transferencia', 0, 0, 5000, 0, 5000, NULL, NULL, 0, 1, '2020-08-12 00:35:18', '2020-08-12 00:35:18'),
(32, 1, NULL, NULL, 'Luisana', '04266379981', 'juan griego', 'Cl 14 Oe con Cl 13 Oe_2, Cali, Valle del Cauca, Colombia', 'Juan Griego', '3.4435932169730243', '-76.5536147334961', NULL, 'Alexander', '04166961508', 'el valle', 'Cra. 7u ##61-30, Cali, Valle del Cauca, Colombia', 'El valle', '3.454674797139593', '-76.49084660605467', '2020-10-02', '16:33:00', 'bicicleta', 'Maletin', 'inicio', 'transferencia', 0, 0, 8000, 0, 8000, NULL, NULL, 0, 0, '2020-09-25 19:34:35', '2020-09-25 20:36:03'),
(33, 0, 'sdf', NULL, 'Luisana Marin', '4266379981', NULL, 'Cl 14 Oe con Cl 13 Oe_2, Cali, Valle del Cauca, Colombia', 'Juan Griego', NULL, NULL, NULL, 'Alexander Marin', '4123572602', NULL, 'Cra. 7u ##61-30, Cali, Valle del Cauca, Colombia', 'El valle', NULL, NULL, '2020-10-06', '10:21:00', 'perrito', 'Maletin', 'inicio', 'transferencia', 0, 0, 0, 0, NULL, NULL, 10, 1, 1, '2020-10-06 02:24:06', '2020-10-06 03:07:29'),
(34, 0, 'sdf', NULL, 'luisana marin', '1234567890', NULL, 'juan griego', NULL, NULL, NULL, NULL, 'Alexander Marin', '4123572602', NULL, 'sdsfd', NULL, NULL, NULL, '2020-10-06', '10:44:00', 'perrito', 'Maletin', 'inicio', 'efectivo', 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, '2020-10-06 02:50:59', '2020-10-06 02:50:59'),
(35, 0, 'sdf', 'luisana marn 4120924871', NULL, NULL, NULL, 'juan griego', 'juan griego', NULL, NULL, 'freddy 4120924853', NULL, NULL, NULL, 'sdsfd', 'sdsfd', NULL, NULL, '2020-10-08', '10:52:00', 'documentos', 'Maletin', NULL, 'transferencia', 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, '2020-10-08 23:54:40', '2020-10-08 23:54:40'),
(36, 0, 'sdf', 'luisana marn 4120924871', NULL, NULL, NULL, 'juan griego', 'juan griego', NULL, NULL, 'freddy 4120924853', NULL, NULL, NULL, 'sdsfd', 'sdsfd', NULL, NULL, '2020-10-14', '18:26:00', 'perrito', 'Maletin', NULL, 'transferencia', 0, 0, 0, 0, NULL, NULL, NULL, 5, 1, '2020-10-12 18:26:48', '2020-10-12 18:52:17'),
(37, 0, 'prueba', 'luisana 4120924871', NULL, NULL, NULL, 'juan griego', 'juan griego', NULL, NULL, 'freddy jose 4120924853', NULL, NULL, NULL, 'prueba', 'prueba', NULL, NULL, '2020-10-14', '18:28:00', 'documentos', 'Maletin', NULL, 'transferencia', 0, 0, 0, 0, NULL, NULL, 10, 1, 1, '2020-10-12 18:28:32', '2020-10-12 18:52:30'),
(38, 0, 'sdf', 'luisana 4120924871', NULL, NULL, 'juan griego', 'juan griego', 'juan griego', NULL, NULL, 'freddy 4120924853', NULL, NULL, 'sdsfd', 'sdsfd', 'sdsfd', NULL, NULL, '2020-10-21', '18:02:00', 'documentos', 'Maletin; MB; Canasta', NULL, 'transferencia', 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, '2020-10-12 19:02:29', '2020-10-12 19:02:29'),
(39, 0, 'sdf', 'luisana marn 4120924871', NULL, NULL, 'juan griego 1', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', 'Juan Griego', '3.4516467', '-76.5319854', 'freddy 4120924853', NULL, NULL, 'el valle', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'El valle', '3.4536466999999997', '-76.5299854', '2020-10-21', '15:37:00', 'documentos', 'Maletin', 'inicio', 'transferencia', 0, 0, 5000, 0, 5000, NULL, 2, 0, 1, '2020-10-15 15:38:28', '2020-10-21 18:40:26'),
(40, 1, NULL, 'luisana marn 4120924871', NULL, NULL, 'juan griego 2', 'Cra. 5 #12-4, Cali, Valle del Cauca, Colombia', 'Juan Griego', '3.4516467', '-76.5319854', 'freddy 4120924853', NULL, NULL, 'el valle', 'Cra. 4 ##14-69, Cali, Valle del Cauca, Colombia', 'El valle', '3.4536466999999997', '-76.5299854', '2020-10-21', '15:38:00', 'documentos', 'Maletin', 'inicio', 'transferencia', 0, 0, 5000, 0, 5000, NULL, 2, 0, 1, '2020-10-15 15:39:14', '2020-10-21 18:40:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfers`
--

CREATE TABLE `transfers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `brever_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `transfers`
--

INSERT INTO `transfers` (`id`, `user_id`, `brever_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 12, 10, 10000, '2020-06-30 02:32:12', '2020-06-30 02:32:12'),
(2, 12, 10, 10000, '2020-07-16 12:51:04', '2020-07-16 12:51:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tradename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_plate` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Cliente. 2 = Brever. 3 = Administrador',
  `vip` tinyint(1) NOT NULL DEFAULT 0 COMMENT '	Solo aplica para los Brevers',
  `services` tinyint(1) DEFAULT 0,
  `users` tinyint(1) DEFAULT 0,
  `financial` tinyint(1) DEFAULT 0,
  `balance` double NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = Inactivo. 1 = Activo. 2 = Esperando Confirmación Admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `tradename`, `email`, `email_verified_at`, `phone`, `license_plate`, `password`, `avatar`, `role_id`, `vip`, `services`, `users`, `financial`, `balance`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(0, 'Administrador', NULL, 'admin@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$ro.mEt1rCBoOSQdks6tz.ue.WaZIEFJhDXBZFdTiXfZx7C0/8Txeq', NULL, 3, 0, 1, 1, 1, 0, 1, NULL, '2020-05-12 05:59:42', '2020-05-12 05:59:42'),
(1, 'luisana', NULL, 'luisanaelenamarin@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$ro.mEt1rCBoOSQdks6tz.ue.WaZIEFJhDXBZFdTiXfZx7C0/8Txeq', '1593455785green-dot.png', 1, 0, 0, 0, 0, 0, 1, NULL, '2020-05-07 23:12:35', '2020-06-29 23:36:25'),
(2, 'Luisana Marín', NULL, 'lvmb29@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$ro.mEt1rCBoOSQdks6tz.ue.WaZIEFJhDXBZFdTiXfZx7C0/8Txeq', NULL, 2, 1, 0, 0, 0, -2500, 1, NULL, '2020-05-12 05:55:38', '2020-08-31 21:45:50'),
(4, 'alexis valera', NULL, 'alexisjos@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$6reYOIbGvqsagb69EtQSHurlMZFbPT2Bj/OqhtsWbE1DjpqxUdvoW', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-05-12 06:45:41', '2020-07-17 01:47:22'),
(5, 'Santiago Ocampo', NULL, 'breveprueba@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$FrTJgImVPyZC.TH84mm3v.AwBXOxWzPvfBk1DZSEe7qSmevhwlIji', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-05-12 07:31:51', '2020-05-12 07:31:51'),
(6, 'Santiago Ocampo', NULL, 'latino_ocampo@hotmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$CDc52N4mzh5VrcdXZ4N2U.lc0wa8xYJL.wFAfTLS8FuHBivIYbkjK', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-05-13 09:09:59', '2020-05-13 09:09:59'),
(7, 'Alexisvalera', NULL, 'alexisjoseva95@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$02jGZE5BPc5T1bOhBFDjseiO9HOz09BtOqJeNFnLdrTrv/1e02HYe', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-05-15 01:51:16', '2020-05-15 01:51:16'),
(8, 'Alexisvalera', NULL, 'alexisjose95@hotmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$PhlTUcZpWnwXER.pLPpGb.n.2boVxIsf/lLvITz0UDYUSI.dcjBha', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-05-15 04:48:05', '2020-05-15 04:48:05'),
(9, 'Alexis Valera', NULL, 'Alexis@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$s9A.OvkbWPQHBF4oHFk7se0g4psWCwoM2VpVPCRjz.tCXnJGvB0E2', NULL, 1, 0, 1, 0, 0, 0, 1, NULL, '2020-05-15 05:08:31', '2020-05-19 08:07:08'),
(10, 'Brever uno', NULL, 'brever@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$bwAULWl0HqGcflaxQfirZOiBL5zEpfSZVZhSwtHbpEEuACfdoKYra', NULL, 2, 0, 0, 0, 0, 23750, 1, NULL, '2020-05-15 22:31:07', '2020-08-08 05:09:54'),
(11, 'Eduar jose', NULL, 'eduar@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$RKqUYSk.RtkAWx79TDbt2uqBKQs.kkoNPuqteooIC0E4QPjdV4wCu', NULL, 3, 0, 1, 1, 1, 0, 0, NULL, '2020-05-25 04:26:47', '2020-08-08 05:09:16'),
(12, 'Felipe Echeverry', NULL, 'djwarcolombia@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$mjRyV8rRt0UegIc.eXQX8.J6YLoaaIPVjbpzg6ZPvYnF0QsMI17q2', '1593484427IMG_20180707_210456.jpg', 2, 1, 0, 0, 0, 12250, 1, NULL, '2020-05-26 21:19:48', '2020-08-28 00:17:33'),
(15, 'cliente nova', 'PAnaderia santa fe', 'cliente@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$Zh/bNseIo.uyCicToy515ed2gPYSqn0xKycWEt.le1rsSapLgxImy', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-06-01 20:45:17', '2020-06-01 20:45:17'),
(16, 'Alexisvalera', 'PAnaderia santa fe', 'adminq@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$XNk3BwbSZf1d7FDK8hcXPOqzNmNFbJU7yu/E7x0aIJxZxAtAQHCSe', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-06-03 17:44:48', '2020-06-03 17:44:48'),
(17, 'Santiago Prueba', NULL, 'prueba@gmail.com', '2020-06-22 15:13:00', '3183703025', 'SEE06E', '$2y$10$su3jApQWzIeUcxA6hhoN..4IBVaITZNf5J9Pd22dSSg/7VcVB5g82', '1593967764image.jpg', 2, 1, 0, 0, 0, -1250, 1, NULL, '2020-06-10 00:14:09', '2020-07-06 01:38:49'),
(18, 'Santiago Ocampo', 'Santiago Ocampo', 'latino-ocampo@hotmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$hMkN9xpr7x0CpejmFbVw8.UxgBU0lZtGgkJBnuBYj.is8su8S1n8a', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-06-10 00:16:54', '2020-06-10 00:16:54'),
(19, 'alexis valera', 'valdusoft', 'alexisjoseva95@valdusoft.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$PnTCQqZIdVS4ERqYiOSXN.wmCRknj7EFGUl8FeRXfMMRqpYqdbexC', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-06-10 00:17:18', '2020-06-10 00:17:18'),
(20, 'Felipe Echeverry2', 'DW STORE', 'prueba2@gmail.com', '2020-06-22 15:13:00', '3136993851', NULL, '$2y$10$/YoDtsSJMTUDc.wZ.WCU4u6ep1ujc4fUPYE6eUSbFI1sJ1g0azrYq', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-06-10 00:18:14', '2020-06-10 03:20:29'),
(21, 'Nova', 'PAnaderia santa', 'admin2@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$NAWd3sfjNIyl9U89/g1yJOuwrOhe23DlNffC4lmYRrX3Uiy1EkOeK', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-06-10 00:22:11', '2020-06-10 00:22:11'),
(22, 'Moderador 1', NULL, 'moderador@breve.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$htX4uDNnx/B2ORtesl4p3ux57Q2WuWEz8DFY5u9/kKJgvUh9/384u', NULL, 3, 0, 1, 0, 0, 0, 1, NULL, '2020-06-11 22:36:29', '2020-06-11 22:36:29'),
(23, '67', NULL, 'admin67@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$/N44vQhW7AlBIU/S3KV6Ou/h5KM5GtVXfLFGB0rYD9qJln5nz6m8i', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-06-30 03:53:54', '2020-08-08 05:09:40'),
(24, 'Prueba6', NULL, 'brevediligencias@gmail.com', '2020-06-22 15:13:00', NULL, NULL, '$2y$10$1M5/JiU8EGUWtMiAkBrLROzIl/LbMKRuGhYD0ic55Hyub5V41NG6u', NULL, 2, 0, 0, 0, 0, 0, 1, NULL, '2020-07-17 01:54:27', '2020-07-17 01:56:33'),
(26, 'prueba', 'prueba', 'pruebaprueba@hotmail.com', NULL, NULL, NULL, '$2y$10$4gcsP/3XTKX3Xh93XsTOneK3r1eXFGleDHkfH5MLoGa5v6aYmrde6', NULL, 1, 0, 0, 0, 0, 0, 1, NULL, '2020-08-28 00:03:21', '2020-08-28 00:10:35');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `balances`
--
ALTER TABLE `balances`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `remember_data`
--
ALTER TABLE `remember_data`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `balances`
--
ALTER TABLE `balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT de la tabla `remember_data`
--
ALTER TABLE `remember_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
