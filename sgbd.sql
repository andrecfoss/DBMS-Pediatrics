-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 13-Nov-2023 às 14:47
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sgbd`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `child`
--

CREATE TABLE `child` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `birth_date` date NOT NULL,
  `tutor_name` varchar(128) NOT NULL,
  `tutor_phone` varchar(32) NOT NULL,
  `tutor_email` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `child`
--

INSERT INTO `child` (`id`, `name`, `birth_date`, `tutor_name`, `tutor_phone`, `tutor_email`) VALUES
(23, 'Paulo', '2020-12-09', 'alfredo', '933445602', 'apapa@gmail.com'),
(24, 'diogochan', '2020-12-14', 'tomas', '944336584', 'emailhe@gmail.com'),
(25, 'tomas', '2020-12-09', 'diogo', '944556432', 'diogo@gmail.com'),
(26, 'caires', '2020-12-18', 'palmeira', '944883215', 'palmeira@gmail.com'),
(27, 'clara', '2020-12-17', 'carlos', '955449923', 'clara@gmail.com'),
(28, 'bia', '2020-12-15', 'marina', '933994321', 'marina@gmail.com'),
(29, 'tiago', '2020-11-09', 'ricardo', '833227164', 'ricardo@gmail.com'),
(30, 'Paulo', '2020-11-05', 'sdadad', '933442395', 'ddfg@gmail.com'),
(31, 'Nuno Rodrigues', '2000-06-12', 'Rodrigues', '123456789', ''),
(32, 'Luis', '2000-01-01', 'Sousa', '123456789', ''),
(33, 'Filipe', '2000-01-01', 'Namora', '291654321', NULL),
(34, 'Filipe Marco', '1234-12-12', 'Marco', '291333333', ''),
(35, 'Carlos', '2008-06-11', 'Marques', '987654321', 'daveiro@gmail.com'),
(36, 'Rodrigo', '2000-12-12', 'Cardoso', '987654321', ''),
(37, 'Rodrigo', '2000-12-12', 'Nascimento', '123456789', ''),
(38, 'Luís', '2003-05-12', 'Brito', '987654321', NULL),
(39, 'Genesis', '2000-07-07', 'Silva', '987654321', ''),
(40, 'Diogo', '2020-12-12', 'Sousa', '987654321', 'daveiro@gmail.com'),
(41, 'Juan', '2021-01-05', 'kira', '291654321', 'daveiro@gmail.cop'),
(42, 'Filipa', '2000-12-12', 'Sousa', '987654321', ''),
(43, 'jenifer', '1989-09-09', 'Consta', '987654321', ''),
(44, 'Rodrigo', '2000-12-12', 'Abreu', '987654321', ''),
(45, 'Luis Aguiar', '2010-10-10', 'Jota Aguiar', '987654321', 'laguiar@gmail.com'),
(46, 'Joao David', '2010-10-10', 'Paulo David', '987654321', 'daveiro@gmail.com'),
(47, 'luana mend', '2020-12-12', 'mend gomes', '987654321', 'lmed@org.pt'),
(48, 'Francisco Silva', '1932-12-12', 'Paulo Silva', '987654321', 'silva@lo.lo'),
(49, 'FIlipe Marques', '2020-12-12', 'Marques Sousa', '987654321', NULL),
(50, 'João Martins', '1999-01-14', ' MM Martins', '987654321', ''),
(51, 'João Silva', '2010-10-10', 'Silva Gomes', '987654321', ''),
(52, 'Luis Olim', '2020-12-12', 'Olim Kopa', '987654321', ''),
(53, 'Chico Mendes', '2020-12-12', 'mendes chi', '987654321', 'sdfdsf@dsf.d'),
(54, 'bruno cafofo', '1998-12-05', 'Jorge Cafofo', '987654321', ''),
(55, 'Ricardo Corte', '2020-10-10', 'Corte João', '987654321', ''),
(56, 'Joao', '2003-05-12', 'Pestana', '987654321', 'pestana@hoje.pt'),
(57, 'luis jorge', '1979-08-12', 'paulo jorge', '962906146', 'daveiro@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `item`
--

CREATE TABLE `item` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `item_type_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `state` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `item`
--

INSERT INTO `item` (`id`, `name`, `item_type_id`, `state`) VALUES
(1, 'medidas', 1, 'active'),
(2, 'cabelo', 1, 'active'),
(3, 'autismo', 2, 'active'),
(4, 'sindrome de asperger', 2, 'active'),
(5, 'poliomelite', 2, 'inactive'),
(23, 'massagem', 3, 'active'),
(25, 'reiki', 3, 'active'),
(29, 'equilibrio', 3, 'active'),
(30, 'força', 4, 'active'),
(31, 'cócegas', 3, 'active'),
(32, 'maratona', 3, 'active'),
(33, 'equilibrio', 4, 'active'),
(34, 'Natação', 3, 'inactive'),
(35, 'caminhada', 3, 'active'),
(36, 'marcha', 3, 'active'),
(37, 'calma', 4, 'active'),
(38, 'tensão', 4, 'active'),
(39, 'bpm', 4, 'active'),
(40, 'sarampo', 2, 'active'),
(41, 'QI', 4, 'active'),
(42, 'acupunctura', 3, 'active'),
(43, 'fraqueza', 4, 'active'),
(44, 'voo', 3, 'active'),
(45, 'raiva', 4, 'active'),
(46, 'saltos', 3, 'active'),
(47, 'resistencia', 4, 'active'),
(48, 'durabilidade', 4, 'active');

-- --------------------------------------------------------

--
-- Estrutura da tabela `item_type`
--

CREATE TABLE `item_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `code` varchar(32) NOT NULL COMMENT 'manually fill with values: child_data, diagnosis, intervention, evaluation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `item_type`
--

INSERT INTO `item_type` (`id`, `name`, `code`) VALUES
(1, 'dado_de_crianca', 'child_data'),
(2, 'diagnostico', 'diagnosis'),
(3, 'intervencao', 'intervation'),
(4, 'avaliacao', 'evaluation'),
(5, 'reserva', 'reserve');

-- --------------------------------------------------------

--
-- Estrutura da tabela `subitem`
--

CREATE TABLE `subitem` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL DEFAULT '',
  `item_id` int(10) UNSIGNED NOT NULL,
  `value_type` enum('text','bool','int','double','enum') NOT NULL COMMENT 'text, int, double, boolean, enum',
  `form_field_name` varchar(64) NOT NULL DEFAULT '' COMMENT 'ascii string to be used as the name of the form field',
  `form_field_type` enum('text','textbox','radio','checkbox','selectbox') NOT NULL,
  `unit_type_id` int(10) UNSIGNED DEFAULT NULL,
  `form_field_order` int(10) UNSIGNED NOT NULL COMMENT 'order in which form fields will be shown',
  `mandatory` int(11) NOT NULL COMMENT '1 if subitem is mandatory for its parent, 0 if not',
  `state` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `subitem`
--

INSERT INTO `subitem` (`id`, `name`, `item_id`, `value_type`, `form_field_name`, `form_field_type`, `unit_type_id`, `form_field_order`, `mandatory`, `state`) VALUES
(1, 'altura', 1, 'int', 'med-_-altura', 'text', 2, 1, 1, 'active'),
(2, 'peso', 1, 'double', 'med-2-peso', 'text', 1, 2, 1, 'active'),
(3, 'cintura', 1, 'int', 'med-3-cintura', 'text', 2, 3, 0, 'active'),
(4, 'cor', 2, 'text', 'cab-4-cr', 'text', NULL, 1, 1, 'active'),
(5, 'tipo de fio', 2, 'enum', 'cab-5-tipo_de_fio', 'checkbox', NULL, 2, 1, 'active'),
(6, 'densidade', 2, 'int', 'cab-6-densidade', 'text', 3, 8, 1, 'active'),
(7, 'grau', 3, 'enum', 'aut-7-grau', 'radio', NULL, 1, 1, 'active'),
(8, 'estereotipia', 3, 'enum', 'aut-8-estereotipia', 'radio', NULL, 9, 0, 'active'),
(68, 'pontas', 2, 'enum', 'cab-68-pontas', 'selectbox', NULL, 5, 1, 'active'),
(69, 'grossura', 2, 'enum', 'cab-69-grossura', 'radio', 2, 10, 0, 'active'),
(70, 'acção', 4, 'text', 'sin-70-acção', 'textbox', NULL, 100, 0, 'active'),
(71, 'largura', 1, 'double', 'med-71-largura', 'text', 2, 65, 1, 'active'),
(72, 'grau', 4, 'enum', 'sin-4-grau', 'selectbox', NULL, 5, 1, 'active'),
(73, 'accao', 4, 'enum', 'sin-73-accao', 'selectbox', NULL, 7, 1, 'active'),
(74, 'duracao', 25, 'double', '-', 'text', 7, 45, 1, 'active'),
(75, 'comprimento', 2, 'double', 'cab-75-comprimento', 'text', 2, 5, 1, 'active'),
(76, 'tipo', 25, 'enum', 'rei-76-tipo', 'selectbox', NULL, 90, 1, 'active'),
(77, 'grau', 25, 'enum', 'rei-77-grau', 'radio', 7, 7, 1, 'active'),
(78, 'intensidade', 25, 'double', 'rei-78-intensidade', 'text', 7, 10, 1, 'active'),
(79, 'empatia', 3, 'enum', 'aut-79-empatia', 'radio', NULL, 43, 1, 'active'),
(80, 'estrelas', 25, 'int', 'rei-80-estrelas', 'text', 1, 10, 1, 'active'),
(81, 'grau', 4, 'enum', 'sin-81-grau', 'selectbox', NULL, 34, 1, 'active'),
(82, 'atencão', 3, 'enum', 'aut-82-atenco', 'selectbox', 7, 9, 1, 'active'),
(83, 'intensidade', 3, 'int', 'aut-83-intensidade', 'text', 11, 9, 1, 'active'),
(84, 'categoria', 4, 'enum', 'sin-84-categoria', 'radio', 8, 45, 0, 'active'),
(85, 'nivel', 25, 'int', 'rei-85-nivel', 'text', 1, 90, 1, 'active'),
(86, 'prana', 25, 'enum', 'rei-86-prana', 'radio', NULL, 43, 1, 'active'),
(87, 'grau', 37, 'text', 'cal-87-grau', 'text', 15, 90, 1, 'active'),
(88, 'ki', 25, 'enum', 'rei-88-ki', 'selectbox', NULL, 5, 1, 'active'),
(89, 'QI', 3, 'int', 'aut-89-QI', 'text', NULL, 6, 1, 'active'),
(90, 'nível', 37, 'int', 'cal-90-nível', 'text', 7, 90, 1, 'active'),
(91, 'altura', 44, 'int', 'voo-91-altura', 'text', 10, 34, 1, 'active'),
(92, 'nivel', 44, 'enum', 'voo-92-nivel', 'selectbox', NULL, 4, 1, 'active'),
(93, 'motor', 44, 'enum', 'voo-93-motor', 'selectbox', NULL, 90, 1, 'active'),
(94, 'Observações', 44, 'text', 'voo-94-Observaes', 'text', NULL, 54, 1, 'active'),
(95, 'tipo', 46, 'enum', 'sal-95-tipo', 'checkbox', NULL, 40, 1, 'active'),
(96, 'frequência', 46, 'enum', 'sal-96-frequncia', 'selectbox', 21, 7, 1, 'active'),
(97, 'amplitude', 46, 'double', 'sal-97-amplitude', 'text', 7, 90, 1, 'active');

-- --------------------------------------------------------

--
-- Estrutura da tabela `subitem_allowed_value`
--

CREATE TABLE `subitem_allowed_value` (
  `id` int(10) UNSIGNED NOT NULL,
  `subitem_id` int(10) UNSIGNED NOT NULL,
  `value` varchar(128) NOT NULL,
  `state` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `subitem_allowed_value`
--

INSERT INTO `subitem_allowed_value` (`id`, `subitem_id`, `value`, `state`) VALUES
(1, 5, 'liso', 'active'),
(2, 5, 'ondulado', 'active'),
(3, 5, 'encaracolado', 'active'),
(4, 7, 'ligeiro', 'active'),
(5, 7, 'moderado', 'active'),
(6, 7, 'grave', 'active'),
(7, 8, 'marcha', 'active'),
(8, 8, 'movimento do tronco', 'active'),
(9, 8, 'cruzamento das pernas', 'active'),
(10, 8, 'saltos', 'active'),
(11, 68, 'abertas', 'active'),
(12, 68, 'fechadas', 'active'),
(13, 69, 'fino', 'active'),
(14, 69, 'medio', 'active'),
(15, 5, 'frisado', 'active'),
(16, 8, 'pe coxinho', 'active'),
(17, 7, 'muito grave', 'active'),
(18, 69, 'super', 'active'),
(19, 68, 'secas', 'active'),
(20, 69, 'hiper', 'active'),
(21, 79, 'extremo', 'active'),
(22, 77, 'baixo', 'active'),
(23, 77, 'médio', 'active'),
(24, 68, 'quebradas', 'active'),
(25, 82, 'alta', 'active'),
(26, 82, 'baixa', 'active'),
(27, 77, 'alto', 'active'),
(28, 5, 'zigzag', 'active'),
(29, 7, 'extremo', 'active'),
(30, 79, 'média', 'active'),
(31, 73, 'pontapé', 'active'),
(32, 92, 'baixo', 'active'),
(33, 92, 'Médio', 'active'),
(34, 73, 'salto', 'active');

-- --------------------------------------------------------

--
-- Estrutura da tabela `subitem_unit_type`
--

CREATE TABLE `subitem_unit_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(45) NOT NULL COMMENT 'kg, cm, mmHg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `subitem_unit_type`
--

INSERT INTO `subitem_unit_type` (`id`, `name`) VALUES
(1, 'kg'),
(2, 'cm'),
(3, 'caracóis/cm'),
(7, 'm'),
(8, 'mm'),
(9, 'min'),
(10, 'km'),
(11, 'seg'),
(12, 'nm'),
(13, 'um'),
(14, 'hh'),
(15, 'jj'),
(16, 'km'),
(17, 'nm'),
(18, 'kl'),
(19, 'mm'),
(20, 'km'),
(21, 'ml');

-- --------------------------------------------------------

--
-- Estrutura da tabela `value`
--

CREATE TABLE `value` (
  `id` int(10) UNSIGNED NOT NULL,
  `child_id` int(10) UNSIGNED NOT NULL,
  `subitem_id` int(10) UNSIGNED NOT NULL,
  `value` varchar(8192) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `producer` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `value`
--

INSERT INTO `value` (`id`, `child_id`, `subitem_id`, `value`, `date`, `time`, `producer`) VALUES
(1, 33, 4, 'teste', '2021-01-15', '11:15:21', 'user'),
(2, 33, 5, 'ondulado', '2021-01-15', '11:15:21', 'user'),
(3, 33, 68, 'fechadas', '2021-01-15', '11:15:21', 'user'),
(4, 33, 69, 'medio', '2021-01-15', '11:15:21', 'user'),
(5, 33, 4, 'azul', '2021-01-15', '12:14:12', NULL),
(6, 33, 5, 'frisado', '2021-01-15', '12:14:12', NULL),
(7, 33, 68, 'fechadas', '2021-01-15', '12:14:12', NULL),
(8, 33, 69, 'fino', '2021-01-15', '12:14:12', NULL),
(9, 33, 4, 'louro', '2021-01-15', '02:41:12', 'user'),
(10, 33, 5, 'frisado', '2021-01-15', '02:41:12', 'user'),
(11, 33, 68, 'abertas', '2021-01-15', '02:41:12', 'user'),
(12, 33, 69, 'medio', '2021-01-15', '02:41:13', 'user'),
(13, 33, 4, 'azul', '2021-01-15', '15:25:09', 'user'),
(14, 33, 5, 'ondulado', '2021-01-15', '15:25:09', 'user'),
(15, 33, 68, 'fechadas', '2021-01-15', '15:25:09', 'user'),
(16, 33, 69, 'fino', '2021-01-15', '15:25:09', 'user'),
(17, 33, 2, '45', '2021-01-15', '15:25:48', 'user'),
(18, 33, 3, '45', '2021-01-15', '15:25:48', 'user'),
(19, 33, 71, '35', '2021-01-15', '15:25:48', 'user'),
(20, 34, 4, 'azul', '2021-01-15', '17:56:58', 'user'),
(21, 34, 5, 'frisado', '2021-01-15', '17:56:58', 'user'),
(22, 34, 5, 'encaracolado', '2021-01-15', '17:56:58', 'user'),
(23, 34, 5, 'ondulado', '2021-01-15', '17:56:58', 'user'),
(24, 34, 68, 'secas', '2021-01-18', '15:41:00', 'user'),
(25, 34, 69, 'medio', '2021-01-15', '17:56:58', 'user'),
(26, 34, 75, '45', '2021-01-15', '17:56:58', 'user'),
(27, 33, 1, '', '2021-01-15', '07:13:15', 'user'),
(28, 33, 2, '', '2021-01-15', '07:13:15', 'user'),
(29, 33, 3, '', '2021-01-15', '07:13:15', 'user'),
(30, 33, 71, '', '2021-01-15', '07:13:15', 'user'),
(31, 33, 4, 'azul', '2021-01-15', '07:49:02', NULL),
(32, 33, 5, 'liso', '2021-01-15', '07:49:02', NULL),
(33, 33, 5, 'ondulado', '2021-01-15', '07:49:02', NULL),
(34, 33, 68, 'fechadas', '2021-01-15', '07:49:02', NULL),
(35, 33, 75, '45', '2021-01-15', '07:49:02', NULL),
(36, 33, 69, 'super', '2021-01-15', '07:49:02', NULL),
(37, 33, 1, '23', '2021-01-15', '07:52:56', NULL),
(38, 33, 2, '12', '2021-01-15', '07:52:56', NULL),
(39, 33, 3, '90', '2021-01-15', '07:52:56', NULL),
(40, 33, 71, '76', '2021-01-15', '07:52:56', NULL),
(41, 33, 1, '43', '2021-01-18', '14:32:49', 'user'),
(42, 33, 2, '54', '2021-01-18', '14:32:49', 'user'),
(43, 33, 3, '76', '2021-01-18', '14:32:50', 'user'),
(44, 33, 71, '87', '2021-01-18', '14:32:50', 'user'),
(45, 33, 5, 'frisado', '2021-01-18', '02:59:23', 'user'),
(46, 33, 5, 'encaracolado', '2021-01-18', '02:59:23', 'user'),
(47, 33, 5, 'ondulado', '2021-01-18', '02:59:23', 'user'),
(48, 33, 4, 'azul', '2021-01-18', '02:59:23', 'user'),
(49, 33, 75, '43', '2021-01-18', '02:59:23', 'user'),
(50, 33, 68, 'fechadas', '2021-01-18', '02:59:23', 'user'),
(51, 33, 69, 'fino', '2021-01-18', '02:59:23', 'user'),
(52, 33, 7, 'moderado', '2021-01-18', '15:33:40', 'user'),
(53, 33, 8, 'pe cochinho', '2021-01-18', '15:33:40', 'user'),
(54, 33, 79, 'extremo', '2021-01-18', '15:33:40', 'user'),
(55, 34, 7, 'ligeiro', '2021-01-18', '15:34:03', 'user'),
(56, 34, 8, 'saltos', '2021-01-18', '15:34:03', 'user'),
(57, 34, 79, 'extremo', '2021-01-18', '15:34:03', 'user'),
(58, 33, 4, 'azul', '2021-01-18', '15:34:32', 'user'),
(59, 33, 5, 'ondulado', '2021-01-18', '15:34:32', 'user'),
(60, 33, 5, 'encaracolado', '2021-01-18', '15:34:32', 'user'),
(61, 33, 5, 'frisado', '2021-01-18', '15:34:32', 'user'),
(62, 33, 68, 'abertas', '2021-01-18', '15:34:32', 'user'),
(63, 33, 69, 'medio', '2021-01-18', '15:34:32', 'user'),
(64, 33, 75, '45', '2021-01-18', '15:34:32', 'user'),
(65, 33, 77, 'medio', '2021-01-18', '15:35:53', 'user'),
(66, 33, 78, '34', '2021-01-18', '15:35:53', 'user'),
(67, 33, 80, '44', '2021-01-18', '15:35:53', 'user'),
(68, 33, 4, 'azul', '2021-01-18', '18:52:12', 'user'),
(69, 33, 68, 'quebradas', '2021-01-18', '18:52:13', 'user'),
(70, 33, 69, 'medio', '2021-01-18', '18:52:13', 'user'),
(71, 33, 75, '45', '2021-01-18', '18:52:13', 'user'),
(72, 33, 4, 'louro', '2021-01-19', '15:24:14', 'user'),
(73, 33, 5, 'liso', '2021-01-19', '15:24:14', 'user'),
(74, 33, 5, 'ondulado', '2021-01-19', '15:24:14', 'user'),
(75, 33, 5, 'encaracolado', '2021-01-19', '15:24:14', 'user'),
(76, 33, 68, 'fechadas', '2021-01-19', '15:24:14', 'user'),
(77, 33, 75, '45', '2021-01-19', '15:24:14', 'user'),
(78, 33, 69, 'medio', '2021-01-19', '15:24:14', 'user'),
(79, 33, 4, 'azul', '2021-01-19', '19:09:48', 'user'),
(80, 33, 6, '34', '2021-01-19', '19:09:48', 'user'),
(81, 33, 75, '43', '2021-01-19', '19:09:48', 'user'),
(82, 33, 5, ',liso,ondulado,encaracolado', '2021-01-19', '19:09:48', 'user'),
(83, 33, 85, 'medio', '2021-01-19', '19:09:48', 'user'),
(84, 33, 90, 'medio', '2021-01-19', '19:09:48', 'user'),
(85, 33, 92, 'medio', '2021-01-19', '19:09:48', 'user'),
(86, 33, 68, 'secas', '2021-01-19', '19:09:48', 'user'),
(87, 33, 4, 'azul', '2021-01-22', '12:17:31', 'user'),
(88, 33, 5, 'zigzag', '2021-01-22', '12:17:31', 'user'),
(89, 33, 6, 'densidade', '2021-01-22', '12:17:31', 'user'),
(90, 33, 68, 'secas', '2021-01-22', '12:17:31', 'user'),
(91, 33, 69, 'hiper', '2021-01-22', '12:17:31', 'user'),
(92, 33, 75, '45', '2021-01-22', '12:17:31', 'user'),
(93, 33, 4, 'azul', '2021-01-22', '17:03:37', 'user'),
(94, 33, 5, 'liso', '2021-01-22', '17:03:37', 'user'),
(95, 33, 5, 'ondulado', '2021-01-22', '17:03:37', 'user'),
(96, 33, 5, 'encaracolado', '2021-01-22', '17:03:37', 'user'),
(97, 33, 68, 'secas', '2021-01-22', '17:03:37', 'user'),
(98, 33, 75, '45', '2021-01-22', '17:03:37', 'user'),
(99, 33, 6, '32', '2021-01-22', '17:03:37', 'user'),
(100, 33, 69, 'medio', '2021-01-22', '17:03:37', 'user');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `child`
--
ALTER TABLE `child`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_item_type1_idx` (`item_type_id`);

--
-- Índices para tabela `item_type`
--
ALTER TABLE `item_type`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `subitem`
--
ALTER TABLE `subitem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subitem_unit_type_idx` (`unit_type_id`),
  ADD KEY `fk_subitem_item1_idx` (`item_id`);

--
-- Índices para tabela `subitem_allowed_value`
--
ALTER TABLE `subitem_allowed_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subitem_allowed_value_subitem1_idx` (`subitem_id`);

--
-- Índices para tabela `subitem_unit_type`
--
ALTER TABLE `subitem_unit_type`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `value`
--
ALTER TABLE `value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_value_child1_idx` (`child_id`),
  ADD KEY `fk_value_subitem1_idx` (`subitem_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `child`
--
ALTER TABLE `child`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de tabela `item`
--
ALTER TABLE `item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de tabela `item_type`
--
ALTER TABLE `item_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `subitem`
--
ALTER TABLE `subitem`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de tabela `subitem_allowed_value`
--
ALTER TABLE `subitem_allowed_value`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `subitem_unit_type`
--
ALTER TABLE `subitem_unit_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `value`
--
ALTER TABLE `value`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_item_item_type1` FOREIGN KEY (`item_type_id`) REFERENCES `item_type` (`id`);

--
-- Limitadores para a tabela `subitem`
--
ALTER TABLE `subitem`
  ADD CONSTRAINT `fk_subitem_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `fk_subitem_unit_type` FOREIGN KEY (`unit_type_id`) REFERENCES `subitem_unit_type` (`id`);

--
-- Limitadores para a tabela `subitem_allowed_value`
--
ALTER TABLE `subitem_allowed_value`
  ADD CONSTRAINT `fk_subitem_allowed_value_subitem1` FOREIGN KEY (`subitem_id`) REFERENCES `subitem` (`id`);

--
-- Limitadores para a tabela `value`
--
ALTER TABLE `value`
  ADD CONSTRAINT `fk_value_child1` FOREIGN KEY (`child_id`) REFERENCES `child` (`id`),
  ADD CONSTRAINT `fk_value_subitem1` FOREIGN KEY (`subitem_id`) REFERENCES `subitem` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
