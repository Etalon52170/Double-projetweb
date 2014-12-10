CREATE TABLE cards (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    symbol0 INT,  -- Numéros des 8 symboles apparraissant sur la carte
    symbol1 INT,
    symbol2 INT,
    symbol3 INT,
    symbol4 INT,
    symbol5 INT,
    symbol6 INT,
    symbol7 INT
);



CREATE TABLE games (		-- Parties en cours ou en attente 
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nbPlayers INT NOT NULL,     -- Nombre de joueurs devant participer (4 par défaut)
    indexx INT         		-- Numéro d'ordre de la carte en haut de la pioche
);

CREATE TABLE stacks (		-- Pioche des cartes des parties
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,     	-- Identifiant de partie 
    card_id INT NOT NULL,	-- Identifiant de carte
    numOrder INT NOT NULL       -- Numéro d'ordre de cette carte dans cette partie
);

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_user` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nb_victoire` int(5) NOT NULL,
  `nb_partie` int(5) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `nbCards` INT,      	 	-- Nombre de cartes récupérées en jouant
  `indexx` INT,                     -- Carte courant du stack
  `game_id` INT(10) UNSIGNED NULL,                -- Partie en cours
  PRIMARY KEY (`id_user`),
  FOREIGN KEY (game_id) REFERENCES games(id)
);
