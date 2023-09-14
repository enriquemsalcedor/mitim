DROP TABLE IF EXISTS `niveles`;

CREATE TABLE `niveles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `fechaCre` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `niveles` */

insert  into `niveles`(`id`,`nombre`,`fechaCre`) values (1,'Administrador','2017-03-28'),(2,'Gerentes','2017-03-28'),(3,'Administración','2017-04-19'),(4,'Compras','2017-04-19'),(5,'Supervisores','2017-04-19'),(6,'Almacen','2017-04-19'),(7,'Centro de costos','2017-04-20'),(8,'Coordinadora','2017-05-17');
