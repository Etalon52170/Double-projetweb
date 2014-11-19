<?php
 /*
  * Permet d'établir la connection
  * avec la base de données
  * BOULANGER Vincent & DAUSSY Alexandre
  */
include_once 'param_co.php';
class base{


	private static $connection;

	/* Permet d'obtenir une connection à la base 
	 * (Les paramètres de connections sont stockés dans un fichier) 
	 * Il faut créer une connection PDO distante
	 */
	public static function getConnection(){
		if (isset(self::$connection)) {
			return self::$connection;
		}else{
			self::$connection = self::connect();
			return self::$connection;
		}		
	}


	public static function connect(){
		global $host, $user, $pass, $base;
		try{
			$dns = "mysql:host=$host;dbname=$base";
			$connection = new PDO($dns, $user, $pass,	
					array(PDO::ERRMODE_EXCEPTION=>true, 
					PDO::ATTR_PERSISTENT=>true));
			$connection->exec("SET CHARACTER SET utf8");
			return($connection);
		}catch (Exception $e){
			die('Erreur : ' . $e->getMessage());
		}
		
	}

}

?>