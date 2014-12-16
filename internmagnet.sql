-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2015 at 10:06 PM
-- Server version: 5.6.21-log
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `internmagnet`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Computer Science'),
(2, 'Marketing'),
(3, 'Finance'),
(4, 'Politics');

-- --------------------------------------------------------

--
-- Table structure for table `employer_categories`
--

CREATE TABLE IF NOT EXISTS `employer_categories` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `employer_id` int(5) NOT NULL,
  `category_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `employer_categories`
--

INSERT INTO `employer_categories` (`id`, `employer_id`, `category_id`) VALUES
(11, 105, 2),
(12, 105, 3);

-- --------------------------------------------------------

--
-- Table structure for table `magnetise`
--

CREATE TABLE IF NOT EXISTS `magnetise` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(5) NOT NULL,
  `to_user_id` int(5) NOT NULL,
  `timestamp` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `magnetise`
--

INSERT INTO `magnetise` (`id`, `from_user_id`, `to_user_id`, `timestamp`) VALUES
(3, 105, 71, 1421840483),
(5, 71, 105, 1421846612),
(7, 105, 100, 1421861359),
(8, 72, 105, 1421863118),
(9, 105, 72, 1421863336),
(10, 105, 103, 1421863378);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`) VALUES
(1, 'Java'),
(2, 'PHP'),
(3, 'Marketing'),
(4, 'JS'),
(5, 'Photography'),
(6, 'Photoshop'),
(7, 'Videography'),
(8, 'Leadership'),
(9, 'ASP'),
(10, 'C++'),
(11, 'Ruby'),
(12, 'Google'),
(13, 'OOPHP'),
(14, 'Facebook'),
(15, 'UML'),
(16, 'Dart.'),
(17, 'Advertising'),
(18, 'Art'),
(19, 'Bussiness'),
(20, 'Selling'),
(21, 'Filming'),
(22, 'AngularJS'),
(23, 'AJAX');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `password` varchar(129) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `category` int(11) NOT NULL,
  `visibility` varchar(10) NOT NULL,
  `bio` varchar(249) NOT NULL,
  `cv` varchar(100) NOT NULL,
  `year` varchar(10) NOT NULL,
  `userType` varchar(15) NOT NULL,
  `dateCreated` date NOT NULL,
  `verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `verified` (`verified`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=106 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `firstName`, `lastName`, `password`, `salt`, `email`, `category`, `visibility`, `bio`, `cv`, `year`, `userType`, `dateCreated`, `verified`) VALUES
(71, 'alicia93', 'Alicia', 'Sykes', '5b0ce74ba61d459fd68891a528a7e28b752d2293fa9ce25c17484115cf092bbd9b670f431dbb2766dbd32c56c3cecf756f85d2de2899d3e8908e983d9eb6edc7', 'YlMClpXFlnxsrmKTBXkhhdtssMXNCJU7', 'alicia2727@hotmail.co.uk', 1, 'visible', 'Software engineer specializing in web development, based in London', '71_1404670165.pdf', '3', 'student', '2014-06-16', 1),
(72, 'henry', 'Henry', 'Jones', 'c18e96e8cf99e59c003e5af07c47e9f45414138a11df3d34ab6303af66ebc09f3cd6136eff1035e52f782fa3207a92ba8c6ea783c8153317b9c27ee722deed0f', 'FztUTZ8z4dQXL0Wy6xhkEzcMxiyQjqG1', 'stackexchange@specialorange.org', 0, 'visible', 'Adventure sport lover studying photography looking hoping to go into a career in action photography ', '', '', 'student', '2014-06-20', 1),
(73, 'andrew', 'Andrew', '', '8217f8ddc4b605635872324b6ed47b7cb69c174f2165493a3fc245ab4e2eaf830144dba338c1c46791749f7d114e66a01a1d402e7974e811eb2e4ec314c9f6db', 'zcbnW6wzwTs8kwjCdrr31rKW3GgDXZOm', 'alexandre@alenonimo.com.br', 0, '', '', '', 'graduated', 'student', '2014-06-21', 0),
(75, 'Eeven', '', '', '53d1e69f6f35922360a152c27784d43e2588d991f5a2d8072febcdc7bd0191fd2d5ee286c9b49cdcf035a22239b8e7ddd7b8894e04cfcad44408a98ab23c8511', 'Jl4QQ87aCNWNKLms0rxn8W1E24hjtyr4', 'evensr@gmail.com', 4, '', 'Founded the politics society at university, passionate about politics  and current affairs', '', '2', 'student', '2014-06-27', 0),
(76, 'test', '', '', '928c67eba036f9238aee084f8136ad95218f832dcf07bd77e7298028afa5fd93a91582a09f7c8dec85c91d2d2ce3b44334e0f8588ef89be5f145d2fb885c0d0a', 'Z8X8BPwo6jTuX6n9i5FAUlPdEPpxwTjk', 'floatinglush@gmail.com', 0, '', '', '', '', 'student', '2014-06-30', 0),
(84, 'lampard49', '', '', '042fa553d16d9b6a2f09c54ae447c20f757474fb44d13a4efebf2629e8803dd1f4c5c1e5fe224cedeb9baaa7e0fcb909c77f60fc2804363bbd1b46009da6b98a', 'j2TYj342n76iGlgN3F1TQJq6C7Iay4nI', 'simonlambert@live.co.uk', 3, '', '', '', '2', 'intern', '2014-07-07', 0),
(85, 'bruce_9', '', '', 'd0f60388e24dae01a3ceca58261fa445a51e46948959adfdbb0c3cecb89e29f92eee5503624f6781c257e47acecca2a99fdd3dd473ee3ca50be7d4962cf87922', 'Hynplb5CJodJy9OrFxC6DP0d6YJ1D4nb', 'dombruce@hotmail.co.uk', 2, '', '', '', 'graduated', 'intern', '2014-07-07', 0),
(86, 'simonlambert', '', '', '1be5ba55f0c9a2352639cfceabf87b613eb1cb58a326e278c2f076eb157bf5d4405cbdd1fc1ccc004acd0e1ec5eb20cdb4563a48c4e03c8b1f6bba443c5bd329', 'PU1RQbD6ukRYSTR1laQGTjIHBDq0uqna', '11023910@brookes.co.uk', 3, '', '', '', '3', 'intern', '2014-07-07', 0),
(87, 'dom', '', '', '7550cbfe64ed7e5ad70eb0de9d0a6f7da76bf197a9fcfa881875a262c8de3c4037fe1ae9d71abb0c99cdc6d192bbde1ac974b4f1bd978faa9945a519dd937446', 'P3I3qD4OBIUOsdm6ryPNZ0syLofWwNmc', 'internmagnet@gmail.com', 1, '', '', '', 'graduated', 'intern', '2014-07-07', 1),
(94, 'simon', '', '', 'e2a929cbcf53044e854cec6eb1ca17aceb84185b8806a201771805c6c56dd25084ea1194fb7d2ab6ef498d491abcd4b658d928d223ca344c905e6f5ced2f2b70', 'oCcf3kgH7Ft6aw18SLytMbffP1bUEJ6T', 'simonlambert@live.co.uk', 3, '', '', '', '2', 'intern', '2014-08-18', 0),
(96, 'jjones010', 'john', 'jones', '75bdf91c7d4622757cb7c1f03a652dc2d87780cefd9ac513f4ee683a50013653997117001240f61e9d6bc62166c6b823020a47c9d11ef974dea063e2bfc292f2', 'D9AQiGxoyl5Xrhmz1DhabsAFabpp7tsA', 'jjones@hotmail.com', 1, '', '', '', 'none', 'student', '2014-08-27', 0),
(97, 'jjones011', 'jim', 'jones', '476c9cbcc1bc005d9fc5b329cb60d32df02f31525e702611d924558b3dc1161a3557ccef5ef0d697136e16cd0fd2f02b641f13141a42851ddaafde89dd5679bf', 'ynkBWbsSEpqi2gChiHl9i4iFiM65Us0j', 'jim@mail.com', 1, '', '', '', 'none', 'student', '2014-08-27', 0),
(98, 'jjones012', 'jack', 'jones', '66132c9852b2293886f2d938d64ee544e7138709615c6eb8cf9a4f5d651b2ff2911f475da65574adb53eb18bfc45283b3217803612bcdf280ba63f5861280d6f', 'QPK9RdmsG6Th1riebskmDFUlLAjh3ATJ', 'jackjones@mail.com', 1, 'visible', '', '', 'none', 'student', '2014-08-27', 0),
(99, 'cjones013', 'chris', 'jones', '7fe4dcc0747fb95d40673093bc679ea410a9526def80ea306d4b2ea21704795f7ad40021fd46e305758dea31a95f2210bd13da5d670f77b2808d97a53750be46', 'UGZD9qLU7zIjeerPmwHP7EkEaHyAyKJj', 'cj@mail.com', 1, 'visible', '', '', 'none', 'student', '2014-08-27', 0),
(100, 'jsmith01', 'john', 'smith', 'dcf767fae15d98a3c15a2d9a9f376a7248fec3e1b4435ed238b64baee1c9eeb05fd87749eebfb8e3b4ec011bc031fd12c34a911aab0f39d895a921789d99d4f4', 'jbt8WXFsat6E1wiE2IXpj9fQU641JKuT', 'Cornelius@MonkeyBrewster.com', 2, 'visible', 'I am a marketing student with a particular interest in retail marketing. I also have experience in filming, photography and art', '100_1410119920.pdf', '2', 'student', '2014-09-07', 1),
(101, 'tpoint', 'three', 'point', 'b7b208824882fab3c50830a843bc528183d67ed45200620d60e2d5acfba2860e141ebc746111b75ec3142eb3914fef1612cd481bd77567dfd5734a3a56739e75', 'I0zgwX01bLJBsMe9ARudekZWE3ERylY7', 'test2@mail.com', 3, 'visible', '', '', '3', 'student', '2015-01-20', 0),
(102, 'tpoint02', 'three', 'point', '2b7e7ba49875fd1ff9c0381e37117faabaac0f6f1a10171b54284b3f62e09996000152124718a5d3f717e27a4d2e68702c719a031416ea372fc22e54ae2d1b7c', 'a5ThbAWrQG2dRLEFrDMplERyKFSEn1jP', 'test2@mail.com', 3, '1', '', '', '3', 'student', '2015-01-20', 0),
(103, 'hcarter', 'henry', 'carter', 'a1a2f0dfd7475ec5b7a7060a87309461aaa7663a7bc3ff26f33bf2ae311fe35c4081a8553a5d50649435095719358d3105827472e10e7ec706320eded4886f2d', 'sx7FJAKfmZdb86XzcMU0klCyIE0O53xU', 'iamstupid@mail.com', 1, 'visible', '', '', '3', 'student', '2015-01-20', 1),
(104, 'fjohn', 'fred', 'john', '2a0a77fd4dd617dfed9d1f824eed27e4463c586273b585ab023b5912acf779eb8d0caa04fbb980c9dafbe61a4d7649cebaa11f57db5f7db140f01d9e4ebf7145', 'tD3Xp7XzBtHtDrFbCfFD8B5igHJ9DhYF', 'fredisdumb@mail.com', 1, 'visible', '', '', 'none', 'student', '2015-01-20', 0),
(105, 'importantcompany', 'importantcompany', '', 'a3f23e9792b1e0225548f3dde741761ea6fd543a2f8861bace689367cc275a620816e01e8e9afe23b00cf86a3c00a554c5d58310cccfad249c02f7e5bf078ca1', 'KHwqJ2tbeFK5HFwY6ExbTH0A5oDu6REN', 'bigdog@mail.com', 0, 'visible', 'Large marketing firm based in North London', '', 'none', 'employer', '2015-01-20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_information`
--

CREATE TABLE IF NOT EXISTS `user_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `value` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `user_information`
--

INSERT INTO `user_information` (`id`, `user_id`, `type`, `value`, `description`) VALUES
(1, 71, 'previous employer', 'University of Oxford', 'Worked as a software engineer programming in Java on an Oxford University research project, that analysed malaria data and used algorithms to  predict when an resistance to a vaccination may occur. Involved writing scalable programs capable of dealing with hundreds of thousands of patient records. Full-time for 3 months. 2013'),
(2, 71, 'school', 'St Johns Community School ', 'A Levels in computing, physics, and economics and AS levels in maths and photography.'),
(3, 71, 'current-employer', 'UTC Swindon', 'I currently work part time for Swindon University as head of their web team. It is my responsibility to maintain their website, ensuring that it is fully accessible and up to date.'),
(4, 80, 'school', 'John Hanson, Andover', 'completed 10 GCSE''s with A-C grades'),
(5, 100, 'previous-employer', 'Tesco', 'I was a shop assistant, and also gained expereience in the marketing department'),
(6, 100, 'university', 'UCL', 'Still studying'),
(7, 100, 'qualification', 'Certificated Camera Operator', 'Awarded in July 2013'),
(8, 71, 'university', 'Oxford Brookes', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE IF NOT EXISTS `user_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip` varchar(11) NOT NULL,
  `time` int(11) NOT NULL,
  `success` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=136 ;

--
-- Dumping data for table `user_logins`
--

INSERT INTO `user_logins` (`id`, `user_id`, `ip`, `time`, `success`) VALUES
(1, 66, '0', 1402393212, 1),
(2, 66, '0', 1402393214, 1),
(3, 61, '::1', 1402393350, 1),
(4, 0, '::1', 1402393420, 0),
(5, 0, '::1', 1402394807, 0),
(6, 0, '::1', 1402394813, 0),
(7, 61, '::1', 1402394849, 0),
(8, 0, '::1', 1402394859, 0),
(9, 61, '::1', 1402394870, 0),
(10, 61, '::1', 1402394886, 1),
(11, 61, '::1', 1402476677, 1),
(12, 0, '::1', 1402476695, 0),
(13, 0, '::1', 1402476707, 0),
(14, 67, '::1', 1402476737, 1),
(15, 67, '::1', 1402476841, 1),
(16, 67, '::1', 1402476857, 1),
(17, 67, '::1', 1402476876, 1),
(18, 68, '::1', 1402478128, 1),
(19, 61, '::1', 1402488471, 1),
(20, 61, '::1', 1402580709, 0),
(21, 61, '::1', 1402580719, 0),
(22, 61, '::1', 1402580728, 0),
(23, 67, '::1', 1402580743, 1),
(24, 67, '::1', 1402580783, 0),
(25, 67, '::1', 1402580790, 0),
(26, 67, '::1', 1402580798, 0),
(27, 69, '::1', 1402581440, 1),
(28, 69, '::1', 1402581516, 0),
(29, 0, '::1', 1402596272, 0),
(30, 61, '::1', 1402596283, 1),
(31, 61, '::1', 1402597023, 1),
(32, 61, '::1', 1402643088, 0),
(33, 61, '::1', 1402643098, 0),
(34, 61, '::1', 1402643108, 1),
(35, 61, '::1', 1402643207, 1),
(36, 67, '::1', 1402646691, 0),
(37, 68, '::1', 1402646720, 1),
(38, 68, '::1', 1402647930, 1),
(39, 0, '::1', 1402648177, 0),
(40, 0, '::1', 1402648590, 0),
(41, 70, '::1', 1402648615, 1),
(42, 0, '::1', 1402908608, 0),
(43, 0, '::1', 1402908624, 0),
(44, 0, '::1', 1402908637, 0),
(45, 71, '::1', 1402910364, 1),
(46, 71, '::1', 1402915199, 1),
(47, 0, '::1', 1403282398, 0),
(48, 72, '::1', 1403282427, 1),
(49, 0, '109.156.207', 1403339368, 0),
(50, 73, '109.156.207', 1403339390, 1),
(51, 74, '109.156.207', 1403339416, 1),
(52, 75, '86.131.76.1', 1403899511, 1),
(53, 75, '86.131.76.1', 1403899543, 1),
(54, 76, '86.170.48.1', 1404123834, 1),
(55, 76, '86.170.48.1', 1404123869, 1),
(56, 77, '86.170.48.1', 1404127654, 1),
(57, 78, '86.170.48.1', 1404236416, 1),
(58, 74, '86.170.48.1', 1404306332, 1),
(59, 71, '37.203.134.', 1404637534, 0),
(60, 71, '37.203.134.', 1404637542, 1),
(61, 71, '37.203.134.', 1404670150, 1),
(62, 79, '37.203.134.', 1404672744, 1),
(63, 80, '37.203.134.', 1404754652, 1),
(64, 80, '37.203.134.', 1404755180, 1),
(65, 81, '37.203.134.', 1404755261, 1),
(66, 82, '37.203.134.', 1404755463, 1),
(67, 83, '37.203.134.', 1404756566, 1),
(68, 84, '80.229.168.', 1404759005, 1),
(69, 85, '161.73.185.', 1404759510, 1),
(70, 84, '80.229.168.', 1404761117, 1),
(71, 86, '80.229.168.', 1404761372, 1),
(72, 87, '161.73.185.', 1404761374, 1),
(73, 0, '80.229.168.', 1404761498, 0),
(74, 87, '80.229.168.', 1404761512, 1),
(75, 87, '80.229.168.', 1405027173, 1),
(76, 71, '37.203.134.', 1405581643, 1),
(77, 88, '37.203.134.', 1405582239, 1),
(78, 89, '80.87.24.94', 1405600998, 1),
(79, 90, '80.87.24.94', 1405601720, 1),
(80, 91, '80.87.24.94', 1405602101, 1),
(81, 92, '80.87.24.94', 1405602249, 1),
(82, 93, '80.87.24.94', 1405602328, 1),
(83, 77, '80.87.24.94', 1405610926, 0),
(84, 71, '80.87.24.94', 1405610931, 0),
(85, 90, '80.87.24.94', 1405610940, 1),
(86, 89, '80.87.24.94', 1405673149, 1),
(87, 71, '37.203.134.', 1405701092, 1),
(88, 71, '37.203.134.', 1405753980, 1),
(89, 71, '80.87.24.94', 1406190600, 1),
(90, 71, '80.87.24.94', 1406210923, 1),
(91, 71, '80.87.24.94', 1406276062, 1),
(92, 71, '217.158.151', 1406659971, 1),
(93, 71, '170.251.38.', 1406793776, 0),
(94, 71, '170.251.38.', 1406793819, 0),
(95, 71, '170.251.38.', 1406793836, 1),
(96, 71, '82.68.253.1', 1407947968, 0),
(97, 71, '82.68.253.1', 1407947982, 1),
(98, 87, '80.229.168.', 1408382066, 0),
(99, 87, '80.229.168.', 1408382081, 0),
(100, 94, '80.229.168.', 1408382140, 1),
(101, 87, '86.176.152.', 1408382200, 0),
(102, 87, '86.176.152.', 1408382212, 0),
(103, 87, '80.229.168.', 1408382224, 0),
(104, 87, '80.229.168.', 1408382431, 0),
(105, 0, '86.176.152.', 1408382455, 0),
(106, 85, '86.176.152.', 1408382474, 1),
(107, 85, '86.176.152.', 1408382517, 1),
(108, 85, '80.229.168.', 1408382538, 0),
(109, 85, '80.229.168.', 1408382559, 1),
(110, 0, '86.176.152.', 1408382628, 0),
(111, 95, '82.68.253.1', 1409077637, 1),
(112, 71, '82.68.253.1', 1409077735, 0),
(113, 71, '82.68.253.1', 1409077749, 1),
(114, 96, '170.251.38.', 1409137106, 1),
(115, 97, '170.251.38.', 1409137171, 1),
(116, 98, '170.251.38.', 1409137212, 1),
(117, 99, '170.251.38.', 1409137470, 1),
(118, 71, '170.251.38.', 1409144057, 1),
(119, 71, '37.203.134.', 1409344549, 1),
(120, 71, '37.203.134.', 1409344830, 0),
(121, 71, '37.203.134.', 1409344837, 1),
(122, 71, '37.203.134.', 1409524338, 1),
(123, 100, '37.203.134.', 1410119468, 1),
(124, 101, '127.0.0.1', 1421769595, 1),
(125, 102, '127.0.0.1', 1421770264, 1),
(126, 103, '127.0.0.1', 1421771456, 1),
(127, 104, '127.0.0.1', 1421771861, 1),
(128, 103, '127.0.0.1', 1421772250, 1),
(129, 105, '127.0.0.1', 1421772551, 1),
(130, 71, '127.0.0.1', 1421840590, 1),
(131, 71, '127.0.0.1', 1421840656, 1),
(132, 105, '127.0.0.1', 1421846657, 1),
(133, 72, '127.0.0.1', 1421847390, 1),
(134, 105, '127.0.0.1', 1421855938, 1),
(135, 72, '127.0.0.1', 1421863105, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

CREATE TABLE IF NOT EXISTS `user_skills` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `user_id` int(5) NOT NULL,
  `skill_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=139 ;

--
-- Dumping data for table `user_skills`
--

INSERT INTO `user_skills` (`id`, `user_id`, `skill_id`) VALUES
(9, 88, 5),
(8, 88, 3),
(10, 90, 0),
(11, 90, 0),
(12, 90, 2),
(21, 89, 10),
(20, 89, 9),
(19, 89, 2),
(124, 100, 17),
(136, 71, 5),
(135, 71, 4),
(134, 71, 3),
(133, 71, 1),
(132, 71, 2),
(131, 71, 12),
(130, 71, 11),
(125, 100, 18),
(126, 100, 3),
(127, 100, 19),
(128, 100, 20),
(129, 100, 21),
(137, 71, 22),
(138, 71, 23);

-- --------------------------------------------------------

--
-- Table structure for table `user_socialmedia`
--

CREATE TABLE IF NOT EXISTS `user_socialmedia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `service` varchar(30) NOT NULL,
  `url` varchar(249) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `user_socialmedia`
--

INSERT INTO `user_socialmedia` (`id`, `user_id`, `service`, `url`) VALUES
(10, 61, 'googleplus', 'https://plus.google.com/115877233289242058609/posts'),
(11, 61, 'dribble', 'https://dribbble.com/AndrewArnott'),
(12, 61, 'tumblr', 'http://andrew.tumblr.com/'),
(13, 71, 'facebook', 'http://fb.com/liss.sykes'),
(14, 71, 'linkedin', 'http://uk.linkedin.com/in/aliciasykes'),
(15, 71, 'googleplus', 'https://plus.google.com/+AliciaSykes'),
(16, 71, 'github', 'https://github.com/Lissy93'),
(17, 71, 'blogger', 'http://lissy93.blogspot.com'),
(18, 71, 'twitter', 'https://twitter.com/Lissy_Sykes'),
(20, 74, 'facebook', 'http://fb.com/liss.sykes'),
(21, 74, 'linkedin', 'http://uk.linkedin.com/in/aliciasykes'),
(22, 74, 'blogger', 'http://lissy93.blogspot.com'),
(23, 74, 'github', 'https://github.com/Lissy93'),
(24, 88, 'twitter', 'http://twitter.co.uk/idontexist'),
(25, 100, 'googleplus', 'https://plus.google.com/105951645642167113129'),
(26, 100, 'vimeo', 'http://vimeo.com/corneliusaesop'),
(27, 100, 'youtube', 'http://www.youtube.com/user/CorneliusAesop');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
