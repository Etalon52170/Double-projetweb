<?php

class Vue {

    protected $tab;
    protected $tabSelecteur = array(
        'acceuil' => 'acceuilpage',
        'jeux' => 'pagePrincipale',
        'inscri' => 'inscription'
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

    private function inscription(){
        $res ='
        <header>
            <script type="text/JavaScript" src="./JS/Js_Connexion.js"></script>
            <table class ="right" >
                <tr>
                    <td class="espaceCellule"><input class="form-control" id="login" type=\'text\' name=\'log\' placeholder= \'Nom\' required/></td>
                    <td class ="espaceCellule"><input class="form-control" id="password" type=\'password\' name=\'pwd\' placeholder= \'Mot de passe\' required/></td>
                    <td><button class="btn btn-sm btn-success" type="button" onClick="validerConnexion()">Connexion</button></td>
                </tr> 
                <tr>
                    <div class="invisibleError btn-sm btn-default" id="mdpIncorrect">
                        Pseudo ou Mot de passe incorrect !!
                    </div>
                </tr>
            </table>   
        </header>            
        <div class="container">
            <form class="form-horizontal" id="registration" method=\'post\' action=\'register.php\'>
                    <fieldset>
                            <legend>Inscris toi et tu auras accès à un jeu R.E.V.O.L.U.T.I.O.N.N.A.I.R.E !!</legend>
                            <div class="control-group">
                                    <label class="control-label">Pseudo</label>
                                    <div class="controls">
                                            <input type="text" id="log" name="user_name">
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">Mot de passe</label>
                                    <div class="controls">
                                            <input id="pwd" name="password" type=\'password\'>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">Email</label>
                                    <div class="controls">
                                            <input type="text" id="email" name="email">
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label"></label>
                                    <div class="controls">
                                            <button type="button" onClick="inscription()" class="btn btn-success" >Validation</button>
                                            <button type="button" onClick="retour()" class="btn btn-primary" >Retour</button>
                                    </div>
                            </div>
                    </fieldset>
                    </br>
                    <div class="col-md-12">
                        <div style="display: none;" class="alert alert-success" id="messageOK">
                            <strong><span class="glyphicon glyphicon-ok" ></span> Inscription effectuée ! Vous allez être redirigé. </strong>
                        </div>
                        <div style="display: none;" class="alert alert-danger" id="messageError"> 
                            <span class="glyphicon glyphicon-remove" ></span><strong> Erreur ! Veuillez vérifier vos informations ! Le mot de passe ne doit pas être inférieur à 6 charactères.</strong>
                        </div>
                    </div>
            </form>
        </div>';
        return $res;
    }
    
    private function pagePrincipale() {
        $res = '<header>
            <script type="text/JavaScript" src="./JS/Js_Jeu.js"></script>
            <div class = \'left-score\'>
                <span class = \'ScorePerso\'>
                    <img src=\'../ressource/image/40px-Coin.png\' title="Nombre de parties" height="25" width="25">
                    : '. 
                    $_SESSION['nb_partie']
                    .'
                <span>
            </div>
            <div class = \'left-score\'>
                <span class = \'ScorePerso\'>
                    <img src="../ressource/image/40px-Key.png" title="Nombre de victoires" height="25" width="25"/>
                    : '. 
                    $_SESSION['nb_victoire']
                    .'
                <span>
            </div>
            <div class = \'right\'>
                <span class = \'ScorePerso\'>
                    <img src="../ressource/image/40px-Red_heart.png"  height="25" width="25" title="Pseudo"/>
                    '. 
                    $_SESSION['login'] 
                    .'
                    <img onClick="deconnection()" style="cursor: pointer;" src="./img/exit.png" title="se déconnecter" height="20" width="20" />
                <span>
            </div>
        </header>
        <div id =\'legame\'>
            LE GAME MAGGLE
        </div>';
        return $res;
    }

    private function acceuilpage() {
        $res = '
        <header>
            <script type="text/JavaScript" src="./JS/Js_Connexion.js"></script>
            <table class ="right" >
                <tr>
                    <td class="espaceCellule"><input class="form-control" id="login" type=\'text\' name=\'log\' placeholder= \'Nom\' required/></td>
                    <td class ="espaceCellule"><input class="form-control" id="password" type=\'password\' name=\'pwd\' placeholder= \'Mot de passe\' required/></td>
                    <td><button class="btn btn-sm btn-success" type="button" onClick="validerConnexion()">Connexion</button></td>
                </tr> 
                </tr>
                    <td></td>
                    <td></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onClick="versInscription()" type="button">Inscription</button>            
                    </td>
                </tr>
                <tr>
                    <div class="invisibleError btn-sm btn-default" id="mdpIncorrect">
                        Pseudo ou Mot de passe incorrect !!
                    </div>
                </tr>
            </table>   
        
        </header>
        <legend>Inscris toi et tu auras accès à un jeu R.E.V.O.L.U.T.I.O.N.N.A.I.R.E !!</legend>
        <div id="image" >
            <img src="./img/dobble.png">
        </div>';
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
            $res .= $this->acceuilpage();
        }

        $res = $res . '</br></br>
        <legend></legend>
        <footer>
            Dobble - 2014
            </br>
            LERICHE PIERRE - JEANMOUGIN MATHIEU - PHILIPPE YANNICK
        </footer>
        <script src="./bootstrap/js/bootstrap.min.js"></script></body></html>';
        return $res;
    }

}

?>