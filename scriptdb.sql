CREATE TABLE IF NOT EXISTS `mensajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenido` TEXT COLLATE utf8_spanish_ci NOT NULL,
  `fechaHora` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `idRemitente` int(11) NOT NULL,
  `idDestinatario` int (11) NOT NULL,
  `estado` ENUM ('enviado', 'recibido','no leido','leido') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'enviado',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;