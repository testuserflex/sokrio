-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 15, 2023 at 06:19 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sokrio`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone1` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `currency_code` int DEFAULT NULL,
  `notification_contact` bigint DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '0=inactive 1=active',
  `upload` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=no 1=yes',
  `user_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `email`, `phone1`, `phone2`, `country_id`, `currency_code`, `notification_contact`, `status`, `upload`, `user_id`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 'Main Branch', 'Kyanja', 'test@clinicplusug.com', '0783473203', '0789334988', 235, 235, 774367210, 1, 0, 2, 1, '2022-02-12 08:11:41', '2022-02-16 05:58:39');

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

DROP TABLE IF EXISTS `businesses`;
CREATE TABLE IF NOT EXISTS `businesses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone1` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin` int DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `currency_code` int DEFAULT NULL,
  `store` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=inactive 1=active',
  `status` int NOT NULL DEFAULT '1' COMMENT '0=inactive 1=active',
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `name`, `address`, `website`, `logo`, `email`, `phone1`, `phone2`, `tin`, `country_id`, `currency_code`, `store`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'HENRY TRANSLATION SERVICES', 'Kyanja', 'www.nugsoft.com', NULL, 'test@poscream.com', '0778668767', '0708668767', 888989, 235, 235, 1, 1, 2, '2022-02-09 13:47:03', '2022-02-12 08:52:50');

-- --------------------------------------------------------

--
-- Table structure for table `business_products`
--

DROP TABLE IF EXISTS `business_products`;
CREATE TABLE IF NOT EXISTS `business_products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1=Active 0=deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

DROP TABLE IF EXISTS `business_settings`;
CREATE TABLE IF NOT EXISTS `business_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `store` int NOT NULL DEFAULT '0' COMMENT '0=inactive 1=active',
  `sms_notifications` int NOT NULL DEFAULT '0' COMMENT '0=inactive 1=active',
  `business_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`id`, `store`, `sms_notifications`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 0, 0, 1, '2022-09-20 13:28:58', '2022-09-20 13:28:58');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country_name` varchar(52) DEFAULT NULL,
  `country_code` varchar(17) DEFAULT NULL,
  `dial_code` varchar(17) DEFAULT NULL,
  `currency_code` varchar(32) DEFAULT NULL,
  `currency` varchar(29) DEFAULT NULL,
  `capital_city` varchar(19) DEFAULT NULL,
  `country_dormain` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=250 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_name`, `country_code`, `dial_code`, `currency_code`, `currency`, `capital_city`, `country_dormain`) VALUES
(1, 'Afghanistan', 'AFG', '93', 'AFN', 'Afghani', 'Kabul', '.af'),
(2, 'Albania', 'ALB', '355', 'ALL', 'Lek', 'Tirana', '.al'),
(3, 'Algeria', 'DZA', '213', 'DZD', 'Algerian Dinar', 'Algiers', '.dz'),
(4, 'American Samoa', 'ASM', '1-684', 'USD', 'US Dollar', 'Pago Pago', '.as'),
(5, 'Andorra', 'AND', '376', 'EUR', 'Euro', 'Andorra la Vella', '.ad'),
(6, 'Angola', 'AGO', '244', 'AOA', 'Kwanza', 'Luanda', '.ao'),
(7, 'Anguilla', 'AIA', '1-264', 'XCD', 'East Caribbean Dollar', 'The Valley', '.ai'),
(9, 'Antigua and Barbuda', 'ATG', '1-268', 'XCD', 'East Caribbean Dollar', 'St. John\'s', '.ag'),
(10, 'Argentina', 'ARG', '54', 'ARS', 'Argentine Peso', 'Buenos Aires', '.ar'),
(11, 'Armenia', 'ARM', '374', 'AMD', 'Armenian Dram', 'Yerevan', '.am'),
(12, 'Aruba', 'ABW', '297', 'AWG', 'Aruban Florin', 'Oranjestad', '.aw'),
(13, 'Australia', 'AUS', '61', 'AUD', 'Australian Dollar', 'Canberra', '.au'),
(14, 'Austria', 'AUT', '43', 'EUR', 'Euro', 'Vienna', '.at'),
(15, 'Azerbaijan', 'AZE', '994', 'AZN', 'Azerbaijanian Manat', 'Baku', '.az'),
(16, 'Bahamas', 'BHS', '1-242', 'BSD', 'Bahamian Dollar', 'Nassau', '.bs'),
(17, 'Bahrain', 'BHR', '973', 'BHD', 'Bahraini Dinar', 'Manama', '.bh'),
(18, 'Bangladesh', 'BGD', '880', 'BDT', 'Taka', 'Dhaka', '.bd'),
(19, 'Barbados', 'BRB', '1-246', 'BBD', 'Barbados Dollar', 'Bridgetown', '.bb'),
(20, 'Belarus', 'BLR', '375', 'BYR', 'Belarussian Ruble', 'Minsk', '.by'),
(21, 'Belgium', 'BEL', '32', 'EUR', 'Euro', 'Brussels', '.be'),
(22, 'Belize', 'BLZ', '501', 'BZD', 'Belize Dollar', 'Belmopan', '.bz'),
(23, 'Benin', 'BEN', '229', 'XOF', 'CFA Franc BCEAO', 'Porto-Novo', '.bj'),
(24, 'Bermuda', 'BMU', '1-441', 'BMD', 'Bermudian Dollar', 'Hamilton', '.bm'),
(25, 'Bhutan', 'BTN', '975', 'INR', 'Indian Rupee', 'Thimphu', '.bt'),
(26, 'Bolivia (Plurinational State of)', 'BOL', '591', 'BOB', 'Boliviano', 'Sucre', '.bo'),
(27, 'Bosnia and Herzegovina', 'BIH', '387', 'BAM', 'Convertible Mark', 'Sarajevo', '.ba'),
(28, 'Botswana', 'BWA', '267', 'BWP', 'Pula', 'Gaborone', '.bw'),
(30, 'Brazil', 'BRA', '55', 'BRL', 'Brazilian Real', 'Brasilia', '.br'),
(32, 'British Virgin Islands', 'VGB', '1-284', 'USD', 'US Dollar', 'Road Town', '.vg'),
(33, 'Brunei Darussalam', 'BRN', '673', 'BND', 'Brunei Dollar', 'Bandar Seri Begawan', '.bn'),
(34, 'Bulgaria', 'BGR', '359', 'BGN', 'Bulgarian Lev', 'Sofia', '.bg'),
(35, 'Burkina Faso', 'BFA', '226', 'XOF', 'CFA Franc BCEAO', 'Ouagadougou', '.bf'),
(36, 'Burundi', 'BDI', '257', 'BIF', 'Burundi Franc', 'Bujumbura', '.bi'),
(37, 'Cambodia', 'KHM', '855', 'KHR', 'Riel', 'Phnom Penh', '.kh'),
(38, 'Cameroon', 'CMR', '237', 'XAF', 'CFA Franc BEAC', 'Yaounde', '.cm'),
(39, 'Canada', 'CAN', '1', 'CAD', 'Canadian Dollar', 'Ottawa', '.ca'),
(40, 'Cabo Verde', 'CPV', '238', 'CVE', 'Cabo Verde Escudo', 'Praia', '.cv'),
(41, 'Bonaire, Sint Eustatius and Saba', 'BES', '599', 'USD', 'US Dollar', '', '.bq'),
(42, 'Cayman Islands', 'CYM', '1-345', 'KYD', 'Cayman Islands Dollar', 'George Town', '.ky'),
(43, 'Central African Republic', 'CAF', '236', 'XAF', 'CFA Franc BEAC', 'Bangui', '.cf'),
(44, 'Chad', 'TCD', '235', 'XAF', 'CFA Franc BEAC', 'N\'Djamena', '.td'),
(45, 'Chile', 'CHL', '56', 'CLP', 'Chilean Peso', 'Santiago', '.cl'),
(46, 'China', 'CHN', '86', 'CNY', 'Yuan Renminbi', 'Beijing', '.cn'),
(49, 'Colombia', 'COL', '57', 'COP', 'Colombian Peso', 'Bogota', '.co'),
(50, 'Comoros', 'COM', '269', 'KMF', 'Comoro Franc', 'Moroni', '.km'),
(51, 'Congo', 'COG', '242', 'XAF', 'CFA Franc BEAC', 'Brazzaville', '.cg'),
(52, 'Democratic Republic of the Congo', 'COD', '243', '', '', 'Kinshasa', '.cd'),
(53, 'Cook Islands', 'COK', '682', 'NZD', 'New Zealand Dollar', 'Avarua', '.ck'),
(54, 'Costa Rica', 'CRI', '506', 'CRC', 'Costa Rican Colon', 'San Jose', '.cr'),
(55, 'Croatia', 'HRV', '385', 'HRK', 'Croatian Kuna', 'Zagreb', '.hr'),
(56, 'Cuba', 'CUB', '53', 'CUP', 'Cuban Peso', 'Havana', '.cu'),
(57, 'Curaçao', 'CUW', '599', 'ANG', 'Netherlands Antillean Guilder', 'Willemstad', '.cw'),
(58, 'Cyprus', 'CYP', '357', 'EUR', 'Euro', 'Nicosia', '.cy'),
(59, 'Czechia', 'CZE', '420', '', '', 'Prague', '.cz'),
(60, 'Côte d\'Ivoire', 'CIV', '225', 'XOF', 'CFA Franc BCEAO', 'Yamoussoukro', '.ci'),
(61, 'Denmark', 'DNK', '45', 'DKK', 'Danish Krone', 'Copenhagen', '.dk'),
(62, 'Djibouti', 'DJI', '253', 'DJF', 'Djibouti Franc', 'Djibouti', '.dj'),
(63, 'Dominica', 'DMA', '1-767', 'XCD', 'East Caribbean Dollar', 'Roseau', '.dm'),
(64, 'Dominican Republic', 'DOM', '1-809,1-829,1-849', 'DOP', 'Dominican Peso', 'Santo Domingo', '.do'),
(65, 'Ecuador', 'ECU', '593', 'USD', 'US Dollar', 'Quito', '.ec'),
(66, 'Egypt', 'EGY', '20', 'EGP', 'Egyptian Pound', 'Cairo', '.eg'),
(67, 'El Salvador', 'SLV', '503', 'USD', 'US Dollar', 'San Salvador', '.sv'),
(68, 'Equatorial Guinea', 'GNQ', '240', 'XAF', 'CFA Franc BEAC', 'Malabo', '.gq'),
(69, 'Eritrea', 'ERI', '291', 'ERN', 'Nakfa', 'Asmara', '.er'),
(70, 'Estonia', 'EST', '372', 'EUR', 'Euro', 'Tallinn', '.ee'),
(71, 'Ethiopia', 'ETH', '251', 'ETB', 'Ethiopian Birr', 'Addis Ababa', '.et'),
(72, 'Falkland Islands (Malvinas)', 'FLK', '500', 'FKP', 'Falkland Islands Pound', 'Stanley', '.fk'),
(73, 'Faeroe Islands', 'FRO', '298', '', '', 'Torshavn', '.fo'),
(74, 'Fiji', 'FJI', '679', 'FJD', 'Fiji Dollar', 'Suva', '.fj'),
(75, 'Finland', 'FIN', '358', 'EUR', 'Euro', 'Helsinki', '.fi'),
(76, 'France', 'FRA', '33', 'EUR', 'Euro', 'Paris', '.fr'),
(77, 'French Guiana', 'GUF', '594', 'EUR', 'Euro', 'Cayenne', '.gf'),
(78, 'French Polynesia', 'PYF', '689', 'XPF', 'CFP Franc', 'Papeete', '.pf'),
(80, 'Gabon', 'GAB', '241', 'XAF', 'CFA Franc BEAC', 'Libreville', '.ga'),
(81, 'Gambia', 'GMB', '220', 'GMD', 'Dalasi', 'Banjul', '.gm'),
(82, 'Georgia', 'GEO', '995', 'GEL', 'Lari', 'Tbilisi', '.ge'),
(83, 'Germany', 'DEU', '49', 'EUR', 'Euro', 'Berlin', '.de'),
(84, 'Ghana', 'GHA', '233', 'GHS', 'Ghana Cedi', 'Accra', '.gh'),
(85, 'Gibraltar', 'GIB', '350', 'GIP', 'Gibraltar Pound', 'Gibraltar', '.gi'),
(86, 'Greece', 'GRC', '30', 'EUR', 'Euro', 'Athens', '.gr'),
(87, 'Greenland', 'GRL', '299', 'DKK', 'Danish Krone', 'Nuuk', '.gl'),
(88, 'Grenada', 'GRD', '1-473', 'XCD', 'East Caribbean Dollar', 'St. George\'s', '.gd'),
(89, 'Guadeloupe', 'GLP', '590', 'EUR', 'Euro', 'Basse-Terre', '.gp'),
(90, 'Guam', 'GUM', '1-671', 'USD', 'US Dollar', 'Hagatna', '.gu'),
(91, 'Guatemala', 'GTM', '502', 'GTQ', 'Quetzal', 'Guatemala City', '.gt'),
(92, 'Guernsey', 'GGY', '44', 'GBP', 'Pound Sterling', 'St Peter Port', '.gg'),
(93, 'Guinea', 'GIN', '224', 'GNF', 'Guinea Franc', 'Conakry', '.gn'),
(94, 'Guinea-Bissau', 'GNB', '245', 'XOF', 'CFA Franc BCEAO', 'Bissau', '.gw'),
(95, 'Guyana', 'GUY', '592', 'GYD', 'Guyana Dollar', 'Georgetown', '.gy'),
(96, 'Haiti', 'HTI', '509', 'USD', 'US Dollar', 'Port-au-Prince', '.ht'),
(98, 'Honduras', 'HND', '504', 'HNL', 'Lempira', 'Tegucigalpa', '.hn'),
(99, 'China, Hong Kong Special Administrative Region', 'HKG', '852', '', '', 'Hong Kong', '.hk'),
(100, 'Hungary', 'HUN', '36', 'HUF', 'Forint', 'Budapest', '.hu'),
(101, 'Iceland', 'ISL', '354', 'ISK', 'Iceland Krona', 'Reykjavik', '.is'),
(102, 'India', 'IND', '91', 'INR', 'Indian Rupee', 'New Delhi', '.in'),
(103, 'Indonesia', 'IDN', '62', 'IDR', 'Rupiah', 'Jakarta', '.id'),
(104, 'Iran (Islamic Republic of)', 'IRN', '98', 'IRR', 'Iranian Rial', 'Tehran', '.ir'),
(105, 'Iraq', 'IRQ', '964', 'IQD', 'Iraqi Dinar', 'Baghdad', '.iq'),
(106, 'Ireland', 'IRL', '353', 'EUR', 'Euro', 'Dublin', '.ie'),
(107, 'Isle of Man', 'IMN', '44', 'GBP', 'Pound Sterling', 'Douglas', '.im'),
(108, 'Israel', 'ISR', '972', 'ILS', 'New Israeli Sheqel', 'Jerusalem', '.il'),
(109, 'Italy', 'ITA', '39', 'EUR', 'Euro', 'Rome', '.it'),
(110, 'Jamaica', 'JAM', '1-876', 'JMD', 'Jamaican Dollar', 'Kingston', '.jm'),
(111, 'Japan', 'JPN', '81', 'JPY', 'Yen', 'Tokyo', '.jp'),
(112, 'Jersey', 'JEY', '44', 'GBP', 'Pound Sterling', 'Saint Helier', '.je'),
(113, 'Jordan', 'JOR', '962', 'JOD', 'Jordanian Dinar', 'Amman', '.jo'),
(114, 'Kazakhstan', 'KAZ', '7', 'KZT', 'Tenge', 'Astana', '.kz'),
(115, 'Kenya', 'KEN', '254', 'KES', 'Kenyan Shilling', 'Nairobi', '.ke'),
(116, 'Kiribati', 'KIR', '686', 'AUD', 'Australian Dollar', 'Tarawa', '.ki'),
(117, 'Kuwait', 'KWT', '965', 'KWD', 'Kuwaiti Dinar', 'Kuwait City', '.kw'),
(118, 'Kyrgyzstan', 'KGZ', '996', 'KGS', 'Som', 'Bishkek', '.kg'),
(119, 'Lao People\'s Democratic Republic', 'LAO', '856', 'LAK', 'Kip', 'Vientiane', '.la'),
(120, 'Latvia', 'LVA', '371', 'EUR', 'Euro', 'Riga', '.lv'),
(121, 'Lebanon', 'LBN', '961', 'LBP', 'Lebanese Pound', 'Beirut', '.lb'),
(122, 'Lesotho', 'LSO', '266', 'ZAR', 'Rand', 'Maseru', '.ls'),
(123, 'Liberia', 'LBR', '231', 'LRD', 'Liberian Dollar', 'Monrovia', '.lr'),
(124, 'Libya', 'LBY', '218', 'LYD', 'Libyan Dinar', 'Tripoli', '.ly'),
(125, 'Liechtenstein', 'LIE', '423', 'CHF', 'Swiss Franc', 'Vaduz', '.li'),
(126, 'Lithuania', 'LTU', '370', 'EUR', 'Euro', 'Vilnius', '.lt'),
(127, 'Luxembourg', 'LUX', '352', 'EUR', 'Euro', 'Luxembourg', '.lu'),
(128, 'China, Macao Special Administrative Region', 'MAC', '853', 'MOP', 'Pataca', 'Macao', '.mo'),
(129, 'The former Yugoslav Republic of Macedonia', 'MKD', '389', 'MKD', 'Denar', 'Skopje', '.mk'),
(130, 'Madagascar', 'MDG', '261', 'MGA', 'Malagasy Ariary', 'Antananarivo', '.mg'),
(131, 'Malawi', 'MWI', '265', 'MWK', 'Kwacha', 'Lilongwe', '.mw'),
(132, 'Malaysia', 'MYS', '60', 'MYR', 'Malaysian Ringgit', 'Kuala Lumpur', '.my'),
(133, 'Maldives', 'MDV', '960', 'MVR', 'Rufiyaa', 'Male', '.mv'),
(134, 'Mali', 'MLI', '223', 'XOF', 'CFA Franc BCEAO', 'Bamako', '.ml'),
(135, 'Malta', 'MLT', '356', 'EUR', 'Euro', 'Valletta', '.mt'),
(136, 'Marshall Islands', 'MHL', '692', 'USD', 'US Dollar', 'Majuro', '.mh'),
(137, 'Martinique', 'MTQ', '596', 'EUR', 'Euro', 'Fort-de-France', '.mq'),
(138, 'Mauritania', 'MRT', '222', 'MRO', 'Ouguiya', 'Nouakchott', '.mr'),
(139, 'Mauritius', 'MUS', '230', 'MUR', 'Mauritius Rupee', 'Port Louis', '.mu'),
(140, 'Mayotte', 'MYT', '262', 'EUR', 'Euro', 'Mamoudzou', '.yt'),
(141, 'Mexico', 'MEX', '52', 'MXN', 'Mexican Peso', 'Mexico City', '.mx'),
(142, 'Micronesia (Federated States of)', 'FSM', '691', 'USD', 'US Dollar', 'Palikir', '.fm'),
(143, 'Republic of Moldova', 'MDA', '373', 'MDL', 'Moldovan Leu', 'Chisinau', '.md'),
(144, 'Monaco', 'MCO', '377', 'EUR', 'Euro', 'Monaco', '.mc'),
(145, 'Mongolia', 'MNG', '976', 'MNT', 'Tugrik', 'Ulan Bator', '.mn'),
(146, 'Montenegro', 'MNE', '382', 'EUR', 'Euro', 'Podgorica', '.me'),
(147, 'Montserrat', 'MSR', '1-664', 'XCD', 'East Caribbean Dollar', 'Plymouth', '.ms'),
(148, 'Morocco', 'MAR', '212', 'MAD', 'Moroccan Dirham', 'Rabat', '.ma'),
(149, 'Mozambique', 'MOZ', '258', 'MZN', 'Mozambique Metical', 'Maputo', '.mz'),
(150, 'Myanmar', 'MMR', '95', 'MMK', 'Kyat', 'Nay Pyi Taw', '.mm'),
(151, 'Namibia', 'NAM', '264', 'ZAR', 'Rand', 'Windhoek', '.na'),
(152, 'Nauru', 'NRU', '674', 'AUD', 'Australian Dollar', 'Yaren', '.nr'),
(153, 'Nepal', 'NPL', '977', 'NPR', 'Nepalese Rupee', 'Kathmandu', '.np'),
(154, 'Netherlands', 'NLD', '31', 'EUR', 'Euro', 'Amsterdam', '.nl'),
(155, 'New Caledonia', 'NCL', '687', 'XPF', 'CFP Franc', 'Noumea', '.nc'),
(156, 'New Zealand', 'NZL', '64', 'NZD', 'New Zealand Dollar', 'Wellington', '.nz'),
(157, 'Nicaragua', 'NIC', '505', 'NIO', 'Cordoba Oro', 'Managua', '.ni'),
(158, 'Niger', 'NER', '227', 'XOF', 'CFA Franc BCEAO', 'Niamey', '.ne'),
(159, 'Nigeria', 'NGA', '234', 'NGN', 'Naira', 'Abuja', '.ng'),
(160, 'Niue', 'NIU', '683', 'NZD', 'New Zealand Dollar', 'Alofi', '.nu'),
(161, 'Norfolk Island', 'NFK', '672', 'AUD', 'Australian Dollar', 'Kingston', '.nf'),
(162, 'Democratic People\'s Republic of Korea', 'PRK', '850', 'KPW', 'North Korean Won', 'Pyongyang', '.kp'),
(163, 'Northern Mariana Islands', 'MNP', '1-670', 'USD', 'US Dollar', 'Saipan', '.mp'),
(164, 'Norway', 'NOR', '47', 'NOK', 'Norwegian Krone', 'Oslo', '.no'),
(165, 'Oman', 'OMN', '968', 'OMR', 'Rial Omani', 'Muscat', '.om'),
(166, 'Pakistan', 'PAK', '92', 'PKR', 'Pakistan Rupee', 'Islamabad', '.pk'),
(167, 'Palau', 'PLW', '680', 'USD', 'US Dollar', 'Melekeok', '.pw'),
(168, 'State of Palestine', 'PSE', '970', '', 'No universal currency', 'East Jerusalem', '.ps'),
(169, 'Panama', 'PAN', '507', 'USD', 'US Dollar', 'Panama City', '.pa'),
(170, 'Papua New Guinea', 'PNG', '675', 'PGK', 'Kina', 'Port Moresby', '.pg'),
(171, 'Paraguay', 'PRY', '595', 'PYG', 'Guarani', 'Asuncion', '.py'),
(172, 'Peru', 'PER', '51', 'PEN', 'Nuevo Sol', 'Lima', '.pe'),
(173, 'Philippines', 'PHL', '63', 'PHP', 'Philippine Peso', 'Manila', '.ph'),
(174, 'Pitcairn', 'PCN', '870', 'NZD', 'New Zealand Dollar', 'Adamstown', '.pn'),
(175, 'Poland', 'POL', '48', 'PLN', 'Zloty', 'Warsaw', '.pl'),
(176, 'Portugal', 'PRT', '351', 'EUR', 'Euro', 'Lisbon', '.pt'),
(177, 'Puerto Rico', 'PRI', '1', 'USD', 'US Dollar', 'San Juan', '.pr'),
(178, 'Qatar', 'QAT', '974', 'QAR', 'Qatari Rial', 'Doha', '.qa'),
(179, 'Romania', 'ROU', '40', 'RON', 'New Romanian Leu', 'Bucharest', '.ro'),
(180, 'Russian Federation', 'RUS', '7', 'RUB', 'Russian Ruble', 'Moscow', '.ru'),
(181, 'Rwanda', 'RWA', '250', 'RWF', 'Rwanda Franc', 'Kigali', '.rw'),
(182, 'Réunion', 'REU', '262', 'EUR', 'Euro', 'Saint-Denis', '.re'),
(183, 'Samoa', 'WSM', '685', 'WST', 'Tala', 'Apia', '.ws'),
(184, 'San Marino', 'SMR', '378', 'EUR', 'Euro', 'San Marino', '.sm'),
(185, 'Saudi Arabia', 'SAU', '966', 'SAR', 'Saudi Riyal', 'Riyadh', '.sa'),
(186, 'Senegal', 'SEN', '221', 'XOF', 'CFA Franc BCEAO', 'Dakar', '.sn'),
(187, 'Serbia', 'SRB', '381 p', 'RSD', 'Serbian Dinar', 'Belgrade', '.rs'),
(188, 'Seychelles', 'SYC', '248', 'SCR', 'Seychelles Rupee', 'Victoria', '.sc'),
(189, 'Sierra Leone', 'SLE', '232', 'SLL', 'Leone', 'Freetown', '.sl'),
(190, 'Singapore', 'SGP', '65', 'SGD', 'Singapore Dollar', 'Singapore', '.sg'),
(191, 'Sint Maarten (Dutch part)', 'SXM', '1-721', 'ANG', 'Netherlands Antillean Guilder', 'Philipsburg', '.sx'),
(192, 'Slovakia', 'SVK', '421', 'EUR', 'Euro', 'Bratislava', '.sk'),
(193, 'Slovenia', 'SVN', '386', 'EUR', 'Euro', 'Ljubljana', '.si'),
(194, 'Solomon Islands', 'SLB', '677', 'SBD', 'Solomon Islands Dollar', 'Honiara', '.sb'),
(195, 'Somalia', 'SOM', '252', 'SOS', 'Somali Shilling', 'Mogadishu', '.so'),
(196, 'South Africa', 'ZAF', '27', 'ZAR', 'Rand', 'Pretoria', '.za'),
(198, 'Republic of Korea', 'KOR', '82', 'KRW', 'Won', 'Seoul', '.kr'),
(199, 'South Sudan', 'SSD', '211', 'SSP', 'South Sudanese Pound', 'Juba', ''),
(200, 'Spain', 'ESP', '34', 'EUR', 'Euro', 'Madrid', '.es'),
(201, 'Sri Lanka', 'LKA', '94', 'LKR', 'Sri Lanka Rupee', 'Colombo', '.lk'),
(202, 'Saint Barthélemy', 'BLM', '590', 'EUR', 'Euro', 'Gustavia', '.gp'),
(203, 'Saint Helena', 'SHN', '290 n', 'SHP', 'Saint Helena Pound', 'Jamestown', '.sh'),
(204, 'Saint Kitts and Nevis', 'KNA', '1-869', 'XCD', 'East Caribbean Dollar', 'Basseterre', '.kn'),
(205, 'Saint Lucia', 'LCA', '1-758', 'XCD', 'East Caribbean Dollar', 'Castries', '.lc'),
(206, 'Saint Martin (French part)', 'MAF', '590', 'EUR', 'Euro', 'Marigot', '.gp'),
(207, 'Saint Pierre and Miquelon', 'SPM', '508', 'EUR', 'Euro', 'Saint-Pierre', '.pm'),
(208, 'Saint Vincent and the Grenadines', 'VCT', '1-784', 'XCD', 'East Caribbean Dollar', 'Kingstown', '.vc'),
(209, 'Sudan', 'SDN', '249', 'SDG', 'Sudanese Pound', 'Khartoum', '.sd'),
(210, 'Suriname', 'SUR', '597', 'SRD', 'Surinam Dollar', 'Paramaribo', '.sr'),
(211, 'Svalbard and Jan Mayen Islands', 'SJM', '47', 'NOK', 'Norwegian Krone', 'Longyearbyen', '.sj'),
(212, 'Swaziland', 'SWZ', '268', 'SZL', 'Lilangeni', 'Mbabane', '.sz'),
(213, 'Sweden', 'SWE', '46', 'SEK', 'Swedish Krona', 'Stockholm', '.se'),
(214, 'Switzerland', 'CHE', '41', 'CHF', 'Swiss Franc', 'Bern', '.ch'),
(215, 'Syrian Arab Republic', 'SYR', '963', 'SYP', 'Syrian Pound', 'Damascus', '.sy'),
(216, 'Sao Tome and Principe', 'STP', '239', 'STD', 'Dobra', 'Sao Tome', '.st'),
(218, 'Tajikistan', 'TJK', '992', 'TJS', 'Somoni', 'Dushanbe', '.tj'),
(219, 'United Republic of Tanzania', 'TZA', '255', 'TZS', 'Tanzanian Shilling', 'Dodoma', '.tz'),
(220, 'Thailand', 'THA', '66', 'THB', 'Baht', 'Bangkok', '.th'),
(221, 'Timor-Leste', 'TLS', '670', 'USD', 'US Dollar', 'Dili', '.tl'),
(222, 'Togo', 'TGO', '228', 'XOF', 'CFA Franc BCEAO', 'Lome', '.tg'),
(223, 'Tokelau', 'TKL', '690', 'NZD', 'New Zealand Dollar', '', '.tk'),
(224, 'Tonga', 'TON', '676', 'TOP', 'Paâ€™anga', 'Nuku\'alofa', '.to'),
(225, 'Trinidad and Tobago', 'TTO', '1-868', 'TTD', 'Trinidad and Tobago Dollar', 'Port of Spain', '.tt'),
(226, 'Tunisia', 'TUN', '216', 'TND', 'Tunisian Dinar', 'Tunis', '.tn'),
(227, 'Turkey', 'TUR', '90', 'TRY', 'Turkish Lira', 'Ankara', '.tr'),
(228, 'Turkmenistan', 'TKM', '993', 'TMT', 'Turkmenistan New Manat', 'Ashgabat', '.tm'),
(229, 'Turks and Caicos Islands', 'TCA', '1-649', 'USD', 'US Dollar', 'Cockburn Town', '.tc'),
(230, 'Tuvalu', 'TUV', '688', 'AUD', 'Australian Dollar', 'Funafuti', '.tv'),
(232, 'United States Virgin Islands', 'VIR', '1-340', 'USD', 'US Dollar', 'Charlotte Amalie', '.vi'),
(233, 'United Kingdom of Great Britain and Northern Ireland', 'GBR', '44', 'GBP', 'Pound Sterling', 'London', '.uk'),
(234, 'United States of America', 'USA', '1', 'USD', 'US Dollar', 'Washington', '.us'),
(235, 'Uganda', 'UGA', '256', 'UGX', 'Uganda Shilling', 'Kampala', '.ug'),
(236, 'Ukraine', 'UKR', '380', 'UAH', 'Hryvnia', 'Kiev', '.ua'),
(237, 'United Arab Emirates', 'ARE', '971', 'AED', 'UAE Dirham', 'Abu Dhabi', '.ae'),
(238, 'Uruguay', 'URY', '598', 'UYU', 'Peso Uruguayo', 'Montevideo', '.uy'),
(239, 'Uzbekistan', 'UZB', '998', 'UZS', 'Uzbekistan Sum', 'Tashkent', '.uz'),
(240, 'Vanuatu', 'VUT', '678', 'VUV', 'Vatu', 'Port Vila', '.vu'),
(241, 'Holy See', 'VAT', '39-06', 'EUR', 'Euro', 'Vatican City', '.va'),
(242, 'Venezuela (Bolivarian Republic of)', 'VEN', '58', 'VEF', 'Bolivar', 'Caracas', '.ve'),
(243, 'Viet Nam', 'VNM', '84', 'VND', 'Dong', 'Hanoi', '.vn'),
(244, 'Wallis and Futuna Islands', 'WLF', '681', 'XPF', 'CFP Franc', 'Mata Utu', '.wf'),
(245, 'Western Sahara', 'ESH', '212', 'MAD', 'Moroccan Dirham', 'El-Aaiun', '.eh'),
(246, 'Yemen', 'YEM', '967', 'YER', 'Yemeni Rial', 'Sanaa', '.ye'),
(247, 'Zambia', 'ZMB', '260', 'ZMW', 'Zambian Kwacha', 'Lusaka', '.zm'),
(248, 'Zimbabwe', 'ZWE', '263', 'ZWL', 'Zimbabwe Dollar', 'Harare', '.zw'),
(249, 'Ãland Islands', 'ALA', '358', 'EUR', 'Euro', 'Mariehamn', '.ax');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_accounts`
--

DROP TABLE IF EXISTS `customer_accounts`;
CREATE TABLE IF NOT EXISTS `customer_accounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `amount_in` double DEFAULT '0',
  `amount_out` double DEFAULT '0',
  `customer_id` int DEFAULT NULL,
  `tId` int DEFAULT '0',
  `mode` int DEFAULT NULL,
  `refno` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` int NOT NULL DEFAULT '1',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `transaction_status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_deposits`
--

DROP TABLE IF EXISTS `customer_deposits`;
CREATE TABLE IF NOT EXISTS `customer_deposits` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `mode` bigint DEFAULT NULL,
  `receipt` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `branch_id` bigint DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_deposits_customer_id_index` (`customer_id`),
  KEY `customer_deposits_mode_index` (`mode`),
  KEY `customer_deposits_user_id_index` (`user_id`),
  KEY `customer_deposits_branch_id_index` (`branch_id`),
  KEY `customer_deposits_business_id_index` (`business_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `developers`
--

DROP TABLE IF EXISTS `developers`;
CREATE TABLE IF NOT EXISTS `developers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `paid` bigint DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category_id` int DEFAULT NULL,
  `mode_id` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE IF NOT EXISTS `expense_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1=Active 0=Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `branch_id`, `business_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 1, 2, 0, '2022-02-18 15:05:30', '2022-11-14 10:53:57'),
(2, NULL, 1, 1, 2, 0, '2022-02-19 06:26:38', '2022-11-14 10:54:02'),
(3, 'Electricity', 1, 1, 2, 1, '2022-02-19 06:26:53', '2022-02-19 06:26:53'),
(4, 'Lunch', 1, 1, 2, 1, '2022-02-24 05:36:02', '2022-02-24 05:36:02'),
(5, 'fffd', 1, 1, 1, 0, '2022-03-15 06:12:35', '2022-03-15 06:12:42'),
(6, 'rent bill', 1, 1, 1, 1, '2022-03-15 08:35:38', '2023-01-20 08:22:16'),
(7, 'Allowance', 1, 1, 1, 1, '2022-06-15 12:46:52', '2022-06-15 12:46:52'),
(8, 'Lunch', 2, 1, 3, 1, '2022-08-05 10:58:08', '2022-08-05 10:58:08'),
(9, 'Airtime', 2, 1, 3, 1, '2022-08-05 11:18:06', '2022-08-05 11:18:06'),
(10, 'Transport', 2, 1, 3, 1, '2022-08-05 11:18:17', '2022-08-05 11:18:17'),
(11, 'Gabbage', 1, 1, 3, 1, '2022-09-08 12:33:22', '2022-09-08 12:33:22'),
(12, 'Breakfast', 1, 1, 3, 1, '2022-09-08 12:33:49', '2022-09-08 12:33:49'),
(13, 'Stationary', 1, 1, 3, 1, '2022-09-08 12:36:02', '2022-09-08 12:36:02'),
(14, 'Water bill', 1, 1, 3, 1, '2023-07-05 19:16:44', '2023-07-05 19:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `expense_payments`
--

DROP TABLE IF EXISTS `expense_payments`;
CREATE TABLE IF NOT EXISTS `expense_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expense_id` bigint NOT NULL,
  `amount` bigint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

DROP TABLE IF EXISTS `general_settings`;
CREATE TABLE IF NOT EXISTS `general_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `back_date_sales` tinyint(1) NOT NULL DEFAULT '0',
  `back_date_purchases` tinyint(1) NOT NULL DEFAULT '0',
  `sale_holding` tinyint(1) NOT NULL DEFAULT '0',
  `allow_negative_stock` tinyint(1) NOT NULL DEFAULT '0',
  `print_order_invoices` tinyint NOT NULL DEFAULT '0',
  `allow_multiple_units` tinyint(1) NOT NULL DEFAULT '0',
  `track_expiries` int NOT NULL DEFAULT '0' COMMENT '0=inactive 1=active',
  `allow_reserve_price` tinyint(1) NOT NULL DEFAULT '0',
  `track_untake_orders` tinyint(1) NOT NULL DEFAULT '0',
  `customer_deposit` int NOT NULL DEFAULT '0' COMMENT '0=inactive 1=active',
  `track_customers` tinyint(1) NOT NULL DEFAULT '0',
  `track_suppliers` int NOT NULL DEFAULT '0' COMMENT '0=inactive 1=active',
  `import_products` int NOT NULL DEFAULT '0',
  `send_appreciation` int NOT NULL DEFAULT '0',
  `customer_message` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_saledetails` int NOT NULL DEFAULT '0',
  `enable_credit_limit` int NOT NULL DEFAULT '0',
  `enable_debt_limit` int NOT NULL DEFAULT '0',
  `confirm_readyorders` int NOT NULL DEFAULT '0',
  `enable_wholeselling` int NOT NULL DEFAULT '0',
  `stockvalue_calculation` int NOT NULL DEFAULT '0',
  `sale_description` int NOT NULL DEFAULT '0',
  `negative_stocktransfer` int NOT NULL DEFAULT '0',
  `show_branchname` int NOT NULL DEFAULT '0',
  `set_total_as_paid` int NOT NULL DEFAULT '1',
  `sale_discount` int NOT NULL DEFAULT '0',
  `purchase_discount` int NOT NULL DEFAULT '0',
  `sellingprice_onstocking` int NOT NULL DEFAULT '0',
  `autofillprices_onstocking` int NOT NULL DEFAULT '0',
  `cash_at_hand` int NOT NULL DEFAULT '0',
  `show_businessvalue` int NOT NULL DEFAULT '0',
  `show_batch_selling` int NOT NULL DEFAULT '0',
  `update_receivedproduct` int NOT NULL DEFAULT '0',
  `deposit_balances` int NOT NULL DEFAULT '0',
  `use_last_costprice` int NOT NULL DEFAULT '0',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `back_date_sales`, `back_date_purchases`, `sale_holding`, `allow_negative_stock`, `print_order_invoices`, `allow_multiple_units`, `track_expiries`, `allow_reserve_price`, `track_untake_orders`, `customer_deposit`, `track_customers`, `track_suppliers`, `import_products`, `send_appreciation`, `customer_message`, `message_saledetails`, `enable_credit_limit`, `enable_debt_limit`, `confirm_readyorders`, `enable_wholeselling`, `stockvalue_calculation`, `sale_description`, `negative_stocktransfer`, `show_branchname`, `set_total_as_paid`, `sale_discount`, `purchase_discount`, `sellingprice_onstocking`, `autofillprices_onstocking`, `cash_at_hand`, `show_businessvalue`, `show_batch_selling`, `update_receivedproduct`, `deposit_balances`, `use_last_costprice`, `branch_id`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, 1, 0, 0, 1, 0, 1, 1, 1, 1, 0, 0, 'Hello Customer', 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 1, 1, 1, 1, 0, 1, 1, 0, 1, 1, '2023-08-12 21:00:00', '2023-08-12 21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `activity` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_01_12_081636_create_batches_table', 1),
(6, '2022_01_12_083322_create_countries_table', 1),
(7, '2022_01_12_083858_create_customers_table', 1),
(8, '2022_01_12_084617_create_expenses_table', 1),
(9, '2022_01_12_085654_create_expense_categories_table', 1),
(10, '2022_01_12_090519_create_logs_table', 1),
(11, '2022_01_12_093415_create_payments_table', 1),
(12, '2022_01_12_100854_create_purchase_payments_table', 1),
(13, '2022_01_12_103416_create_purchase_orders_table', 1),
(65, '2022_02_08_092707_create_lpo_details_table', 7),
(15, '2022_01_12_124648_create_business_products_table', 1),
(16, '2022_01_12_125506_create_products_table', 1),
(17, '2022_01_12_130315_create_product_categories_table', 1),
(18, '2022_01_12_130746_create_product_units_table', 1),
(19, '2022_01_12_131338_create_units_table', 1),
(20, '2022_01_12_131535_create_suppliers_table', 1),
(21, '2022_01_12_131954_create_sms_logs_table', 1),
(22, '2022_01_12_132618_create_sms_packages_table', 1),
(23, '2022_01_12_132938_create_sms_purchases_table', 1),
(24, '2022_01_12_133631_create_sms_balances_table', 1),
(25, '2022_01_12_133947_create_purchases_table', 1),
(26, '2022_01_12_134359_create_purchase_carts_table', 1),
(27, '2022_01_12_134723_create_purchase_details_table', 1),
(28, '2022_01_12_134914_create_purchase_returns_table', 1),
(29, '2022_01_12_135919_create_sales_table', 1),
(30, '2022_01_12_140154_create_sale_carts_table', 1),
(31, '2022_01_12_140701_create_sale_details_table', 1),
(32, '2022_01_12_140843_create_sale_returns_table', 1),
(33, '2022_01_12_141204_create_spoilt_stocks_table', 1),
(34, '2022_01_12_141332_create_stocks_table', 1),
(35, '2022_01_12_141636_create_stock_movements_table', 1),
(64, '2022_01_12_142140_create_stock_transfers_table', 6),
(37, '2022_01_12_142838_create_stock_transfer_carts_table', 1),
(38, '2022_01_12_143626_create_stores_table', 1),
(39, '2022_01_12_144403_create_payment_options_table', 1),
(40, '2022_01_14_062718_create_transactions_table', 1),
(41, '2022_01_14_064143_create_cashins_table', 1),
(42, '2022_01_14_064155_create_cashouts_table', 1),
(43, '2022_01_14_064930_create_money_transfers_table', 1),
(44, '2022_01_14_070054_create_customer_accounts_table', 1),
(45, '2022_01_14_070606_create_businesses_table', 1),
(46, '2022_01_14_070623_create_branches_table', 1),
(78, '2022_01_14_071946_create_permissions_table', 11),
(48, '2022_01_14_073821_create_business_settings_table', 1),
(49, '2022_01_14_073839_create_general_settings_table', 1),
(50, '2022_01_14_075429_create_licences_table', 1),
(51, '2022_01_14_080332_create_reset_passwords_table', 1),
(52, '2022_01_14_080437_create_developers_table', 1),
(53, '2022_01_14_080505_create_notifications_table', 1),
(54, '2022_01_14_080547_create_consumable_items_table', 1),
(55, '2022_01_14_080714_create_consumable_stocks_table', 1),
(56, '2022_01_14_092518_create_consumable_usages_table', 1),
(57, '2022_01_14_094728_create_licence_notifications_table', 1),
(58, '2022_01_14_095101_create_receipt_settings_table', 1),
(59, '2022_01_14_100344_create_stock_reconciliations_table', 1),
(60, '2022_02_02_094741_create_store_movements_table', 2),
(63, '2022_02_05_094036_create_stock_transfer_details_table', 5),
(66, '2022_02_08_092801_create_lpos_table', 7),
(67, '2022_02_08_092921_create_lpo_carts_table', 7),
(68, '2022_02_16_161532_create_sale_holds_table', 8),
(69, '2022_02_28_150224_create_services_table', 8),
(70, '2022_03_01_154634_create_reconcilations_table', 8),
(71, '2022_03_08_102142_create_lpo_receive_carts_table', 9),
(72, '2022_03_19_084936_create_customer_deposits_table', 9),
(73, '2022_05_31_100644_create_supplier_accounts_table', 10),
(74, '2022_05_31_102402_create_supplier_deposits_table', 10),
(79, '2022_08_23_085837_create_consumable_stock_carts_table', 12),
(80, '2022_08_24_102603_create_consumable_usage_carts_table', 13),
(81, '2022_08_30_171302_create_messagings_table', 14),
(82, '2022_08_31_162116_create_sms_accounts_table', 15),
(83, '2022_09_06_145446_create_reconcilation_logs_table', 16),
(84, '2022_09_15_125910_create_instock_transfer_carts_table', 17),
(85, '2022_09_15_125910_create_instock_transfer_details_table', 17),
(86, '2022_09_15_125910_create_instock_transfers_table', 17),
(87, '2022_09_22_124732_create_temp_passwords_table', 18),
(88, '2022_09_29_091843_create_price_changes_table', 19),
(89, '2022_10_07_090943_create_access_times_table', 20),
(90, '2022_10_18_121406_create_sellingprice_changes_table', 21);

-- --------------------------------------------------------

--
-- Table structure for table `money_transfers`
--

DROP TABLE IF EXISTS `money_transfers`;
CREATE TABLE IF NOT EXISTS `money_transfers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `amount` double DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `refno` int DEFAULT NULL,
  `madeBy` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` int DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `mode` int DEFAULT NULL,
  `receipt` bigint DEFAULT NULL,
  `date` date DEFAULT NULL,
  `recno` int DEFAULT NULL,
  `month` tinyint DEFAULT NULL,
  `year` year DEFAULT NULL,
  `type` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '1-Sale, 2-Previous Debtor',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `transaction_status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_options`
--

DROP TABLE IF EXISTS `payment_options`;
CREATE TABLE IF NOT EXISTS `payment_options` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g Rancho',
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g Bank',
  `type_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g Centenary',
  `account_number` bigint DEFAULT NULL COMMENT 'e.g 0098789433982',
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` double DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0=deleted 1=active',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_options`
--

INSERT INTO `payment_options` (`id`, `name`, `type`, `type_name`, `account_number`, `code`, `balance`, `is_default`, `status`, `branch_id`, `business_id`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 'Test Payment', 'Bank', 'Standard Chartered', 789656567, '', 235000, 1, 1, 1, 1, 2, '2023-08-14 07:11:10', '2023-08-14 07:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `stock_tab` tinyint(1) NOT NULL DEFAULT '0',
  `store_tab` tinyint(1) NOT NULL DEFAULT '0',
  `reports_tab` tinyint(1) NOT NULL DEFAULT '0',
  `products_tab` tinyint(1) NOT NULL DEFAULT '0',
  `expenses_tab` tinyint(1) NOT NULL DEFAULT '0',
  `logout_tab` tinyint(1) NOT NULL DEFAULT '1',
  `new_sale` tinyint(1) NOT NULL DEFAULT '0',
  `view_shop_stock` tinyint(1) NOT NULL DEFAULT '0',
  `add_shop_stock` tinyint(1) NOT NULL DEFAULT '0',
  `edit_shop_stock` tinyint(1) NOT NULL DEFAULT '0',
  `view_store_stock` tinyint(1) NOT NULL DEFAULT '0',
  `add_store_stock` tinyint(1) NOT NULL DEFAULT '0',
  `edit_store_stock` tinyint(1) NOT NULL DEFAULT '0',
  `view_spoilt_stock` tinyint(1) NOT NULL DEFAULT '0',
  `record_spoilt_stock` tinyint(1) NOT NULL DEFAULT '0',
  `delete_spoilt_stock` tinyint(1) NOT NULL DEFAULT '0',
  `view_short_expiries` tinyint(1) NOT NULL DEFAULT '0',
  `view_expired_stock` tinyint(1) NOT NULL DEFAULT '0',
  `record_expired_stock` tinyint(1) NOT NULL DEFAULT '0',
  `reconcile_shop_stock` tinyint(1) NOT NULL DEFAULT '0',
  `reconcile_store_stock` tinyint(1) NOT NULL DEFAULT '0',
  `savereconcile_shop_stock` int NOT NULL DEFAULT '0',
  `savereconcile_store_stock` int NOT NULL DEFAULT '0',
  `view_reconciliation_report` tinyint(1) NOT NULL DEFAULT '0',
  `transfer_stock` tinyint(1) NOT NULL DEFAULT '0',
  `receive_transferred_stock` tinyint(1) NOT NULL DEFAULT '0',
  `view_stock_transfers` tinyint(1) NOT NULL DEFAULT '0',
  `view_payment_options` tinyint(1) NOT NULL DEFAULT '0',
  `add_payment_options` tinyint(1) NOT NULL DEFAULT '0',
  `edit_payment_options` tinyint(1) NOT NULL DEFAULT '0',
  `delete_payment_options` tinyint(1) NOT NULL DEFAULT '0',
  `view_option_statement` tinyint(1) NOT NULL DEFAULT '0',
  `view_currencies` tinyint(1) NOT NULL DEFAULT '0',
  `view_customers` tinyint(1) NOT NULL DEFAULT '0',
  `add_customers` tinyint(1) NOT NULL DEFAULT '0',
  `edit_customers` tinyint(1) NOT NULL DEFAULT '0',
  `delete_customers` tinyint(1) NOT NULL DEFAULT '0',
  `view_customer_deposits` tinyint(1) NOT NULL DEFAULT '0',
  `add_customer_deposits` tinyint(1) NOT NULL DEFAULT '0',
  `edit_customer_deposits` tinyint(1) NOT NULL DEFAULT '0',
  `delete_customer_deposits` tinyint(1) NOT NULL DEFAULT '0',
  `view_customer_balances` tinyint(1) NOT NULL DEFAULT '0',
  `view_suppliers` tinyint(1) NOT NULL DEFAULT '0',
  `add_suppliers` tinyint(1) NOT NULL DEFAULT '0',
  `edit_suppliers` tinyint(1) NOT NULL DEFAULT '0',
  `delete_suppliers` tinyint(1) NOT NULL DEFAULT '0',
  `view_supplier_deposits` tinyint(1) NOT NULL DEFAULT '0',
  `add_supplier_deposits` tinyint(1) NOT NULL DEFAULT '0',
  `edit_supplier_deposits` tinyint(1) NOT NULL DEFAULT '0',
  `delete_supplier_deposits` tinyint(1) NOT NULL DEFAULT '0',
  `view_audits` tinyint(1) NOT NULL DEFAULT '0',
  `view_cash_in` tinyint(1) NOT NULL DEFAULT '0',
  `add_cash_in` tinyint(1) NOT NULL DEFAULT '0',
  `edit_cash_in` tinyint(1) NOT NULL DEFAULT '0',
  `delete_cash_in` tinyint(1) NOT NULL DEFAULT '0',
  `view_cash_out` tinyint(1) NOT NULL DEFAULT '0',
  `add_cash_out` tinyint(1) NOT NULL DEFAULT '0',
  `edit_cash_out` tinyint(1) NOT NULL DEFAULT '0',
  `delete_cash_out` tinyint(1) NOT NULL DEFAULT '0',
  `view_money_transfers` tinyint(1) NOT NULL DEFAULT '0',
  `add_money_transfers` tinyint(1) NOT NULL DEFAULT '0',
  `edit_money_transfers` tinyint(1) NOT NULL DEFAULT '0',
  `delete_money_transfers` tinyint(1) NOT NULL DEFAULT '0',
  `view_expenses` tinyint(1) NOT NULL DEFAULT '0',
  `add_expenses` tinyint(1) NOT NULL DEFAULT '0',
  `edit_expenses` tinyint(1) NOT NULL DEFAULT '0',
  `delete_expenses` tinyint(1) NOT NULL DEFAULT '0',
  `view_expense_categories` tinyint(1) NOT NULL DEFAULT '0',
  `add_expense_categories` tinyint(1) NOT NULL DEFAULT '0',
  `edit_expense_categories` tinyint(1) NOT NULL DEFAULT '0',
  `delete_expense_categories` tinyint(1) NOT NULL DEFAULT '0',
  `view_expenses_report` tinyint(1) NOT NULL DEFAULT '0',
  `view_products` tinyint(1) NOT NULL DEFAULT '0',
  `add_products` tinyint(1) NOT NULL DEFAULT '0',
  `edit_products` tinyint(1) NOT NULL DEFAULT '0',
  `delete_products` tinyint(1) NOT NULL DEFAULT '0',
  `view_business_products` tinyint(1) NOT NULL DEFAULT '0',
  `add_business_products` tinyint(1) NOT NULL DEFAULT '0',
  `edit_business_products` tinyint(1) NOT NULL DEFAULT '0',
  `delete_business_products` tinyint(1) NOT NULL DEFAULT '0',
  `view_product_categories` tinyint(1) NOT NULL DEFAULT '0',
  `add_product_categories` tinyint(1) NOT NULL DEFAULT '0',
  `edit_product_categories` tinyint(1) NOT NULL DEFAULT '0',
  `delete_product_categories` tinyint(1) NOT NULL DEFAULT '0',
  `view_product_units` tinyint(1) NOT NULL DEFAULT '0',
  `add_product_units` tinyint(1) NOT NULL DEFAULT '0',
  `edit_product_units` tinyint(1) NOT NULL DEFAULT '0',
  `delete_product_units` tinyint(1) NOT NULL DEFAULT '0',
  `view_units` tinyint(1) NOT NULL DEFAULT '0',
  `add_units` tinyint(1) NOT NULL DEFAULT '0',
  `edit_units` tinyint(1) NOT NULL DEFAULT '0',
  `delete_units` tinyint(1) NOT NULL DEFAULT '0',
  `view_services` tinyint(1) NOT NULL DEFAULT '0',
  `view_sales_report` tinyint(1) NOT NULL DEFAULT '0',
  `view_purchases_report` tinyint(1) NOT NULL DEFAULT '0',
  `view_debtors_report` tinyint(1) NOT NULL DEFAULT '0',
  `clear_debtors` tinyint(1) NOT NULL DEFAULT '0',
  `clear_creditors` tinyint(1) NOT NULL DEFAULT '0',
  `view_creditors_report` tinyint(1) NOT NULL DEFAULT '0',
  `view_sales_analysis` tinyint(1) NOT NULL DEFAULT '0',
  `edit_sales` tinyint(1) NOT NULL DEFAULT '0',
  `delete_sales` tinyint(1) NOT NULL DEFAULT '0',
  `edit_sales_amountpaid` tinyint(1) NOT NULL DEFAULT '0',
  `edit_selling_price` int NOT NULL DEFAULT '0',
  `edit_purchase` tinyint(1) NOT NULL DEFAULT '0',
  `delete_purchase` tinyint(1) NOT NULL DEFAULT '0',
  `edit_purchase_amountpaid` tinyint(1) NOT NULL DEFAULT '0',
  `manage_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `view_users` tinyint(1) NOT NULL DEFAULT '0',
  `add_users` tinyint(1) NOT NULL DEFAULT '0',
  `edit_users` tinyint(1) NOT NULL DEFAULT '0',
  `delete_users` tinyint(1) NOT NULL DEFAULT '0',
  `view_user_roles` tinyint(1) NOT NULL DEFAULT '0',
  `add_user_roles` tinyint(1) NOT NULL DEFAULT '0',
  `edit_user_roles` tinyint(1) NOT NULL DEFAULT '0',
  `delete_user_roles` tinyint(1) NOT NULL DEFAULT '0',
  `view_held_sales` tinyint(1) NOT NULL DEFAULT '0',
  `view_messages` tinyint(1) NOT NULL DEFAULT '0',
  `view_general_settings` tinyint(1) NOT NULL DEFAULT '0',
  `view_receipt_settings` tinyint(1) NOT NULL DEFAULT '0',
  `view_lpo` tinyint(1) NOT NULL DEFAULT '0',
  `remove_expired_stock` tinyint(1) NOT NULL DEFAULT '0',
  `advanced_features_tab` tinyint(1) NOT NULL DEFAULT '0',
  `advanced_reports_tab` tinyint(1) NOT NULL DEFAULT '0',
  `consumables_tab` int NOT NULL DEFAULT '0',
  `view_advanced_sales_report` tinyint(1) NOT NULL DEFAULT '0',
  `view_advanced_purchases_report` tinyint(1) NOT NULL DEFAULT '0',
  `view_advanced_debtors_report` tinyint(1) NOT NULL DEFAULT '0',
  `view_advanced_creditors_report` tinyint(1) NOT NULL DEFAULT '0',
  `view_advanced_stock_transfers` tinyint(1) NOT NULL DEFAULT '0',
  `view_advanced_held_sales` tinyint(1) NOT NULL DEFAULT '0',
  `view_advanced_expenses_report` tinyint(1) NOT NULL DEFAULT '0',
  `view_transactions_report` int NOT NULL DEFAULT '0',
  `view_income_statement` int NOT NULL DEFAULT '0',
  `view_product_demand_report` int NOT NULL DEFAULT '0',
  `view_purchase_payments_report` int NOT NULL DEFAULT '0',
  `view_profitanalysis_report` int NOT NULL DEFAULT '0',
  `add_consumable_items` int NOT NULL DEFAULT '0',
  `stocking_consumables` int NOT NULL DEFAULT '0',
  `using_consumables` int NOT NULL DEFAULT '0',
  `stocking_report` int NOT NULL DEFAULT '0',
  `usage_report` int NOT NULL DEFAULT '0',
  `edit_consumables` int NOT NULL DEFAULT '0',
  `delete_consumables` int NOT NULL DEFAULT '0',
  `view_purchasereturns_report` int NOT NULL DEFAULT '0',
  `view_salereturns_report` int NOT NULL DEFAULT '0',
  `confirm_spoilt_stock` int NOT NULL DEFAULT '0',
  `view_payments_report` int NOT NULL DEFAULT '0',
  `view_storeprice_changes` int NOT NULL DEFAULT '0',
  `view_shopprice_changes` int NOT NULL DEFAULT '0',
  `manage_accesstime` int NOT NULL DEFAULT '0',
  `edit_accesstime` int NOT NULL DEFAULT '0',
  `view_supplierbalances` int NOT NULL DEFAULT '0',
  `confirm_orders` int NOT NULL DEFAULT '0',
  `confirm_servedorders` int NOT NULL DEFAULT '0',
  `view_sellingprice_changes` int NOT NULL DEFAULT '0',
  `internaltransfer_stock` int NOT NULL DEFAULT '0',
  `directtransfer_stock` int NOT NULL DEFAULT '0',
  `view_advanced_shoppurchases_report` int NOT NULL DEFAULT '0',
  `view_advanced_storepurchases_report` int NOT NULL DEFAULT '0',
  `modify_sales` int NOT NULL DEFAULT '0',
  `modify_purchases` int NOT NULL DEFAULT '0',
  `view_user_summary` int NOT NULL DEFAULT '0',
  `view_general_summary` int DEFAULT '0',
  `print_heldsales` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0=inactive 1=active',
  `user_id` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `role`, `view_dashboard`, `stock_tab`, `store_tab`, `reports_tab`, `products_tab`, `expenses_tab`, `logout_tab`, `new_sale`, `view_shop_stock`, `add_shop_stock`, `edit_shop_stock`, `view_store_stock`, `add_store_stock`, `edit_store_stock`, `view_spoilt_stock`, `record_spoilt_stock`, `delete_spoilt_stock`, `view_short_expiries`, `view_expired_stock`, `record_expired_stock`, `reconcile_shop_stock`, `reconcile_store_stock`, `savereconcile_shop_stock`, `savereconcile_store_stock`, `view_reconciliation_report`, `transfer_stock`, `receive_transferred_stock`, `view_stock_transfers`, `view_payment_options`, `add_payment_options`, `edit_payment_options`, `delete_payment_options`, `view_option_statement`, `view_currencies`, `view_customers`, `add_customers`, `edit_customers`, `delete_customers`, `view_customer_deposits`, `add_customer_deposits`, `edit_customer_deposits`, `delete_customer_deposits`, `view_customer_balances`, `view_suppliers`, `add_suppliers`, `edit_suppliers`, `delete_suppliers`, `view_supplier_deposits`, `add_supplier_deposits`, `edit_supplier_deposits`, `delete_supplier_deposits`, `view_audits`, `view_cash_in`, `add_cash_in`, `edit_cash_in`, `delete_cash_in`, `view_cash_out`, `add_cash_out`, `edit_cash_out`, `delete_cash_out`, `view_money_transfers`, `add_money_transfers`, `edit_money_transfers`, `delete_money_transfers`, `view_expenses`, `add_expenses`, `edit_expenses`, `delete_expenses`, `view_expense_categories`, `add_expense_categories`, `edit_expense_categories`, `delete_expense_categories`, `view_expenses_report`, `view_products`, `add_products`, `edit_products`, `delete_products`, `view_business_products`, `add_business_products`, `edit_business_products`, `delete_business_products`, `view_product_categories`, `add_product_categories`, `edit_product_categories`, `delete_product_categories`, `view_product_units`, `add_product_units`, `edit_product_units`, `delete_product_units`, `view_units`, `add_units`, `edit_units`, `delete_units`, `view_services`, `view_sales_report`, `view_purchases_report`, `view_debtors_report`, `clear_debtors`, `clear_creditors`, `view_creditors_report`, `view_sales_analysis`, `edit_sales`, `delete_sales`, `edit_sales_amountpaid`, `edit_selling_price`, `edit_purchase`, `delete_purchase`, `edit_purchase_amountpaid`, `manage_permissions`, `view_users`, `add_users`, `edit_users`, `delete_users`, `view_user_roles`, `add_user_roles`, `edit_user_roles`, `delete_user_roles`, `view_held_sales`, `view_messages`, `view_general_settings`, `view_receipt_settings`, `view_lpo`, `remove_expired_stock`, `advanced_features_tab`, `advanced_reports_tab`, `consumables_tab`, `view_advanced_sales_report`, `view_advanced_purchases_report`, `view_advanced_debtors_report`, `view_advanced_creditors_report`, `view_advanced_stock_transfers`, `view_advanced_held_sales`, `view_advanced_expenses_report`, `view_transactions_report`, `view_income_statement`, `view_product_demand_report`, `view_purchase_payments_report`, `view_profitanalysis_report`, `add_consumable_items`, `stocking_consumables`, `using_consumables`, `stocking_report`, `usage_report`, `edit_consumables`, `delete_consumables`, `view_purchasereturns_report`, `view_salereturns_report`, `confirm_spoilt_stock`, `view_payments_report`, `view_storeprice_changes`, `view_shopprice_changes`, `manage_accesstime`, `edit_accesstime`, `view_supplierbalances`, `confirm_orders`, `confirm_servedorders`, `view_sellingprice_changes`, `internaltransfer_stock`, `directtransfer_stock`, `view_advanced_shoppurchases_report`, `view_advanced_storepurchases_report`, `modify_sales`, `modify_purchases`, `view_user_summary`, `view_general_summary`, `print_heldsales`, `status`, `user_id`, `branch_id`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 3, 1, 1, '2022-08-11 21:00:00', '2022-08-14 15:15:04');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=350 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(8, 'App\\Models\\User', 1, 'My-token', '85431a8e6a8c13d6e6da38398a65299291667b980e44244d19658353630e0b8a', '[\"*\"]', '2022-06-15 08:11:12', '2022-06-15 08:08:20', '2022-06-15 08:11:12'),
(10, 'App\\Models\\User', 1, 'My-token', '95cedfcbb18c01cf827cc181f81446e32957ba55d3721ab6f548f5dee730483f', '[\"*\"]', '2022-06-15 08:44:14', '2022-06-15 08:42:52', '2022-06-15 08:44:14'),
(14, 'App\\Models\\User', 1, 'My-token', '38d10b63f7b7fc42efeab74a342c98a239563115c3bff0440d532f910e70d956', '[\"*\"]', '2022-06-15 12:50:07', '2022-06-15 12:13:20', '2022-06-15 12:50:07'),
(12, 'App\\Models\\User', 1, 'My-token', 'ca922c7047ee141c9d5a9cdd5a38b98f3d7b6e979933d0a042c146a22726e590', '[\"*\"]', NULL, '2022-06-15 08:49:31', '2022-06-15 08:49:31'),
(249, 'App\\Models\\User', 3, 'My-token', '4b9deeba4152d118469e1ea8f9b1629918095c785ccf2e0ce5aa37076deb0c01', '[\"*\"]', '2023-03-03 07:41:15', '2023-03-03 07:35:48', '2023-03-03 07:41:15'),
(248, 'App\\Models\\User', 3, 'My-token', '8614dad9f4bc2bc7a38903abf76c7fe729d2226a86072aa20613229ab83969c6', '[\"*\"]', '2023-03-03 07:15:55', '2023-03-03 07:15:51', '2023-03-03 07:15:55'),
(247, 'App\\Models\\User', 3, 'My-token', '6bf5cfa419d5e16d6f407564fb360ee412d48686a2c327e76b30f37b59a273c6', '[\"*\"]', '2023-03-03 07:10:22', '2023-03-03 07:10:19', '2023-03-03 07:10:22'),
(246, 'App\\Models\\User', 3, 'My-token', '5cdad418a4fcc46bc768b2083087b3ae4e4bbdde2f575ceaf848a26c70360147', '[\"*\"]', '2023-03-03 07:03:51', '2023-03-03 07:00:43', '2023-03-03 07:03:51'),
(245, 'App\\Models\\User', 3, 'My-token', '7bf27b520ceca2fc51f343544d87ffa5a28499d55b343815f0069f7f372775b7', '[\"*\"]', '2023-03-03 06:53:59', '2023-03-03 06:48:10', '2023-03-03 06:53:59'),
(244, 'App\\Models\\User', 3, 'My-token', 'd5fd85a6c86aba8c115e18cf5dd946324d2d6f7bf285274c31710c5832447fab', '[\"*\"]', '2023-03-03 06:40:21', '2023-03-03 06:40:03', '2023-03-03 06:40:21'),
(243, 'App\\Models\\User', 3, 'My-token', 'fd915ddb468214943e0037ad6f2d72091c94848045e45bd409c1c26d6cf4e6f2', '[\"*\"]', '2023-03-03 06:39:09', '2023-03-03 06:13:25', '2023-03-03 06:39:09'),
(242, 'App\\Models\\User', 3, 'My-token', '5d11481eb4d5ef7f6d135e12f3f4066343faa28884c00fde9bdf9b356d19018c', '[\"*\"]', '2023-03-02 13:19:28', '2023-03-02 13:19:21', '2023-03-02 13:19:28'),
(241, 'App\\Models\\User', 3, 'My-token', '01189049854d7257bbbfd9b080d702f1529fe1d646a7cbe2c14f149b8defef63', '[\"*\"]', '2023-03-02 13:17:53', '2023-03-02 11:48:33', '2023-03-02 13:17:53'),
(240, 'App\\Models\\User', 3, 'My-token', '4c77bc11183c0bcafde0dc34e754b306d3849627d2ab55c0affc8fb7621e675e', '[\"*\"]', '2023-03-02 11:01:59', '2023-03-02 11:01:54', '2023-03-02 11:01:59'),
(239, 'App\\Models\\User', 3, 'My-token', '5965e1d140524d8f646b5e9dabb7e2aa43c5dbe7f9488fba703b4f4f4ef56919', '[\"*\"]', NULL, '2023-03-02 10:16:17', '2023-03-02 10:16:17'),
(238, 'App\\Models\\User', 3, 'My-token', '686f14d06fb85c18bb7f319035ca8855d9dda655a5954bac1b2d25a4cb91ca8e', '[\"*\"]', NULL, '2023-03-02 10:15:50', '2023-03-02 10:15:50'),
(237, 'App\\Models\\User', 3, 'My-token', '55b0943b6bc6bd720fdd4da08fd7e3e9392f223ba7a25103bc481f8ef4880b4f', '[\"*\"]', '2023-03-02 10:06:47', '2023-03-02 10:06:42', '2023-03-02 10:06:47'),
(236, 'App\\Models\\User', 3, 'My-token', '674a975a7e237ac2da6685f5a59e0d871a9cf805144d4f2b9cbbb53c9e0edfa9', '[\"*\"]', '2023-03-02 10:04:03', '2023-03-02 10:03:58', '2023-03-02 10:04:03'),
(235, 'App\\Models\\User', 3, 'My-token', 'd4a37dc0dbc6c91a22f52d6774aa099dd6bec06e9ead4d3fb9237d69b78328d9', '[\"*\"]', '2023-03-02 09:52:33', '2023-03-02 09:52:29', '2023-03-02 09:52:33'),
(234, 'App\\Models\\User', 3, 'My-token', '361eb81fd8d5ebd5c0e5c98f88bf09212decdd9ef559fde6e7001a27bbbf7fbc', '[\"*\"]', '2023-03-02 09:51:23', '2023-03-01 16:46:06', '2023-03-02 09:51:23'),
(233, 'App\\Models\\User', 3, 'My-token', 'ed3a3cbec067a76f3da0c77a5aa399db6a0adf294b57ad8ce9469636721b9789', '[\"*\"]', '2023-03-01 09:47:46', '2023-02-27 12:15:21', '2023-03-01 09:47:46'),
(232, 'App\\Models\\User', 3, 'My-token', 'ba8e259a7dccde6e49df048900fbf5285aa7c4fe8ae5e72596e7eda81b9b2747', '[\"*\"]', '2023-02-27 11:13:01', '2023-02-27 08:20:53', '2023-02-27 11:13:01'),
(231, 'App\\Models\\User', 3, 'My-token', '2610684ae4b5caba83bb819d74f3ae6393df5fce1d3ef307d3c01335125ecd0c', '[\"*\"]', '2023-02-27 08:20:14', '2023-02-24 05:18:11', '2023-02-27 08:20:14'),
(230, 'App\\Models\\User', 3, 'My-token', 'd82caebe8fceef300e832ca3845bb7dca36196150a80a64c753d70f8ae39ef11', '[\"*\"]', '2023-02-21 09:42:08', '2023-02-20 12:50:53', '2023-02-21 09:42:08'),
(229, 'App\\Models\\User', 3, 'My-token', '0f7b2d676c764bfd8a6e16cc191aec02f64d1e64312dae7d2b3cc2e9e6ce26aa', '[\"*\"]', '2023-02-20 12:48:37', '2023-02-20 12:10:16', '2023-02-20 12:48:37'),
(227, 'App\\Models\\User', 3, 'My-token', '5313978063608d65548fcf706621a6ccb6f118fbe6ca61eddc0a9f2015d608e9', '[\"*\"]', '2023-02-17 08:03:16', '2023-02-14 11:32:07', '2023-02-17 08:03:16'),
(222, 'App\\Models\\User', 3, 'My-token', 'ad201a9616d7db047578f922c2756d37abf14202025ef52d06f98f36dba1ae99', '[\"*\"]', '2023-02-08 14:22:53', '2023-02-02 06:16:24', '2023-02-08 14:22:53'),
(224, 'App\\Models\\User', 2, 'My-token', '696b6008d3d77a1ca059e95bdffe69bda90194cc4f80363f11803b23ebd68940', '[\"*\"]', '2023-02-03 10:23:27', '2023-02-03 09:36:52', '2023-02-03 10:23:27'),
(220, 'App\\Models\\User', 3, 'My-token', '8b83956ee13ec04a8c1dde033fdcf171d55423480f182646f7bf32bd442dff12', '[\"*\"]', '2023-02-02 06:15:20', '2023-02-01 08:14:52', '2023-02-02 06:15:20'),
(221, 'App\\Models\\User', 2, 'My-token', '92f79c6c805a5f6a84284628f7432daed59b8159b24403dbbd86926ac8380b9e', '[\"*\"]', '2023-02-01 12:36:54', '2023-02-01 10:07:46', '2023-02-01 12:36:54'),
(193, 'App\\Models\\User', 3, 'My-token', 'e243e7b5c33b7eba3421df47579aa5b2bfc882500f4a2d08b6f67647ed2f866f', '[\"*\"]', '2022-11-24 11:57:27', '2022-11-24 05:52:31', '2022-11-24 11:57:27'),
(195, 'App\\Models\\User', 3, 'My-token', 'f4fbc552a846e35d174fe6f35d8ebb1949378528b0af85022b6926f3d363e50a', '[\"*\"]', NULL, '2022-11-29 07:51:49', '2022-11-29 07:51:49'),
(200, 'App\\Models\\User', 15, 'My-token', 'f89a047757db7b5171f0c8c34d17a2780fc586997ec6c0f11ca9e4cae612b880', '[\"*\"]', '2022-11-30 07:58:40', '2022-11-29 08:59:36', '2022-11-30 07:58:40'),
(202, 'App\\Models\\User', 3, 'My-token', 'd6322c06b24c9e07a1d5ee3a257b40b4ed55503c980b18a5a49111c851fb0e48', '[\"*\"]', '2022-12-05 10:55:21', '2022-12-05 10:31:41', '2022-12-05 10:55:21'),
(203, 'App\\Models\\User', 3, 'My-token', '3fbcd00604380f3243f2407cadd740adcfa6d87c86ad7ee735dac188c67af660', '[\"*\"]', '2022-12-08 11:40:37', '2022-12-06 13:13:01', '2022-12-08 11:40:37'),
(207, 'App\\Models\\User', 3, 'My-token', '90d863d6533bbb90af9e52fa5a2925373db6c18f9e6b1cb32e3d1fe6b295a042', '[\"*\"]', '2022-12-17 08:58:16', '2022-12-17 07:24:11', '2022-12-17 08:58:16'),
(208, 'App\\Models\\User', 3, 'My-token', 'cc4c60cb32110d66d4ad6b89fa6c3dee739c6c575509fc9453f88067221d9f58', '[\"*\"]', '2022-12-22 09:03:09', '2022-12-21 17:55:21', '2022-12-22 09:03:09'),
(209, 'App\\Models\\User', 3, 'My-token', '3d124dfa2b0bb11befea226a676b1aeb4ec10d7344a38b4285e318b342a3ae4e', '[\"*\"]', '2023-01-07 09:31:08', '2023-01-06 14:46:16', '2023-01-07 09:31:08'),
(214, 'App\\Models\\User', 3, 'My-token', '7700a156d2eb81026b2137df523ea76ec3517f09b6848810742289384304d9f0', '[\"*\"]', NULL, '2023-01-25 07:01:34', '2023-01-25 07:01:34'),
(215, 'App\\Models\\User', 3, 'My-token', 'b7147bd66c5d8b806dcbe9a72f41b931d9f87de174886e9b963641fcba929564', '[\"*\"]', NULL, '2023-01-25 07:01:41', '2023-01-25 07:01:41'),
(216, 'App\\Models\\User', 3, 'My-token', '9cd7338dcf27616281329a01e857c4647f09037fd8c64e32d3ee581ba41afecd', '[\"*\"]', NULL, '2023-01-25 07:02:46', '2023-01-25 07:02:46'),
(217, 'App\\Models\\User', 3, 'My-token', '390f421f9db342143f013338fac7365b5f9778dfca36e70a895a1e41cd170f99', '[\"*\"]', NULL, '2023-01-25 07:03:33', '2023-01-25 07:03:33'),
(218, 'App\\Models\\User', 3, 'My-token', 'f457a24ffc865fde568bde0fdb76a5673eff480b4a1f0226b3e301f4679fe50a', '[\"*\"]', '2023-01-25 14:39:34', '2023-01-25 07:04:19', '2023-01-25 14:39:34'),
(172, 'App\\Models\\User', 3, 'My-token', '5c88199e94ee418e2d9649ac9f99594de264430954f5a828f250162cb09fdc3c', '[\"*\"]', '2022-10-27 16:55:02', '2022-10-27 12:27:23', '2022-10-27 16:55:02'),
(173, 'App\\Models\\User', 3, 'My-token', 'c9b3cf06fa1962268f5dc4bdb09f901bb8366efee194a127e20b83391cde3e5c', '[\"*\"]', '2022-10-28 08:21:01', '2022-10-27 20:39:52', '2022-10-28 08:21:01'),
(174, 'App\\Models\\User', 3, 'My-token', '71be1ee8cfc9b120e094fc9b464048921acf15c6c99a88aee1ec28c450283a6a', '[\"*\"]', '2022-10-31 15:45:20', '2022-10-31 13:30:22', '2022-10-31 15:45:20'),
(179, 'App\\Models\\User', 3, 'My-token', 'e4a9e0198f4fcd26a361152df20289abea7a10b32da70534f234ecdbc8b9eab7', '[\"*\"]', '2022-11-01 11:41:20', '2022-11-01 10:03:55', '2022-11-01 11:41:20'),
(176, 'App\\Models\\User', 2, 'My-token', 'acd4093d019eb6588fffe39801305737c49d617f6630ea5df26a30decda58d7c', '[\"*\"]', '2022-11-01 06:55:20', '2022-11-01 05:45:35', '2022-11-01 06:55:20'),
(163, 'App\\Models\\User', 6, 'My-token', '62197c96d84428be707ae1ab453a9581f60e93a1730c811d42e9096afa91455a', '[\"*\"]', '2022-10-21 11:29:26', '2022-10-21 11:29:15', '2022-10-21 11:29:26'),
(164, 'App\\Models\\User', 7, 'My-token', '79ae2b57925c963dcba5fbadad4d0acfea3603a0ec78929a110df7c78e72f1a8', '[\"*\"]', '2022-10-21 11:31:47', '2022-10-21 11:31:46', '2022-10-21 11:31:47'),
(192, 'App\\Models\\User', 3, 'My-token', '36045179e21d4c11ffe640422e57a1046e2fdb0a511ead57dd53a2686462a90a', '[\"*\"]', '2022-11-28 11:53:34', '2022-11-23 10:00:31', '2022-11-28 11:53:34'),
(191, 'App\\Models\\User', 3, 'My-token', '47129f03a1751232c3b15660c06577441cbc86174e44f0ec5a65a1bce06105f6', '[\"*\"]', '2022-11-22 09:09:36', '2022-11-22 05:56:57', '2022-11-22 09:09:36'),
(190, 'App\\Models\\User', 3, 'My-token', '9e245fb6908438fafe0dbd01f1197ea4090cfc9bf94436e3ecc91c58d7712351', '[\"*\"]', '2022-11-22 05:52:18', '2022-11-19 06:34:19', '2022-11-22 05:52:18'),
(188, 'App\\Models\\User', 3, 'My-token', 'c415775aa543558273ef0dc028caa237526f6b27321989ee274478f85a909aaa', '[\"*\"]', '2022-11-14 14:03:43', '2022-11-12 15:35:09', '2022-11-14 14:03:43'),
(189, 'App\\Models\\User', 3, 'My-token', '8a0614f1f3276f3dbf1cc680ab38276cf5cfccbe54cf376e37af7c3661ba24eb', '[\"*\"]', '2022-11-15 10:07:35', '2022-11-15 07:29:39', '2022-11-15 10:07:35'),
(187, 'App\\Models\\User', 3, 'My-token', '2189120d8cfddaa0c5c9c647e3e80838e9447abd90f32ef4a69efe5c50e7358a', '[\"*\"]', '2022-11-12 10:32:24', '2022-11-12 10:15:51', '2022-11-12 10:32:24'),
(186, 'App\\Models\\User', 3, 'My-token', '4dcb48ddbc5850dc324a13d88d2426f29e5f0a835c74edf6c5772587243b520f', '[\"*\"]', '2022-11-12 10:38:44', '2022-11-12 06:36:50', '2022-11-12 10:38:44'),
(184, 'App\\Models\\User', 3, 'My-token', '75becd93b882fa9f48d42ce28d1c5ca3c4b98cc37d093d6445da0d22bdb02e5b', '[\"*\"]', '2022-11-10 06:15:16', '2022-11-10 05:56:17', '2022-11-10 06:15:16'),
(183, 'App\\Models\\User', 3, 'My-token', '18201e02467b851c991bbc6de5ffd101ff27604856bcaf2d75bb5d421f7d0ba7', '[\"*\"]', '2022-11-09 08:32:57', '2022-11-09 05:32:14', '2022-11-09 08:32:57'),
(182, 'App\\Models\\User', 3, 'My-token', 'edf1730bcd5da7c79b66c18a9de83216c0ed9ebc068ac68a4e2d2be4b9eb4aad', '[\"*\"]', '2022-11-08 10:37:32', '2022-11-08 05:39:14', '2022-11-08 10:37:32'),
(226, 'App\\Models\\User', 3, 'My-token', 'f1e26141808a7955a2bfb0a4db91082705400ff7c3dd2f682ed117c93286e5e4', '[\"*\"]', '2023-03-20 12:25:19', '2023-02-03 10:07:37', '2023-03-20 12:25:19'),
(180, 'App\\Models\\User', 3, 'My-token', '27d84d293d732448c4dd995a24255a70c32d8d872344a0008f85f87a136889f2', '[\"*\"]', '2022-11-03 13:48:57', '2022-11-03 05:36:16', '2022-11-03 13:48:57'),
(177, 'App\\Models\\User', 2, 'My-token', '2195af148cf3cf7227e47168bf3bbcbfd25079d9383a6ab343032f5bf5aa3b99', '[\"*\"]', '2022-11-01 12:09:00', '2022-11-01 09:14:38', '2022-11-01 12:09:00'),
(171, 'App\\Models\\User', 3, 'My-token', 'abe404bbebb1340f201112599d610acc0e07684b9a040b5c515dcd1cd3b2ec76', '[\"*\"]', '2022-10-26 09:25:55', '2022-10-26 05:59:21', '2022-10-26 09:25:55'),
(136, 'App\\Models\\User', 14, 'my-app-token', '5af87064073fdad3f6c36cfa2d64807437f41a25ca23b2d42a01de9f9bb4d758', '[\"*\"]', '2022-09-22 11:42:39', '2022-09-22 11:42:37', '2022-09-22 11:42:39'),
(143, 'App\\Models\\User', 4, 'my-app-token', 'a4e7f8f5df9d9e7e5002d993f9a94db48788a307cfa79276ddbf110acd82ae58', '[\"*\"]', '2022-09-22 12:57:47', '2022-09-22 12:01:28', '2022-09-22 12:57:47'),
(170, 'App\\Models\\User', 3, 'my-app-token', 'd0ed4558e0a06bca91d936ce021cca9410f9b1b35b85fbff1270ea4dc8d25b07', '[\"*\"]', '2022-10-25 09:35:37', '2022-10-25 06:04:09', '2022-10-25 09:35:37'),
(144, 'App\\Models\\User', 4, 'My-token', 'd4de70a35dff811f8c3fe1edc8c2fd4ce4c7aac43e0f6ec746712f638508a38d', '[\"*\"]', '2022-10-27 12:25:36', '2022-09-22 13:06:58', '2022-10-27 12:25:36'),
(250, 'App\\Models\\User', 3, 'My-token', '280471bbbd12af405ff5773a764533b3d7bff62ee149b2d2bbb2718912178863', '[\"*\"]', '2023-03-03 08:07:43', '2023-03-03 08:07:25', '2023-03-03 08:07:43'),
(251, 'App\\Models\\User', 3, 'My-token', 'aa12023061b2f7821ba336ea76f10364797882c1b861de43d1b7c87004df6ea5', '[\"*\"]', '2023-03-03 08:13:02', '2023-03-03 08:12:59', '2023-03-03 08:13:02'),
(252, 'App\\Models\\User', 3, 'My-token', '48201f3c4a027f38e2b1591f7316cd5b86e22e56834b906e805a1d3ca72c2efa', '[\"*\"]', '2023-03-03 08:28:24', '2023-03-03 08:28:18', '2023-03-03 08:28:24'),
(253, 'App\\Models\\User', 3, 'My-token', 'e4306c6b54588cf6f31edf75e196251f5fe6955f36ae29c11be401f302cc2641', '[\"*\"]', '2023-03-03 08:32:36', '2023-03-03 08:32:32', '2023-03-03 08:32:36'),
(254, 'App\\Models\\User', 3, 'My-token', '452bdda7326248f53c7a078bfdd6c71edb43a4bbb81671daba50c1285978e232', '[\"*\"]', '2023-03-03 08:34:27', '2023-03-03 08:34:24', '2023-03-03 08:34:27'),
(255, 'App\\Models\\User', 3, 'My-token', '2b3fa4ef319d0fd35a185c51585435a991d0fbf74c71cdf2a155ad645d785422', '[\"*\"]', '2023-03-03 08:39:22', '2023-03-03 08:39:17', '2023-03-03 08:39:22'),
(256, 'App\\Models\\User', 3, 'My-token', 'a3d046a17113b62ba6b9eb30a2efed12b51e5c922941e12251e63a9e59570688', '[\"*\"]', '2023-03-03 08:43:21', '2023-03-03 08:43:17', '2023-03-03 08:43:21'),
(257, 'App\\Models\\User', 3, 'My-token', 'f5fc75c4652422e3e5be1e86d35fac69774347ac191968910228a3a5e7c3d455', '[\"*\"]', '2023-03-03 08:51:05', '2023-03-03 08:50:47', '2023-03-03 08:51:05'),
(261, 'App\\Models\\User', 3, 'My-token', '7dcc845fd68823c3718549bd7a1b0cf74391826c09d4e1c4e27710b65e79d4c5', '[\"*\"]', '2023-03-09 10:01:17', '2023-03-03 10:48:23', '2023-03-09 10:01:17'),
(262, 'App\\Models\\User', 2, 'My-token', 'cdb92b88edc6bc7dcb8daf68e689d3e6480376bbcd689a21e9f89a0e597ca0dc', '[\"*\"]', '2023-03-07 07:41:00', '2023-03-07 07:39:48', '2023-03-07 07:41:00'),
(263, 'App\\Models\\User', 3, 'My-token', 'e6b7efd70065de6e334b651ca8d7857d299b38891f74a8daa8b0d086986d48d2', '[\"*\"]', '2023-03-09 10:02:50', '2023-03-09 10:01:57', '2023-03-09 10:02:50'),
(264, 'App\\Models\\User', 3, 'My-token', '9d70b22d47dda2633b8868af8daf5654161ce316cf6e1ed176b6a62f3a69195b', '[\"*\"]', '2023-03-15 08:25:36', '2023-03-09 10:03:41', '2023-03-15 08:25:36'),
(265, 'App\\Models\\User', 3, 'My-token', '10a38bcba5d7bb7ad087070ebb7154fd8a955ae6d0bdc283dfab7c7e78470c3f', '[\"*\"]', '2023-03-20 09:16:15', '2023-03-15 08:27:03', '2023-03-20 09:16:15'),
(274, 'App\\Models\\User', 3, 'My-token', '9c5833a992a6cbbdcfeb55c6dac9e5f310d64f088c14f4747d1782c2efb68255', '[\"*\"]', '2023-03-23 06:32:17', '2023-03-22 08:34:26', '2023-03-23 06:32:17'),
(271, 'App\\Models\\User', 3, 'My-token', 'ce3c55c4a0528f2d4c7d780736d4e9ff49a41ca583c3860f0f94b90c0e84c67a', '[\"*\"]', '2023-03-21 16:37:28', '2023-03-21 06:02:21', '2023-03-21 16:37:28'),
(272, 'App\\Models\\User', 17, 'My-token', 'a25fb8317e18475b7767f605160b4e713246a5fd43c3a9dbd4a0c4d998cf7572', '[\"*\"]', '2023-03-21 06:34:15', '2023-03-21 06:34:11', '2023-03-21 06:34:15'),
(275, 'App\\Models\\User', 3, 'My-token', '9eaa4ff89a612f2042eb1461f5bfdc3ef5d1372369697d5fd8edd1372e24e93d', '[\"*\"]', '2023-03-27 08:31:04', '2023-03-27 08:12:45', '2023-03-27 08:31:04'),
(277, 'App\\Models\\User', 3, 'My-token', 'a7bdb6505f236a4e716f5f0b1ef2ff454ccbe0cda7060c55c348206906070319', '[\"*\"]', '2023-04-04 09:20:06', '2023-04-04 06:46:20', '2023-04-04 09:20:06'),
(278, 'App\\Models\\User', 3, 'My-token', '59b7dd3c3a00683878ec6665f9c82dddeef8b68a8571700ef67f17c44274bde8', '[\"*\"]', '2023-04-04 10:09:36', '2023-04-04 09:46:10', '2023-04-04 10:09:36'),
(279, 'App\\Models\\User', 3, 'My-token', '4999fc28cd8e42cafe1bb7131c1754679280941d2fa4d7c7359703929277d9a5', '[\"*\"]', NULL, '2023-04-05 05:56:43', '2023-04-05 05:56:43'),
(280, 'App\\Models\\User', 3, 'My-token', '7f01386947fd07c060a0995b9345ae3312d5f073bd39c8557cdc76f24e5e4f2e', '[\"*\"]', '2023-04-05 05:58:51', '2023-04-05 05:57:46', '2023-04-05 05:58:51'),
(281, 'App\\Models\\User', 3, 'My-token', 'cdc8520afc24ede1b0224a1682cd050b67c4313b46e4231e4fe61e9917886eb7', '[\"*\"]', '2023-04-05 07:27:27', '2023-04-05 07:26:48', '2023-04-05 07:27:27'),
(282, 'App\\Models\\User', 3, 'My-token', '800c6c034ed348bf825fea25a80781219b30b250780ac593499111d03dcd4013', '[\"*\"]', NULL, '2023-04-05 08:16:16', '2023-04-05 08:16:16'),
(285, 'App\\Models\\User', 3, 'My-token', 'f7f1fbbfa96f8f00217f3069e72f09ce9aa832c3c003d3d6c7a10cf50081cb88', '[\"*\"]', '2023-04-11 08:11:07', '2023-04-05 15:24:45', '2023-04-11 08:11:07'),
(289, 'App\\Models\\User', 3, 'My-token', '0edc91097fce7cb5577ddecd6763586d3c0f3c01c35e4a8cb315fd58b167b6ff', '[\"*\"]', '2023-04-13 11:52:13', '2023-04-13 10:22:47', '2023-04-13 11:52:13'),
(290, 'App\\Models\\User', 3, 'My-token', '086fc7a4227f1d6f8b34017dad58c026b3327e0267122379ee310fe0a5891ce8', '[\"*\"]', '2023-04-13 13:45:05', '2023-04-13 12:34:38', '2023-04-13 13:45:05'),
(291, 'App\\Models\\User', 3, 'My-token', 'e74dba152b000d0f831db65a949daf0d1319d1b72243039d208804606645da6d', '[\"*\"]', '2023-04-17 12:10:03', '2023-04-17 08:19:27', '2023-04-17 12:10:03'),
(292, 'App\\Models\\User', 3, 'My-token', 'a7b26cd0d5e3d90448d3e16866726a26a7101754c11e14e8995d746e9e4b9cfc', '[\"*\"]', NULL, '2023-04-18 06:09:20', '2023-04-18 06:09:20'),
(293, 'App\\Models\\User', 3, 'My-token', '8d260e0ff2503f4f04800fa55ae7c2672b90b0e2bb4751fccb7f1ccde6da8725', '[\"*\"]', '2023-04-17 06:41:41', '2023-04-18 06:26:27', '2023-04-17 06:41:41'),
(294, 'App\\Models\\User', 3, 'My-token', '600836e2b09abad21affc853056844ea1610a3bd4c9bd69f5823dfd284f2c6ed', '[\"*\"]', '2023-04-17 06:41:52', '2023-04-17 06:41:50', '2023-04-17 06:41:52'),
(295, 'App\\Models\\User', 3, 'My-token', '25a038f30d82fb7f8803751578f288cca8522cc99d75dff558cf1a2f04ca92d0', '[\"*\"]', '2023-04-17 06:58:42', '2023-04-17 06:58:41', '2023-04-17 06:58:42'),
(296, 'App\\Models\\User', 3, 'My-token', 'b142e8786d7d95ad12f241a2adafad047f3fa630bda12e3fcf66bc422bba5061', '[\"*\"]', '2023-04-17 06:59:41', '2023-04-17 06:59:39', '2023-04-17 06:59:41'),
(297, 'App\\Models\\User', 3, 'My-token', '98a525ba2190d1c5e1c790796a3f5b054b27c0c6067d74075704cd9a94e66801', '[\"*\"]', NULL, '2023-04-17 06:59:40', '2023-04-17 06:59:40'),
(298, 'App\\Models\\User', 3, 'My-token', 'f9ce6e5a3bdef76587f44218faa1c429b2f83fed7f609f94bb14bacb24a0dcb7', '[\"*\"]', '2023-04-17 07:00:41', '2023-04-17 07:00:40', '2023-04-17 07:00:41'),
(299, 'App\\Models\\User', 3, 'My-token', '50cc2c3dd5f66638dce7946fb21264a65ccbc61b87b087dd480d17606a16e056', '[\"*\"]', '2023-04-17 07:49:36', '2023-04-17 07:49:32', '2023-04-17 07:49:36'),
(300, 'App\\Models\\User', 3, 'My-token', 'b8052a8201569a3cf7a8f215944e65e21cd024a6d545c5d381f5bb4518fb0429', '[\"*\"]', '2023-04-17 07:53:41', '2023-04-17 07:53:39', '2023-04-17 07:53:41'),
(302, 'App\\Models\\User', 3, 'My-token', '1083bdbace3d230154d731cccdfbf797df02892eab4c8fdc7c779730ff69ded6', '[\"*\"]', '2023-04-17 07:55:18', '2023-04-17 07:55:16', '2023-04-17 07:55:18'),
(303, 'App\\Models\\User', 3, 'My-token', '57fc62fdf43b8fb82e6bf475e32624757ec5d16755ddae1f978e51cd9f115c42', '[\"*\"]', '2023-04-17 07:56:54', '2023-04-17 07:56:52', '2023-04-17 07:56:54'),
(304, 'App\\Models\\User', 3, 'My-token', '129d61aa12503b0d586b122f832845821be8af471a68d6558aa168b1bc42a9ec', '[\"*\"]', '2023-04-17 07:58:07', '2023-04-17 07:58:05', '2023-04-17 07:58:07'),
(305, 'App\\Models\\User', 3, 'My-token', 'f7bdc719453206087d3bff0d413d4d46cd428f3998a6b1d613f70df2c1a93adf', '[\"*\"]', '2023-04-17 08:29:42', '2023-04-17 08:29:38', '2023-04-17 08:29:42'),
(311, 'App\\Models\\User', 3, 'My-token', '3358f1e764c68e1818a51f018e05c5f758c540df2b9d8e601b05e842213d9c78', '[\"*\"]', '2023-04-21 08:36:42', '2023-04-21 08:25:33', '2023-04-21 08:36:42'),
(310, 'App\\Models\\User', 3, 'My-token', 'f753b5b5861f6216c3b204651257ee8cc92d6d3698d83d663ceb71e02386a9d8', '[\"*\"]', '2023-04-20 09:33:39', '2023-04-20 06:42:31', '2023-04-20 09:33:39'),
(312, 'App\\Models\\User', 3, 'My-token', '7c9da851f2abe89baaaedb2722a77d40ad067a833d6d5c37c5687bd8ea75d5a0', '[\"*\"]', '2023-04-21 09:33:02', '2023-04-21 08:42:19', '2023-04-21 09:33:02'),
(313, 'App\\Models\\User', 3, 'My-token', 'b6bd1cfb5b84f91734af8edc3b91552cdfa03bd7dae7ae5e472e44095dc37bb9', '[\"*\"]', '2023-04-24 11:24:54', '2023-04-21 09:33:13', '2023-04-24 11:24:54'),
(345, 'App\\Models\\User', 3, 'My-token', 'b0eb513386dc1ca93205ceb9a09fb5464c2670faafa8d34c3360b5525be61c7d', '[\"*\"]', '2023-08-14 16:16:20', '2023-08-13 15:51:01', '2023-08-14 16:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `price_changes`
--

DROP TABLE IF EXISTS `price_changes`;
CREATE TABLE IF NOT EXISTS `price_changes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint DEFAULT NULL,
  `old_price` bigint DEFAULT '0',
  `new_price` bigint DEFAULT '0',
  `type` int NOT NULL COMMENT '0-shop, 1-store',
  `branch_id` bigint DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `user_id` bigint NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_changes_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` bigint DEFAULT NULL,
  `efriscategory` int DEFAULT NULL,
  `product_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `minimum_quantity` double DEFAULT NULL,
  `unit_id` bigint DEFAULT NULL,
  `selling_price` double DEFAULT NULL,
  `reserve_price` double DEFAULT NULL,
  `wholesale_unitprice` int NOT NULL DEFAULT '0',
  `wholesale_reserveprice` int NOT NULL DEFAULT '0',
  `is_product` tinyint NOT NULL DEFAULT '0',
  `vat` tinyint NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1=Active 0=deleted',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `buy` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_item_id_index` (`item_id`),
  KEY `products_branch_id_index` (`branch_id`),
  KEY `products_business_id_index` (`business_id`),
  KEY `unit_id` (`unit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `item_id`, `efriscategory`, `product_name`, `product_code`, `quantity`, `minimum_quantity`, `unit_id`, `selling_price`, `reserve_price`, `wholesale_unitprice`, `wholesale_reserveprice`, `is_product`, `vat`, `status`, `branch_id`, `business_id`, `user_id`, `created_at`, `updated_at`, `buy`) VALUES
(1, 1, NULL, 'MTN Uganda Translation', NULL, NULL, NULL, 0, 300000, 300000, 0, 0, 0, 0, 1, 1, 1, 3, '2023-08-14 07:11:05', '2023-08-14 07:11:10', NULL),
(2, 2, NULL, 'Pepsi', NULL, NULL, NULL, 0, 200000, 200000, 0, 0, 0, 0, 1, 1, 1, 3, '2023-08-14 07:11:10', '2023-08-14 07:11:10', NULL),
(3, 3, NULL, 'Sweet Pepsi', NULL, 30, 10, 1, 200, 200, 0, 0, 1, 0, 1, 1, 1, 3, '2023-08-14 07:11:17', '2023-08-14 07:11:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE IF NOT EXISTS `product_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1=Active 0=deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `branch_id`, `business_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Default', 1, 1, 3, 1, '2023-08-14 07:11:01', '2023-08-14 07:11:10'),
(2, 'Sweets', 1, 1, 3, 1, '2023-08-14 07:11:17', '2023-08-14 07:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `product_units`
--

DROP TABLE IF EXISTS `product_units`;
CREATE TABLE IF NOT EXISTS `product_units` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `product_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `base_quantity` double DEFAULT NULL,
  `selling_price` double DEFAULT NULL,
  `reserve_price` double DEFAULT NULL,
  `wholesale_unitprice` int NOT NULL DEFAULT '0',
  `wholesale_reserveprice` int NOT NULL DEFAULT '0',
  `is_base` tinyint NOT NULL DEFAULT '0',
  `stock_movement` bigint NOT NULL DEFAULT '0',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_units`
--

INSERT INTO `product_units` (`id`, `product_id`, `product_code`, `unit_id`, `base_quantity`, `selling_price`, `reserve_price`, `wholesale_unitprice`, `wholesale_reserveprice`, `is_base`, `stock_movement`, `branch_id`, `business_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 1, 1, 200, 200, 0, 0, 1, 0, 1, 1, 3, '2023-08-14 07:11:17', '2023-08-14 07:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `receipt` int DEFAULT NULL,
  `category` int NOT NULL DEFAULT '0' COMMENT '0=shop 1=store',
  `supplier_id` int DEFAULT NULL,
  `total` double DEFAULT NULL,
  `paid` double DEFAULT NULL,
  `new_status` int NOT NULL DEFAULT '0',
  `discount` double NOT NULL DEFAULT '0',
  `date` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_carts`
--

DROP TABLE IF EXISTS `purchase_carts`;
CREATE TABLE IF NOT EXISTS `purchase_carts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `unit` bigint DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `unit_sellingprice` double DEFAULT NULL,
  `batch` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `expiry` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=direct_purchase 1=store',
  `category` int NOT NULL DEFAULT '0' COMMENT '0=shop 1=store',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

DROP TABLE IF EXISTS `purchase_details`;
CREATE TABLE IF NOT EXISTS `purchase_details` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `unit` bigint DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `buying_price` double DEFAULT NULL,
  `selling_price` double DEFAULT NULL,
  `batch` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` int NOT NULL DEFAULT '0' COMMENT '0=shop 1=store',
  `return_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=no 1=yes',
  `purchase_id` int DEFAULT NULL,
  `returned` int NOT NULL DEFAULT '0',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit` (`unit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE IF NOT EXISTS `purchase_orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_id` int DEFAULT NULL,
  `paid` double DEFAULT NULL COMMENT 'paid in advance',
  `received` double DEFAULT NULL COMMENT 'amount worth items received',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payments`
--

DROP TABLE IF EXISTS `purchase_payments`;
CREATE TABLE IF NOT EXISTS `purchase_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_id` int DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `mode` int DEFAULT NULL,
  `receipt` bigint DEFAULT NULL,
  `date` date DEFAULT NULL,
  `recno` int DEFAULT NULL,
  `month` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` year DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1.Purchase Payment, 2.Previous Creditor',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

DROP TABLE IF EXISTS `purchase_returns`;
CREATE TABLE IF NOT EXISTS `purchase_returns` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `unit_buying_price` double DEFAULT NULL COMMENT 'buying price for each product',
  `amount_refunded` double DEFAULT NULL COMMENT 'total amount refund',
  `batch` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `reason` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode` int DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '0=shop, 1=store',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_settings`
--

DROP TABLE IF EXISTS `receipt_settings`;
CREATE TABLE IF NOT EXISTS `receipt_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `print_receipt_after_sale` int NOT NULL DEFAULT '1',
  `print_receipt_after_purchase` int NOT NULL DEFAULT '1',
  `font_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `font_size` double DEFAULT NULL,
  `indicate_website` tinyint DEFAULT '0',
  `indicate_tin` tinyint DEFAULT '0',
  `indicate_business_name` tinyint DEFAULT '1',
  `indicate_branch_name` int NOT NULL DEFAULT '0',
  `indicate_goods_not_returnable` tinyint DEFAULT '0',
  `indicate_goods_vat_inclusive` tinyint DEFAULT '0',
  `indicate_user` tinyint DEFAULT '1',
  `indicate_customer` tinyint DEFAULT '0',
  `show_description` int NOT NULL DEFAULT '0',
  `show_pickingdate` int NOT NULL DEFAULT '0',
  `receipt_type` int NOT NULL DEFAULT '1',
  `show_contacts` int NOT NULL DEFAULT '1',
  `print_deposits` int NOT NULL DEFAULT '0',
  `show_expiry_date` int NOT NULL DEFAULT '0',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipt_settings`
--

INSERT INTO `receipt_settings` (`id`, `print_receipt_after_sale`, `print_receipt_after_purchase`, `font_type`, `font_size`, `indicate_website`, `indicate_tin`, `indicate_business_name`, `indicate_branch_name`, `indicate_goods_not_returnable`, `indicate_goods_vat_inclusive`, `indicate_user`, `indicate_customer`, `show_description`, `show_pickingdate`, `receipt_type`, `show_contacts`, `print_deposits`, `show_expiry_date`, `branch_id`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, 1, 1, 1, 1, 1, 0, 1, 0, 1, 1, 1, 0, 1, 1, 1, 1, '2023-08-12 21:00:00', '2023-08-12 21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `reconcilations`
--

DROP TABLE IF EXISTS `reconcilations`;
CREATE TABLE IF NOT EXISTS `reconcilations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `num` int NOT NULL DEFAULT '0',
  `type` int NOT NULL DEFAULT '0',
  `user_id` bigint NOT NULL,
  `branch_id` bigint NOT NULL,
  `business_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reconcilations_user_id_index` (`user_id`),
  KEY `reconcilations_branch_id_index` (`branch_id`),
  KEY `reconcilations_business_id_index` (`business_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reconcilation_logs`
--

DROP TABLE IF EXISTS `reconcilation_logs`;
CREATE TABLE IF NOT EXISTS `reconcilation_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint DEFAULT NULL,
  `unit_id` bigint DEFAULT NULL,
  `stockreconciliation_id` int NOT NULL,
  `base_quantity` bigint DEFAULT NULL,
  `actual_quantity` bigint DEFAULT NULL,
  `type` int NOT NULL DEFAULT '0',
  `branch_id` bigint DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reconcilation_logs_product_id_index` (`product_id`),
  KEY `reconcilation_logs_unit_id_index` (`unit_id`),
  KEY `reconcilation_logs_base_quantity_index` (`base_quantity`),
  KEY `reconcilation_logs_actual_quantity_index` (`actual_quantity`),
  KEY `reconcilation_logs_branch_id_index` (`branch_id`),
  KEY `reconcilation_logs_business_id_index` (`business_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `total` double DEFAULT NULL,
  `paid` double DEFAULT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `received` bigint NOT NULL DEFAULT '0',
  `sale_date` date DEFAULT NULL,
  `receipt` int DEFAULT NULL,
  `offline_receipt` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picking_date` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picked` int NOT NULL DEFAULT '0',
  `date_picked` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=bad debtor 1=active debtor',
  `new_status` int NOT NULL DEFAULT '0',
  `transaction_status` int NOT NULL DEFAULT '0',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_carts`
--

DROP TABLE IF EXISTS `sale_carts`;
CREATE TABLE IF NOT EXISTS `sale_carts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `quantity` double DEFAULT NULL,
  `quantity_taken` double DEFAULT NULL,
  `selling_price` double DEFAULT NULL,
  `unit` int DEFAULT NULL,
  `batch_id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hold` int NOT NULL DEFAULT '0' COMMENT '0=notheld 1=held',
  `holdId` bigint DEFAULT NULL,
  `cart_status` int NOT NULL DEFAULT '0',
  `description` varchar(3000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `holdId` (`holdId`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_details`
--

DROP TABLE IF EXISTS `sale_details`;
CREATE TABLE IF NOT EXISTS `sale_details` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `quantity_remaining` double DEFAULT '0',
  `buying_price` double DEFAULT NULL,
  `average_buyingprice` double DEFAULT NULL,
  `selling_price` double DEFAULT NULL,
  `unit` int DEFAULT NULL,
  `batch_id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `returned` int NOT NULL DEFAULT '0',
  `order_status` int NOT NULL DEFAULT '0' COMMENT '0-pending, 1-prepared, 2-served',
  `description` varchar(3000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_holds`
--

DROP TABLE IF EXISTS `sale_holds`;
CREATE TABLE IF NOT EXISTS `sale_holds` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint NOT NULL,
  `identification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint NOT NULL,
  `business_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_holds_user_id_index` (`user_id`),
  KEY `sale_holds_branch_id_index` (`branch_id`),
  KEY `sale_holds_business_id_index` (`business_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `actual_quantity` double DEFAULT NULL,
  `buying_price` double DEFAULT NULL,
  `average_buyingprice` double DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1=successfull 0=unsuccessfull',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `quantity`, `actual_quantity`, `buying_price`, `average_buyingprice`, `branch_id`, `business_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 0, NULL, 0, NULL, 1, 1, 3, 1, '2023-07-03 16:07:20', '2023-07-03 16:07:20'),
(2, 2, 0, NULL, 50000, NULL, 1, 1, 3, 1, '2023-07-05 19:09:51', '2023-07-05 19:09:51'),
(3, 3, 30, NULL, 100, 100, 1, 1, 3, 1, '2023-08-14 07:11:17', '2023-08-14 07:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `from_quantity` bigint NOT NULL DEFAULT '0',
  `quantity_in` double DEFAULT NULL,
  `quantity_out` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_reconciliations`
--

DROP TABLE IF EXISTS `stock_reconciliations`;
CREATE TABLE IF NOT EXISTS `stock_reconciliations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `recId` bigint DEFAULT NULL,
  `rec_date` date DEFAULT NULL,
  `from` double DEFAULT NULL,
  `to` double DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `buying_price` double NOT NULL DEFAULT '0',
  `user_id` int DEFAULT NULL,
  `branch_id` bigint DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recId` (`recId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` int DEFAULT NULL,
  `credit_limit` int DEFAULT '0',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1=Active 0=deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `email`, `address`, `contact`, `credit_limit`, `branch_id`, `business_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Test Supplier', 'test@gmail.com', 'Norway', 777896778, 200000, 1, 1, 1, 1, '2022-02-26 07:26:01', '2022-02-26 07:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_accounts`
--

DROP TABLE IF EXISTS `supplier_accounts`;
CREATE TABLE IF NOT EXISTS `supplier_accounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `amount_in` double DEFAULT '0',
  `amount_out` double DEFAULT '0',
  `supplier_id` int DEFAULT NULL,
  `tId` int DEFAULT '0',
  `mode` int DEFAULT NULL,
  `refno` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` int NOT NULL DEFAULT '1',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_deposits`
--

DROP TABLE IF EXISTS `supplier_deposits`;
CREATE TABLE IF NOT EXISTS `supplier_deposits` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `mode` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `branch_id` bigint DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_deposits_supplier_id_index` (`supplier_id`),
  KEY `supplier_deposits_mode_index` (`mode`),
  KEY `supplier_deposits_user_id_index` (`user_id`),
  KEY `supplier_deposits_branch_id_index` (`branch_id`),
  KEY `supplier_deposits_business_id_index` (`business_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_passwords`
--

DROP TABLE IF EXISTS `temp_passwords`;
CREATE TABLE IF NOT EXISTS `temp_passwords` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `branch_id` bigint DEFAULT NULL,
  `temp_password` bigint DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` int DEFAULT NULL COMMENT 'sale=1, expense=2, purchase=3,sale_return=4,purchase_return=5,cashout=6,cashin=7,money transfer=8,customer deposit=9',
  `amount_in` double DEFAULT '0',
  `amount_out` double DEFAULT '0',
  `mode` int DEFAULT NULL,
  `tId` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `symbol`, `status`, `branch_id`, `business_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'pieces', 'pieces', 1, 1, 1, 3, '2023-08-14 07:11:17', '2023-08-14 07:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` int DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int DEFAULT NULL COMMENT 'Permission_id',
  `utype` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `initial_visit` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1-actvie,0-deleted,2-suspended',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `contact`, `email`, `password`, `role`, `utype`, `user_id`, `branch_id`, `business_id`, `initial_visit`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Test User', 'testuser', 2147483647, 'ptersont9545@gmail.com', '$2y$10$G4kf7PnoiU7IX6RxyvxdOum6sWTfLpAe98OoXcgskaHRYnOw1ZdNu', 1, 0, 1, 1, 1, 1, 1, '2023-08-13 21:00:00', '2023-08-13 21:00:00');

-- --------------------------------------------------------

--
-- Stand-in structure for view `zeros`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `zeros`;
CREATE TABLE IF NOT EXISTS `zeros` (
`id` bigint unsigned
,`item_id` bigint
,`efriscategory` int
,`product_name` varchar(191)
,`product_code` varchar(191)
,`quantity` double
,`minimum_quantity` double
,`unit_id` bigint
,`selling_price` double
,`reserve_price` double
,`wholesale_unitprice` int
,`wholesale_reserveprice` int
,`is_product` tinyint
,`vat` tinyint
,`status` tinyint
,`branch_id` int
,`business_id` int
,`user_id` int
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `zeros`
--
DROP TABLE IF EXISTS `zeros`;

DROP VIEW IF EXISTS `zeros`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zeros`  AS SELECT `products`.`id` AS `id`, `products`.`item_id` AS `item_id`, `products`.`efriscategory` AS `efriscategory`, `products`.`product_name` AS `product_name`, `products`.`product_code` AS `product_code`, `products`.`quantity` AS `quantity`, `products`.`minimum_quantity` AS `minimum_quantity`, `products`.`unit_id` AS `unit_id`, `products`.`selling_price` AS `selling_price`, `products`.`reserve_price` AS `reserve_price`, `products`.`wholesale_unitprice` AS `wholesale_unitprice`, `products`.`wholesale_reserveprice` AS `wholesale_reserveprice`, `products`.`is_product` AS `is_product`, `products`.`vat` AS `vat`, `products`.`status` AS `status`, `products`.`branch_id` AS `branch_id`, `products`.`business_id` AS `business_id`, `products`.`user_id` AS `user_id`, `products`.`created_at` AS `created_at`, `products`.`updated_at` AS `updated_at` FROM `products` WHERE ((`products`.`quantity` = 0) AND (`products`.`business_id` = 1))  ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
