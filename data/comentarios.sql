DROP TABLE IF EXISTS `comentarios`;

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_orden` int(11) DEFAULT NULL,
  `id_incidente` int(11) DEFAULT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `comentario` text COLLATE utf8_spanish2_ci,
  PRIMARY KEY (`id`),
  KEY `FK_comentarios_ordenes` (`id_orden`),
  KEY `FK_comentarios_incidentes` (`id_incidente`),
  CONSTRAINT `FK_comentarios_incidentes` FOREIGN KEY (`id_incidente`) REFERENCES `incidentes` (`numero`),
  CONSTRAINT `FK_comentarios_ordenes` FOREIGN KEY (`id_orden`) REFERENCES `ordenes` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;