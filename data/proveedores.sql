DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ruc` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(200) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `correo` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `contacto` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `sitioweb` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `proveedores` */

insert  into `proveedores`(`id`,`nombre`,`descripcion`,`ruc`,`direccion`,`telefono`,`correo`,`contacto`,`sitioweb`,`estado`) values (0,'','-','','-','-','','-','',''),(1,'Adipan','Adipan','J-123456789-7',' Av.',' 04245803326','lismary.18@gmail.com',' Daniel','adipan.com','Activo'),(2,'Amazon','Amazon','J-2589634-8','EEUU','158963254',' ','','www.amazon.com','Activo'),(3,'Bussines Suplieer','Bussines Suplieer','',' ',' ',' ',' ','','Activo'),(4,'Cochez y Cía','Cochez y Cía','',' ',' ',' ',' ','',''),(5,'Coresa','Coresa','',' ',' ',' ',' ','',''),(6,'Empresas Melo (020)','Empresas Melo (020)','',' ',' ',' ',' ','',''),(7,'Ferreteria Victoria','Ferreteria Victoria','',' ',' ',' ',' ','',''),(8,'Fuel Service Panamá','Fuel Service Panamá','',' ',' ',' ',' ','',''),(9,'IMPLOSA','IMPLOSA','',' ',' ',' ',' ','',''),(10,'Matco Internacional','Matco Internacional','',' ',' ',' ',' ','',''),(11,'Maxindustrias','Maxindustrias','',' ',' ',' ',' ','',''),(12,'Moderna Comercial','Moderna Comercial','',' ',' ',' ',' ','',''),(13,'Rocayol Safety','Rocayol Safety','',' ',' ',' ',' ','',''),(14,'Total Clean','Total Clean','',' ',' ',' ',' ','',''),(15,'prueba','','147852369','lara','0424','lis@gmail.com','0414','lis','Activo');

