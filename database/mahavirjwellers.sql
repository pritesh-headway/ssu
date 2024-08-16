-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2024 at 07:02 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mahavirjwellers`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign_coupons`
--

CREATE TABLE `assign_coupons` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'seller_id',
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(200) NOT NULL,
  `phone_number` varchar(12) DEFAULT NULL,
  `coupon_format` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 for single, 2 for range, 3 for multiple',
  `coupon_number` varchar(255) DEFAULT NULL,
  `coupon_range_from` varchar(255) DEFAULT NULL,
  `coupon_range_to` varchar(255) DEFAULT NULL,
  `coupon_count` varchar(200) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `assign_coupons`
--

INSERT INTO `assign_coupons` (`id`, `coupon_id`, `user_id`, `customer_id`, `customer_name`, `phone_number`, `coupon_format`, `coupon_number`, `coupon_range_from`, `coupon_range_to`, `coupon_count`, `city`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 15, NULL, 'lala Patil', '7856525452', 1, '#000230', NULL, NULL, NULL, 'NK', 1, '2024-07-23 09:23:07', '2024-07-23 09:23:07');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `banner_name` varchar(150) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `banner_type` tinyint(4) NOT NULL DEFAULT 2 COMMENT '1 for desktop, 2 for mobile',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `banner_name`, `image`, `status`, `banner_type`, `created_at`, `updated_at`) VALUES
(1, 'Qui officia numquam et sit.', '20240719182145.jpg', 1, 2, NULL, '2024-07-19 12:51:45'),
(2, 'Maiores eos consequatur asperiores occaecati nam quisquam quo minima.', NULL, 1, 2, NULL, NULL),
(3, 'Sed qui at facilis aperiam.', NULL, 1, 2, NULL, NULL),
(4, 'Sunt ea dolor vero itaque reprehenderit nesciunt.', NULL, 1, 2, NULL, NULL),
(5, 'Quisquam qui consequuntur explicabo sed sint in.', NULL, 1, 2, NULL, NULL),
(6, 'Omnis recusandae animi provident nulla.', NULL, 1, 2, NULL, NULL),
(7, 'Temporibus voluptas ut voluptas tempore.', NULL, 1, 2, NULL, NULL),
(8, 'Id natus accusamus sequi pariatur quos numquam ut.', NULL, 1, 2, NULL, NULL),
(9, 'Corrupti odio inventore velit.', NULL, 1, 2, NULL, NULL),
(10, 'Eum atque saepe rerum optio quisquam iste.', NULL, 1, 2, NULL, NULL),
(11, 'Eum aut placeat nostrum autem maiores illum.', NULL, 1, 2, NULL, NULL),
(12, 'Quod facilis itaque est recusandae exercitationem adipisci alias.', NULL, 1, 2, NULL, NULL),
(13, 'Iure omnis qui nesciunt fugit.', NULL, 1, 2, NULL, NULL),
(14, 'Aperiam laudantium distinctio sed iusto velit.', NULL, 1, 2, NULL, NULL),
(15, 'Blanditiis asperiores rerum assumenda mollitia.', NULL, 1, 2, NULL, NULL),
(16, 'Temporibus asperiores mollitia odio aut repudiandae corrupti reprehenderit nemo.', NULL, 1, 2, NULL, NULL),
(17, 'Ea error quod error qui voluptatem excepturi.', NULL, 1, 2, NULL, NULL),
(18, 'Consequatur quibusdam repudiandae ipsum esse.', NULL, 1, 2, NULL, NULL),
(19, 'Quidem rerum quisquam consequatur laboriosam delectus.', NULL, 1, 2, NULL, NULL),
(20, 'Quis sit omnis beatae deleniti.', NULL, 1, 2, NULL, NULL),
(21, 'Corporis recusandae blanditiis et ducimus.', NULL, 1, 2, NULL, NULL),
(22, 'Et animi est iusto labore id voluptate autem.', NULL, 1, 2, NULL, NULL),
(23, 'Ex enim dicta cum voluptas non beatae sit similique.', NULL, 1, 2, NULL, NULL),
(24, 'Veritatis et corporis ut.', NULL, 1, 2, NULL, NULL),
(25, 'Sit illum vel omnis ea doloremque itaque.', NULL, 1, 2, NULL, NULL),
(26, 'Doloremque nulla aperiam ut et neque et vero dicta.', NULL, 1, 2, NULL, NULL),
(27, 'Est minima officiis architecto et nobis ex repudiandae.', NULL, 1, 2, NULL, NULL),
(28, 'Et a quia ea omnis.', NULL, 1, 2, NULL, NULL),
(29, 'In ipsum dolor est rerum tenetur.', NULL, 1, 2, NULL, NULL),
(30, 'Ut culpa nihil neque nihil ullam mollitia.', NULL, 1, 2, NULL, NULL),
(31, 'Provident ratione deleniti cumque voluptatem veritatis.', NULL, 1, 2, NULL, NULL),
(32, 'Fugiat magnam sequi aut numquam.', NULL, 1, 2, NULL, NULL),
(33, 'Totam ea rerum dolores rerum consequatur fuga magni.', NULL, 1, 2, NULL, NULL),
(34, 'Aut velit ea asperiores.', NULL, 1, 2, NULL, NULL),
(35, 'Voluptatem sunt modi cum facere aut sint praesentium.', NULL, 1, 2, NULL, NULL),
(36, 'Sed facilis voluptas dolores ducimus.', NULL, 1, 2, NULL, NULL),
(37, 'Aut reiciendis iste qui ipsa officia ut dignissimos iste.', NULL, 1, 2, NULL, NULL),
(38, 'Neque porro corporis itaque nulla enim molestias soluta voluptas.', NULL, 1, 2, NULL, NULL),
(39, 'Placeat beatae voluptatem inventore consectetur.', NULL, 1, 2, NULL, NULL),
(40, 'Numquam placeat et eaque voluptates corporis officia quasi.', NULL, 1, 2, NULL, NULL),
(41, 'Quia enim doloribus cupiditate sed repellat.', NULL, 1, 2, NULL, NULL),
(42, 'Est ea nesciunt repudiandae est et.', NULL, 1, 2, NULL, NULL),
(43, 'Reiciendis quia suscipit et est voluptas.', NULL, 1, 2, NULL, NULL),
(44, 'Voluptas ut in veritatis cupiditate vel deleniti.', NULL, 1, 2, NULL, NULL),
(45, 'Ex voluptas quasi magnam quos.', NULL, 1, 2, NULL, NULL),
(46, 'Sunt similique exercitationem dignissimos voluptates.', NULL, 1, 2, NULL, NULL),
(47, 'Vitae et minima est qui.', NULL, 1, 2, NULL, NULL),
(48, 'Corrupti est nisi aut itaque omnis cum.', NULL, 1, 2, NULL, NULL),
(49, 'Delectus officia et fugiat voluptatibus modi.', NULL, 1, 2, NULL, NULL),
(50, 'Quo atque est rerum qui a non.', NULL, 1, 2, NULL, NULL),
(51, 'Aut voluptatem et quo.', NULL, 1, 2, NULL, NULL),
(52, 'Omnis tempora quasi sequi ex quae.', NULL, 1, 2, NULL, NULL),
(53, 'Quo non officia quasi molestias incidunt animi recusandae.', NULL, 1, 2, NULL, NULL),
(54, 'Quia neque quam in laboriosam sequi vel.', NULL, 1, 2, NULL, NULL),
(55, 'Enim mollitia culpa sed nihil omnis asperiores consequatur.', NULL, 1, 2, NULL, NULL),
(56, 'Nam sequi est ducimus sed aut aut.', NULL, 1, 2, NULL, NULL),
(57, 'Placeat et explicabo sit exercitationem et ducimus quis.', NULL, 1, 2, NULL, NULL),
(58, 'Est doloribus dolor voluptas corporis.', NULL, 1, 2, NULL, NULL),
(59, 'Et aut est quibusdam magnam hic aut id.', NULL, 1, 2, NULL, NULL),
(60, 'Voluptatem aperiam quibusdam laudantium.', NULL, 1, 2, NULL, NULL),
(61, 'Aperiam qui adipisci omnis reprehenderit ipsa tempore in quo.', NULL, 1, 2, NULL, NULL),
(62, 'Quam rem deleniti a sint.', NULL, 1, 2, NULL, NULL),
(63, 'Sunt ipsum itaque quis voluptatem id.', NULL, 1, 2, NULL, NULL),
(64, 'Explicabo et quia ipsam illo fuga.', NULL, 1, 2, NULL, NULL),
(65, 'Earum dolorem corrupti sint aut quis ea sunt.', NULL, 1, 2, NULL, NULL),
(66, 'Quam qui enim quia aliquid molestias.', NULL, 1, 2, NULL, NULL),
(67, 'Magnam perspiciatis ut nihil et aut ea.', NULL, 1, 2, NULL, NULL),
(68, 'Quos voluptate nisi excepturi eum.', NULL, 1, 2, NULL, NULL),
(69, 'Iure sit itaque laboriosam facere aut et nihil.', NULL, 1, 2, NULL, NULL),
(70, 'Velit omnis quaerat voluptatum mollitia enim.', NULL, 1, 2, NULL, NULL),
(71, 'Occaecati quibusdam velit earum similique tempore et rerum a.', NULL, 1, 2, NULL, NULL),
(72, 'Amet molestiae voluptatem est et eum consequatur voluptatem.', NULL, 1, 2, NULL, NULL),
(73, 'Dolor sit a dolore nisi hic aut.', NULL, 1, 2, NULL, NULL),
(74, 'Inventore sunt ut explicabo est eum repellat illum.', NULL, 1, 2, NULL, NULL),
(75, 'Eum aut vel saepe maiores corrupti.', NULL, 1, 2, NULL, NULL),
(76, 'Soluta dignissimos voluptatum inventore sint non.', NULL, 1, 2, NULL, NULL),
(77, 'Vel dicta fugit et ipsam dolor aliquid.', NULL, 1, 2, NULL, NULL),
(78, 'Omnis repellendus quia consequuntur voluptatem hic ut aut.', NULL, 1, 2, NULL, NULL),
(79, 'Sit qui alias explicabo sit quaerat sint.', NULL, 1, 2, NULL, NULL),
(80, 'Quo et ipsa magnam quod accusantium explicabo consequatur excepturi.', NULL, 1, 2, NULL, NULL),
(81, 'Nihil odio a temporibus.', NULL, 1, 2, NULL, NULL),
(82, 'Voluptates eum id est in officiis.', NULL, 1, 2, NULL, NULL),
(83, 'Ratione quisquam alias nihil distinctio.', NULL, 1, 2, NULL, NULL),
(84, 'Et at atque perspiciatis voluptatum similique aliquam deserunt.', NULL, 1, 2, NULL, NULL),
(85, 'Ratione et non id est sint.', NULL, 1, 2, NULL, NULL),
(86, 'Itaque dicta natus vel at recusandae dignissimos.', NULL, 1, 2, NULL, NULL),
(87, 'Ut error earum reprehenderit ad minus.', NULL, 1, 2, NULL, NULL),
(88, 'Animi recusandae est sequi alias.', NULL, 1, 2, NULL, NULL),
(89, 'Dolor distinctio quia et accusamus.', NULL, 1, 2, NULL, NULL),
(90, 'Qui reiciendis quos non aliquid incidunt.', NULL, 1, 2, NULL, NULL),
(91, 'A dolor aliquid repellat minima minus.', NULL, 1, 2, NULL, NULL),
(92, 'Dolor eos quis rerum non a libero harum sunt.', NULL, 1, 2, NULL, NULL),
(93, 'Est fugiat dicta et.', NULL, 1, 2, NULL, NULL),
(94, 'Accusamus in ut dolorum voluptatem nulla doloremque.', NULL, 1, 2, NULL, NULL),
(95, 'Reiciendis animi omnis laudantium dolores velit.', NULL, 1, 2, NULL, NULL),
(96, 'Autem autem et dignissimos est et occaecati ratione.', NULL, 1, 2, NULL, NULL),
(97, 'Ut et debitis cumque numquam assumenda.', NULL, 1, 2, NULL, NULL),
(98, 'Accusantium asperiores eos tempora reprehenderit nihil neque.', NULL, 1, 2, NULL, NULL),
(99, 'Nam temporibus omnis nobis corrupti vel.', NULL, 1, 2, NULL, NULL),
(100, 'Delectus et laborum ad iste inventore.', NULL, 1, 2, NULL, NULL),
(101, 'Modi illo quo nostrum corrupti.', NULL, 1, 2, NULL, NULL),
(102, 'Perferendis dolore dolore in explicabo vel.', NULL, 1, 2, NULL, NULL),
(103, 'Ratione quisquam excepturi magnam consequatur libero eos et.', NULL, 1, 2, NULL, NULL),
(104, 'Sint labore inventore et fugiat vel asperiores.', NULL, 1, 2, NULL, NULL),
(105, 'In quod voluptatibus magni praesentium autem dignissimos omnis.', NULL, 1, 2, NULL, NULL),
(106, 'Dolores error enim magnam rerum.', NULL, 1, 2, NULL, NULL),
(107, 'Numquam non cupiditate similique esse quia ut.', NULL, 1, 2, NULL, NULL),
(108, 'Molestiae consequatur autem aut natus sunt ut.', NULL, 1, 2, NULL, NULL),
(109, 'Rerum eius officiis hic est aut sequi est.', NULL, 1, 2, NULL, NULL),
(110, 'Reprehenderit inventore inventore est assumenda error velit et dolor.', NULL, 1, 2, NULL, NULL),
(111, 'Autem aperiam nobis aliquam animi saepe.', NULL, 1, 2, NULL, NULL),
(112, 'Et aut nobis eos occaecati et est voluptas.', NULL, 1, 2, NULL, NULL),
(113, 'Quasi sit qui veritatis veritatis voluptatum.', NULL, 1, 2, NULL, NULL),
(114, 'Labore natus ipsa qui et nostrum perferendis nihil.', NULL, 1, 2, NULL, NULL),
(115, 'Est aliquid voluptatem dolorem aliquid.', NULL, 1, 2, NULL, NULL),
(116, 'Aut possimus est qui.', NULL, 1, 2, NULL, NULL),
(117, 'Eos rerum id quo at.', NULL, 1, 2, NULL, NULL),
(118, 'Modi dolore voluptatem distinctio voluptatem et.', NULL, 1, 2, NULL, NULL),
(119, 'Sed qui vel doloribus.', NULL, 1, 2, NULL, NULL),
(120, 'Eaque facere repellat blanditiis voluptas animi.', NULL, 1, 2, NULL, NULL),
(121, 'Vitae ut quis dolorem tempora ex doloribus et.', NULL, 1, 2, NULL, NULL),
(122, 'Qui quasi quia esse est.', NULL, 1, 2, NULL, NULL),
(123, 'Perferendis enim nihil rerum quo recusandae at libero.', NULL, 1, 2, NULL, NULL),
(124, 'Facere fugiat error soluta et esse omnis aspernatur.', NULL, 1, 2, NULL, NULL),
(125, 'Voluptatem ea impedit pariatur qui ab.', NULL, 1, 2, NULL, NULL),
(126, 'Sit libero repellendus exercitationem optio qui commodi quaerat adipisci.', NULL, 1, 2, NULL, NULL),
(127, 'Illo voluptatem sunt modi nihil.', NULL, 1, 2, NULL, NULL),
(128, 'Sunt reiciendis iste enim iure.', NULL, 1, 2, NULL, NULL),
(129, 'Aut quibusdam autem quia aut laborum.', NULL, 1, 2, NULL, NULL),
(130, 'Tempora eius sint voluptatem dolores autem officia.', NULL, 1, 2, NULL, NULL),
(131, 'Atque id impedit asperiores ea.', NULL, 1, 2, NULL, NULL),
(132, 'Quo sunt qui officiis cum modi quam.', NULL, 1, 2, NULL, NULL),
(133, 'Pariatur dignissimos aliquam similique repellendus.', NULL, 1, 2, NULL, NULL),
(134, 'Distinctio id natus similique est.', NULL, 1, 2, NULL, NULL),
(135, 'Maxime laudantium neque quibusdam eveniet ut perspiciatis.', NULL, 1, 2, NULL, NULL),
(136, 'Sapiente maiores est eos cupiditate quos ad tempora.', NULL, 1, 2, NULL, NULL),
(137, 'Harum iure repellat rerum numquam quae modi.', NULL, 1, 2, NULL, NULL),
(138, 'In voluptate id quaerat quis saepe incidunt vel.', NULL, 1, 2, NULL, NULL),
(139, 'Reiciendis ducimus veniam sed et magni.', NULL, 1, 2, NULL, NULL),
(140, 'Laborum ut animi qui non voluptate veniam.', NULL, 1, 2, NULL, NULL),
(141, 'Quaerat harum iste placeat libero provident.', NULL, 1, 2, NULL, NULL),
(142, 'A alias quasi minus aut rerum dolorem.', NULL, 1, 2, NULL, NULL),
(143, 'Quaerat minima delectus minima optio neque mollitia.', NULL, 1, 2, NULL, NULL),
(144, 'Voluptatem dolorem est totam aperiam id dolor optio consequatur.', NULL, 1, 2, NULL, NULL),
(145, 'Assumenda aliquam accusantium molestiae similique molestiae officia.', NULL, 1, 2, NULL, NULL),
(146, 'Odio et eum sint est et non dolorem.', NULL, 1, 2, NULL, NULL),
(147, 'Fugiat id fuga possimus sit in ex inventore iste.', NULL, 1, 2, NULL, NULL),
(148, 'Dolorum dolorem sint iste voluptatem excepturi voluptatum omnis.', NULL, 1, 2, NULL, NULL),
(149, 'Quisquam necessitatibus in ex architecto fugit optio.', NULL, 1, 2, NULL, NULL),
(150, 'Et et voluptas qui qui repellendus.', NULL, 1, 2, NULL, NULL),
(151, 'Reiciendis quasi commodi et esse est quod aut.', NULL, 1, 2, NULL, NULL),
(152, 'Fugit nostrum quis aperiam est aut nemo dolorem.', NULL, 1, 2, NULL, NULL),
(153, 'Officia ab et sed sit.', NULL, 1, 2, NULL, NULL),
(154, 'Et occaecati nulla aliquid rerum sunt.', NULL, 1, 2, NULL, NULL),
(155, 'Quidem dolore in atque sint nobis dolorem vel.', NULL, 1, 2, NULL, NULL),
(156, 'Aliquid eveniet labore rerum fugit rerum aliquam.', NULL, 1, 2, NULL, NULL),
(157, 'Quos quia ut quasi autem.', NULL, 1, 2, NULL, NULL),
(158, 'Commodi velit voluptatem nesciunt voluptas optio laudantium dolorem doloremque.', NULL, 1, 2, NULL, NULL),
(159, 'Laudantium ut qui atque cumque.', NULL, 1, 2, NULL, NULL),
(160, 'Consequatur et quisquam aperiam aspernatur.', NULL, 1, 2, NULL, NULL),
(161, 'Non nostrum quod cum ab ullam autem.', NULL, 1, 2, NULL, NULL),
(162, 'Et tempore eligendi neque numquam.', NULL, 1, 2, NULL, NULL),
(163, 'Veniam quia ducimus eos consequatur quis similique qui.', NULL, 1, 2, NULL, NULL),
(164, 'Non blanditiis officia neque ea harum.', NULL, 1, 2, NULL, NULL),
(165, 'Quia sapiente atque enim eum.', NULL, 1, 2, NULL, NULL),
(166, 'Ullam architecto ea esse distinctio.', NULL, 1, 2, NULL, NULL),
(167, 'Rerum quo laborum odit quia quaerat.', NULL, 1, 2, NULL, NULL),
(168, 'Ut voluptas fugiat doloribus incidunt.', NULL, 1, 2, NULL, NULL),
(169, 'Quia ab ratione atque quod nostrum nesciunt deserunt.', NULL, 1, 2, NULL, NULL),
(170, 'Sit laudantium vitae omnis at rerum.', NULL, 1, 2, NULL, NULL),
(171, 'Quia ut fugit sed molestiae omnis.', NULL, 1, 2, NULL, NULL),
(172, 'Est molestiae qui voluptas laudantium ipsam.', NULL, 1, 2, NULL, NULL),
(173, 'Error fuga exercitationem explicabo nostrum ipsum iusto.', NULL, 1, 2, NULL, NULL),
(174, 'Porro totam sit quis est quod quidem.', NULL, 1, 2, NULL, NULL),
(175, 'Eos provident dignissimos quasi reprehenderit eaque.', NULL, 1, 2, NULL, NULL),
(176, 'Quis excepturi corrupti optio dolorum odit atque sunt.', NULL, 1, 2, NULL, NULL),
(177, 'Aut delectus consequuntur dolorem ducimus.', NULL, 1, 2, NULL, NULL),
(178, 'Rerum dolore dicta et esse.', NULL, 1, 2, NULL, NULL),
(179, 'Fuga possimus dolorem ea aliquam error.', NULL, 1, 2, NULL, NULL),
(180, 'Est ex placeat fugit non.', NULL, 1, 2, NULL, NULL),
(181, 'Omnis temporibus dolores officiis rerum voluptatem.', NULL, 1, 2, NULL, NULL),
(182, 'Non temporibus vero reiciendis.', NULL, 1, 2, NULL, NULL),
(183, 'Voluptas totam natus excepturi veritatis consequuntur totam sunt ullam.', NULL, 1, 2, NULL, NULL),
(184, 'Ut enim autem quis omnis magnam eos.', NULL, 1, 2, NULL, NULL),
(185, 'Minima eaque quam quam.', NULL, 1, 2, NULL, NULL),
(186, 'Dolorum repudiandae voluptatem ipsam praesentium qui in.', NULL, 1, 2, NULL, NULL),
(187, 'Rerum numquam architecto dolor vel iste officia.', NULL, 1, 2, NULL, NULL),
(188, 'Et mollitia et magni porro consequuntur beatae.', NULL, 1, 2, NULL, NULL),
(189, 'Ut aliquam voluptate aut harum consequatur deserunt.', NULL, 1, 2, NULL, NULL),
(190, 'Tempore possimus sunt alias voluptatem tempore.', NULL, 1, 2, NULL, NULL),
(191, 'Illum voluptatem aut quis.', NULL, 1, 2, NULL, NULL),
(192, 'Tempore ipsam in inventore blanditiis.', NULL, 1, 2, NULL, NULL),
(193, 'Ullam explicabo error iure quia vel quia cum.', NULL, 1, 2, NULL, NULL),
(194, 'Labore sed porro vero occaecati.', NULL, 1, 2, NULL, NULL),
(195, 'Id cum est et consequatur blanditiis odit.', NULL, 1, 2, NULL, NULL),
(196, 'Adipisci ab consectetur molestiae voluptatum consequatur.', NULL, 1, 2, NULL, NULL),
(197, 'Iste harum nulla impedit nemo.', NULL, 1, 2, NULL, NULL),
(198, 'Tenetur nam ducimus vel velit.', NULL, 1, 2, NULL, NULL),
(199, 'Qui possimus qui rem esse perspiciatis quidem repudiandae.', NULL, 1, 2, NULL, NULL),
(200, 'Laudantium voluptatem et ea ut consequatur.', NULL, 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `coupon_number` varchar(255) NOT NULL,
  `coupon_name` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_number`, `coupon_name`, `status`, `created_at`, `updated_at`) VALUES
(1, '#000100', 'SSU 2023', 1, '2024-07-20 23:29:34', '2024-07-20 23:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `coupons_order`
--

CREATE TABLE `coupons_order` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `receipt_payment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_description` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT ' 1 for desktop, 2 for mobile ',
  `prize` double NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `event_location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_description`, `start_date`, `end_date`, `image`, `image_type`, `prize`, `status`, `event_location`, `created_at`, `updated_at`) VALUES
(1, 'SSU 2023', 'dsfsf sdfsd', '2023-01-01', '2023-01-30', '20240720053947.jpeg', 1, 9999, 1, 'Ahmedabad', '2024-07-18 11:21:15', '2024-07-20 00:10:38'),
(2, 'SSU 2022', NULL, '2022-05-01', '2022-05-28', NULL, 1, 9999, 1, 'Mumbai', '2024-07-18 11:21:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_details`
--

CREATE TABLE `event_details` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event_details`
--

INSERT INTO `event_details` (`id`, `event_id`, `image`, `video`, `created_at`, `updated_at`) VALUES
(1, 1, 'event_images/1.png', 'event_videos/video.mp4', '2024-07-18 16:32:44', NULL),
(2, 1, 'event_images/2.png', 'event_videos/video2.mp4', '2024-07-18 16:33:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_12_17_095139_create_roles_permission_names_table', 1),
(7, '2023_12_17_095257_create_roles_permission_descriptions_table', 1),
(8, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(9, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(10, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(11, '2016_06_01_000004_create_oauth_clients_table', 2),
(12, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('20e96b96e49cf1b335b693de1ad21adce1884827d5518c4e29efc669273f4a33ff5a66510cfba880', 7, 1, 'authToken', '[]', 0, '2024-07-17 11:58:16', '2024-07-17 11:58:16', '2025-07-17 17:28:16');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'eLJmkOpRbxFD2PHatCKARR1QDecMFqgVEni3zufe', NULL, 'http://localhost', 1, 0, 0, '2024-07-17 11:54:46', '2024-07-17 11:54:46'),
(2, NULL, 'Laravel Password Grant Client', 'qGHBZ3acDvwdSw0dL6m0mWMt2LvBMnqY7xyTgPQZ', 'users', 'http://localhost', 0, 1, 0, '2024-07-17 11:54:46', '2024-07-17 11:54:46');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-07-17 11:54:46', '2024-07-17 11:54:46');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 7, 'authToken', 'a84bb8157c23f32ec17fe1720689d58507edfd09c95106376d0f67e534251640', '[\"*\"]', NULL, NULL, '2024-07-17 11:27:09', '2024-07-17 11:27:09'),
(2, 'App\\Models\\User', 7, 'authToken', '9b2c1dbf6fbaffdb70e5922c68ee18c95bc2b76f1121ebf637219d19bb183575', '[\"*\"]', NULL, NULL, '2024-07-17 11:46:54', '2024-07-17 11:46:54'),
(3, 'App\\Models\\User', 7, 'authToken', 'e96776bc6efa00dc17aa7d41ab38800a01c62e4e820f1e1fdd44d96e7992b7cf', '[\"*\"]', NULL, NULL, '2024-07-17 11:58:46', '2024-07-17 11:58:46');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permission_descriptions`
--

CREATE TABLE `roles_permission_descriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles_permission_names`
--

CREATE TABLE `roles_permission_names` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles_permission_names`
--

INSERT INTO `roles_permission_names` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', NULL, NULL),
(2, 'Developer', NULL, NULL),
(3, 'Analyst', NULL, NULL),
(4, 'Support', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` enum('2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '2 for seller, 3 customer',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `storename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `join_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PAN` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `GST` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flatNo` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_step` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `user_type`, `name`, `lname`, `storename`, `email`, `date_of_birth`, `join_date`, `phone_number`, `otp`, `PAN`, `GST`, `flatNo`, `pincode`, `area`, `city`, `state`, `otp_expires_at`, `status`, `two_step`, `last_login`, `role_name`, `avatar`, `position`, `department`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '000001', '2', 'super admin', NULL, NULL, 'super@admin.com', NULL, 'Wed, Jul 17, 2024 3:28 PM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'User Normal', NULL, NULL, NULL, NULL, '$2y$12$.z2AsCGIju0omeh9GQF4EOTUIkKl91PkwS.C3.BT6NJof1L9xcLD.', NULL, '2024-07-17 09:58:26', '2024-07-17 09:58:26'),
(7, '000002', '2', 'Rush', 'one', 'sel store', 'sell@gmail.com', NULL, NULL, '9970831750', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20240721061925.jpg', NULL, NULL, NULL, '$2y$12$2TSAFwM8NmVMu2vLU5Shu.zIRTF/9zBPerRjlwMSPY00FcFYtwJ7.', NULL, '2024-07-17 11:26:12', '2024-07-21 00:49:25'),
(9, '000003', '2', 'shaym', 'patil', 'Demo', 'shyam@gmail.com', NULL, NULL, NULL, NULL, 'BHKKK6524P', '98652547fdfd', 'D-1516 Saujanya Apartment', '380008', 'Khokhra Ahmedabad', 'AH', 'GUJ', NULL, NULL, NULL, NULL, NULL, 'profile_images/ZG0SEA2yiCvj5zhCaCJZnXlsVFan68Gk0D7YPbiE.png', NULL, NULL, NULL, '$2y$12$BvgeQeWz7m848.iW.k.8Be1c8FjZXeUjprJxfvSYrPTmtM6xqnGE.', NULL, '2024-07-18 10:20:45', '2024-07-18 10:31:23'),
(14, '000004', '3', 'Ketan', 'patil', NULL, 'ketan@gmail.com', NULL, NULL, '9865452478', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20240721085045.jpg', NULL, NULL, NULL, '$2y$12$eC.7l3HGJDalJLBXUDexduYkb9RTJ3cVlikmvpIoNq1vxz0HU/iJm', NULL, '2024-07-21 03:16:51', '2024-07-21 03:20:45'),
(15, '000005', '2', 'Yash', 'Agrawal', 'Yash Store', 'yash@gmail.com', NULL, NULL, '8545785652', NULL, NULL, NULL, '15 Karan Society', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$2bum62kuRKtunRdLpO0S9.IjBXuhzLObrOGvxbyOeYJyrxbxsAHN.', NULL, '2024-07-23 08:54:29', '2024-07-23 08:54:29'),
(16, '000006', '3', 'Abhishek', 'patil', 'Abhi Store', 'abhi@gmail.com', NULL, NULL, '8545785650', NULL, NULL, NULL, '15 Karan Society', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$1/01BkpDj/FE/P632Dg0xuNiT5NVLzYiEuweyn1x5PKAbuDL7E6yi', NULL, '2024-07-23 08:58:01', '2024-07-23 08:58:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign_coupons`
--
ALTER TABLE `assign_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons_order`
--
ALTER TABLE `coupons_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_details`
--
ALTER TABLE `event_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles_permission_descriptions`
--
ALTER TABLE `roles_permission_descriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_permission_names`
--
ALTER TABLE `roles_permission_names`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assign_coupons`
--
ALTER TABLE `assign_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupons_order`
--
ALTER TABLE `coupons_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_details`
--
ALTER TABLE `event_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles_permission_descriptions`
--
ALTER TABLE `roles_permission_descriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles_permission_names`
--
ALTER TABLE `roles_permission_names`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
