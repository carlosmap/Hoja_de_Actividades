-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 18, 2023 at 07:27 PM
-- Server version: 8.0.29-0ubuntu0.20.04.3
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `HojaActividades`
--

-- --------------------------------------------------------

--
-- Table structure for table `Actividades`
--

CREATE TABLE `Actividades` (
  `id_usuario` int NOT NULL,
  `id_proyecto` int NOT NULL,
  `fecha_actividad` date NOT NULL,
  `descripcion_actividad` varchar(10000) NOT NULL,
  `cantidad_horas_actividad` int NOT NULL,
  `fecha_registro_actividad` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Actividades`
--

INSERT INTO `Actividades` (`id_usuario`, `id_proyecto`, `fecha_actividad`, `descripcion_actividad`, `cantidad_horas_actividad`, `fecha_registro_actividad`) VALUES
(1, 1, '2023-03-04', 'Actividades realizadas en el dia 4 en el proyecto Cerro Matozo', 6, '2023-03-06'),
(1, 1, '2023-03-09', 'Actividades realizadas en el día 4 en el proyecto Cerro Matozo', 3, '2023-03-06'),
(1, 1, '2023-03-28', 'Prueba de la actividades realizadas con los caracterez especiales ', 2, '2023-03-21'),
(1, 1, '2023-03-31', 'En este dìa se realiso en el proyecto Cerro Matozò la añadidura del revestimiento en la carretera del rebozaderó:!', 1, '2023-04-17'),
(1, 1, '2023-04-05', 'Visita tècnica al lote donde se desarrolla el proyecto', 2, '2023-04-18'),
(1, 2, '2023-03-08', 'Desarrollo de los planos estructurales del proyecto PHCañafisto', 5, '2023-03-06'),
(1, 2, '2023-03-11', 'Reuniòn con la interventoria del proyecto PHCañafisto', 2, '2023-03-10'),
(1, 2, '2023-03-15', 'Desarrollo de los planos del rebosadero', 2, '2023-04-14'),
(1, 2, '2023-04-20', 'Redaccion de la documentaciòn incial de proyecto', 1, '2023-04-17'),
(1, 3, '2023-03-01', 'Realización de las perforaciones para los pilotes del proyecto', 3, '2023-03-10'),
(1, 3, '2023-03-04', 'Reuniòn de seguimiento con los grupos de trabajo', 1, '2023-03-10'),
(1, 3, '2023-03-11', 'Diseño de los planos estructurales de los inter conectores vialess', 3, '2023-03-06'),
(1, 3, '2023-03-25', 'Actividadès en el proyecto Guaicaramo', 1, '2023-04-14'),
(1, 3, '2023-03-31', 'Socialización del proyecto con las comunidades afectadas ', 2, '2023-03-10');

-- --------------------------------------------------------

--
-- Table structure for table `Proyectos`
--

CREATE TABLE `Proyectos` (
  `id_proyecto` int NOT NULL,
  `nombre_proyecto` varchar(100) NOT NULL,
  `estado_proyecto` tinyint(1) NOT NULL,
  `fecha_inicio_proyecto` date NOT NULL,
  `fecha_finalizacion_proyecto` date NOT NULL,
  `fecha_registro_proyecto` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Proyectos`
--

INSERT INTO `Proyectos` (`id_proyecto`, `nombre_proyecto`, `estado_proyecto`, `fecha_inicio_proyecto`, `fecha_finalizacion_proyecto`, `fecha_registro_proyecto`) VALUES
(1, 'Cerro Matozò', 1, '2022-12-04', '2024-06-19', '2023-03-05'),
(2, 'PHCañafisto', 1, '2022-02-06', '2023-07-19', '2023-03-01'),
(3, 'Guaicaramo', 1, '2023-02-05', '2023-06-09', '2023-03-01');

-- --------------------------------------------------------

--
-- Table structure for table `Tipos_documento`
--

CREATE TABLE `Tipos_documento` (
  `id_tipo_documento` int NOT NULL,
  `tipo_documento` varchar(50) NOT NULL,
  `estado_tipo_documento` tinyint(1) NOT NULL,
  `fecha_registro_tipo_documento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Tipos_documento`
--

INSERT INTO `Tipos_documento` (`id_tipo_documento`, `tipo_documento`, `estado_tipo_documento`, `fecha_registro_tipo_documento`) VALUES
(1, 'Cedula', 1, '2023-03-05');

-- --------------------------------------------------------

--
-- Table structure for table `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id_usuario` int NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `apellido_usuario` varchar(50) NOT NULL,
  `documento_usuario` int NOT NULL,
  `id_tipo_documento` int NOT NULL,
  `estado_usuario` tinyint(1) NOT NULL,
  `login_usuario` varchar(20) NOT NULL,
  `fecha_registro_usuario` date NOT NULL,
  `id_usuario_crea` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Usuarios`
--

INSERT INTO `Usuarios` (`id_usuario`, `nombre_usuario`, `apellido_usuario`, `documento_usuario`, `id_tipo_documento`, `estado_usuario`, `login_usuario`, `fecha_registro_usuario`, `id_usuario_crea`) VALUES
(1, 'Juan Camilo', 'Andrade Quijano', 1025478954, 1, 1, 'juanAndrade', '2023-03-05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Usuarios_password`
--

CREATE TABLE `Usuarios_password` (
  `id_usuario_password` int NOT NULL,
  `id_usuario` int NOT NULL,
  `password_usuario` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `estado_usuario_password` tinyint(1) NOT NULL,
  `fecha_registro_usuario_password` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Usuarios_password`
--

INSERT INTO `Usuarios_password` (`id_usuario_password`, `id_usuario`, `password_usuario`, `estado_usuario_password`, `fecha_registro_usuario_password`) VALUES
(1, 1, '$2y$10$KBOXWMTbITAWiC7fu/HJ7u/FFSBO2ls5PjiWH.FNHcG53BwlolekS', 1, '2023-03-05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Actividades`
--
ALTER TABLE `Actividades`
  ADD PRIMARY KEY (`id_usuario`,`id_proyecto`,`fecha_actividad`),
  ADD KEY `id_proyecto` (`id_proyecto`);

--
-- Indexes for table `Proyectos`
--
ALTER TABLE `Proyectos`
  ADD PRIMARY KEY (`id_proyecto`);

--
-- Indexes for table `Tipos_documento`
--
ALTER TABLE `Tipos_documento`
  ADD PRIMARY KEY (`id_tipo_documento`);

--
-- Indexes for table `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_tipo_documento` (`id_tipo_documento`);

--
-- Indexes for table `Usuarios_password`
--
ALTER TABLE `Usuarios_password`
  ADD PRIMARY KEY (`id_usuario_password`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Proyectos`
--
ALTER TABLE `Proyectos`
  MODIFY `id_proyecto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Tipos_documento`
--
ALTER TABLE `Tipos_documento`
  MODIFY `id_tipo_documento` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Usuarios_password`
--
ALTER TABLE `Usuarios_password`
  MODIFY `id_usuario_password` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Actividades`
--
ALTER TABLE `Actividades`
  ADD CONSTRAINT `Actividades_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `Usuarios` (`id_usuario`),
  ADD CONSTRAINT `Actividades_ibfk_2` FOREIGN KEY (`id_proyecto`) REFERENCES `Proyectos` (`id_proyecto`);

--
-- Constraints for table `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD CONSTRAINT `Usuarios_ibfk_1` FOREIGN KEY (`id_tipo_documento`) REFERENCES `Tipos_documento` (`id_tipo_documento`);

--
-- Constraints for table `Usuarios_password`
--
ALTER TABLE `Usuarios_password`
  ADD CONSTRAINT `Usuarios_password_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `Usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
