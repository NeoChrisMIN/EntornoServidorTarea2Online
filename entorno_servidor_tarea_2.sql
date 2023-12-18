-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-12-2023 a las 17:41:12
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `entorno_servidor_tarea_2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `IDCAT` int(5) NOT NULL,
  `NOMBRECAT` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`IDCAT`, `NOMBRECAT`) VALUES
(1, 'Categoría 1'),
(2, 'Categoría 2'),
(3, 'Categoría 3'),
(4, 'Categoría 4'),
(7, 'ficción'),
(9, 'acción'),
(11, 'carreras'),
(12, '5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `IDENT` int(5) NOT NULL,
  `IDUSUARIO` int(5) NOT NULL,
  `IDCATEGORIA` int(5) NOT NULL,
  `TITULO` varchar(40) NOT NULL,
  `IMAGEN` varchar(40) NOT NULL,
  `DESCRIPCION` text NOT NULL,
  `FECHA` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`IDENT`, `IDUSUARIO`, `IDCATEGORIA`, `TITULO`, `IMAGEN`, `DESCRIPCION`, `FECHA`) VALUES
(1, 1, 1, 'Titulo', 'foto1.jpg', 'Esta es la descripcion', '2023-01-17 10:47:08'),
(13, 2, 1, 'TÃ­tulo Entrada 3', '1676983922-foto3.jpg', '<h1><strong>Hola :)</strong></h1>\r\n\r\n<ul>\r\n	<li><strong>Esto es una pba</strong></li>\r\n	<li><strong>Esto es una pba</strong></li>\r\n</ul>\r\n', '2023-02-21 11:52:02'),
(14, 2, 2, 'Titulo Entrada 5', '', 'Otra Pba ;) o quizás dos :3\r\n', '2023-12-05 23:26:00'),
(15, 1, 1, 'TÃ­tulo Entrada 4', '1676984400-foto5.jpg', '<h1><strong>Soy admin ;)</strong></h1>\r\n\r\n<ul>\r\n	<li>S&iacute; se&ntilde;or</li>\r\n	<li>No s&eacute;</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n', '2023-12-14 09:46:40'),
(16, 2, 3, 'Las Monas chinas', 'Cute-Anime-PNG-Image.png', 'un ejercito de monas chinas amenaza la vida de los hombres', '2023-12-15 23:33:36'),
(17, 8, 4, 'Vientos de ultramar', 'descarga.png', 'historia de amor en un barco que se pierde en el mar', '2023-12-15 23:34:37'),
(18, 2, 4, 'Siempre adelante', 'Marshall_Lee_Croma.png', 'Historia de superación', '2023-12-15 23:38:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IDUSER` int(5) NOT NULL,
  `NICK` varchar(40) NOT NULL,
  `NOMBRE` varchar(40) NOT NULL,
  `APELLIDOS` varchar(40) NOT NULL,
  `EMAIL` varchar(40) NOT NULL,
  `CONTRASENIA` varchar(40) NOT NULL,
  `AVATAR` varchar(50) NOT NULL,
  `ROL` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IDUSER`, `NICK`, `NOMBRE`, `APELLIDOS`, `EMAIL`, `CONTRASENIA`, `AVATAR`, `ROL`) VALUES
(1, 'malodo', 'maria', 'Lopez Dominguez', 'maria@gmail.com', '123', 'avatar1.png', 'admin'),
(2, 'ninja', 'antonio', 'gonzalez', 'antonio@gmail.com', '12345', 'avatar2.png', 'user'),
(3, 'Espay', 'Christian', 'Martín Infantes', '6bchristianmi@gmail.com', '123', 'avatar_3_Cute-Anime-PNG-Image.png', 'admin'),
(7, 'qwe', 'qio', 'yu ta', 'qwe@gmail.com', '123', 'avatar_7_Marshall_Lee_Croma.png', 'user'),
(8, 'yuki', 'yukine', 'machiro kiriya', 'yuki@gmail.com', '123', 'Cute-Anime-PNG-Image.png', 'admin'),
(9, 'vi', 'violet', 'evergarden', 'violet@gmail.com', '123', 'avatar_9_descarga.png', 'user'),
(10, 'ryu', 'ryu_ken', 'riso machego', 'ryu@gmail.com', '123', 'Cute-Anime-PNG-Image.png', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`IDCAT`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`IDENT`),
  ADD KEY `IDUSUARIO` (`IDUSUARIO`),
  ADD KEY `IDCATEGORIA` (`IDCATEGORIA`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IDUSER`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `IDCAT` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `IDENT` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IDUSER` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `ENTRADAS_IBFK_1` FOREIGN KEY (`IDUSUARIO`) REFERENCES `usuarios` (`IDUSER`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ENTRADAS_IBFK_2` FOREIGN KEY (`IDCATEGORIA`) REFERENCES `categoria` (`IDCAT`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
