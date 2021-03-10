-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         5.7.24 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando datos para la tabla api_rest_laravel.categories: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'ordenadores', '2021-01-31 17:14:52', '2021-01-31 17:14:53'),
	(2, 'moviles y tablets', '2021-01-31 17:15:07', '2021-01-31 17:15:07'),
	(3, 'Videojuegos', '2021-03-09 20:49:29', '2021-03-09 21:14:37');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Volcando datos para la tabla api_rest_laravel.posts: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` (`id`, `user_id`, `category_id`, `title`, `content`, `image`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 'venta', 'se venden dispositivos de dudosa procedencia', NULL, '2021-01-31 17:16:15', '2021-01-31 17:16:15'),
	(2, 5, 2, 'Gran torino', 'Contenido del post', 'paralelos.png', '2021-03-10 00:06:50', '2021-03-10 00:18:59');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;

-- Volcando datos para la tabla api_rest_laravel.users: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `surname`, `role`, `email`, `password`, `description`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES
	(1, 'Alfredo', 'alfred', 'Admin', 'test@gmail.com', '12345678', 'administrador', NULL, '2021-01-31 17:15:42', '2021-01-31 17:15:42', NULL),
	(5, 'Julio', 'Ramirez', 'ROLE_USER', 'test1@gmail.com', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', NULL, NULL, '2021-02-18 18:26:04', '2021-02-18 19:52:12', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
