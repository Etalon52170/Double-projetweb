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
        <script type="text/JavaScript" src="./JS/Js_Connexion.js"></script>
            <div class = \'right-co\'>
                <button class="btn btn-sm btn-default" type="button">Inscription</button>
            </div>
            <div class=\'right-co\'>
                <input class="form-control" id="log" type=\'text\' name=\'log\' placeholder= \'Nom\' required/>
                <input class="form-control" id="pwd" type=\'password\' name=\'pwd\' placeholder= \'Mot de passe\' required/>
                <button class="btn btn-sm btn-default" type="button" onClick="validerConnexion()">Connexion</button>
            </div>       
        </header>';
        return $res;
    }

    public function affichageGeneral($select) {
        $res = '<!DOCTYPE html>
<html lang="fr">

    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel=\'stylesheet\' href=\'prototype/CascadeStyleSheet.css\' media=\'all\' type=\'text/css\' />
         <!-- Bootstrap -->
         <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
    </head>
  
<body>';

        if (array_key_exists($select, $this->tabSelecteur)) {

            $m = $this->tabSelecteur[$select];

            $res .= $this->$m();
        } else {
            $res .= $this->defaut();
        }

        $res = $res . '<script src="./bootstrap/js/bootstrap.min.js"></script></body></html>';
        return $res;
    }

}

?>