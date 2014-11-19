<?php
class Vue {

    protected $tab;
    protected $tabSelecteur = array(
        'acceuil' => 'acceuilpage'
    );

    public function __construct() {
        
    }

    public function __set($attr_name, $attr_val) {

        if (property_exists(__CLASS__, $attr_name)) {
            $this->$attr_name = $attr_val;
        } else {
            $emess = __CLASS__ . ": unknown member $attr_name (setAttr)";
            echo ($emess);
        }
    }

    public function __get($attr_name) {
        if (property_exists(__CLASS__, $attr_name)) {
            return $this->$attr_name;
        }
        $emess = __CLASS__ . ": unknown member $attr_name (getAttr)";
        throw new Exception($emess, 45);
    }

    private function acceuilpage() {
        $res = '
        <header>
            <div id=\'inscription\'>
                <a href = \'blog.php?a=inscription\'>Inscription</a>
            </div>
            <form id=\'login\' action=\'auth.php\' method=\'post\'>
                <div classboutton\'>
                    <button type=\'submit\'>Connexion</button>
                </div>
                <div id= identification >
                    <input type=\'text\' name=\'nom\' placeholder= \'Nom\' required/>
		<input type=\'password\' name=\'pwd\' placeholder= \'Mot de passe\' required/>
                ';
        return $res;
    }

    public function affichageGeneral($select) {
        $res = '<!DOCTYPE html>
<html lang="fr">

<head>
	<title>Affichage général</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="style.css" type="text/css" /> 
</head>  
  
<body>';

        if (array_key_exists($select, $this->tabSelecteur)) {

            $m = $this->tabSelecteur[$select];

            $res .= $this->$m();
        } else {
            $res .= $this->defaut();
        }

        $res = $res . '</body></html>';
        return $res;
    }

}

?>