-- Il faut créer la base de données paypalap_db avant d'executer ce script

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userID` varchar(255) NOT NULL,
  `appID` varchar(255) NOT NULL,  
  `apiUser` varchar(255) NOT NULL,
  `apiPass` varchar(255) NOT NULL,  
  `apiSig` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
