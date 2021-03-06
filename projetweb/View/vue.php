<?php

class Vue {

    public $index_courant;
    public $nbPlayers;
    public $tab_partie;
    public $listStack;
    public $listUtil;
    public $listUtilOrder;
    protected $tabSelecteur = array(
        'acceuil' => 'acceuilpage',
        'jeux' => 'lobby',
        'inscri' => 'inscription',
        'partie' => 'partie',
        'arene' => 'arene'
    );

    public function __construct() {
        
    }
    //getter - setter
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

    //affichage de la vue de la partie
    private function arene() {
        $res = $this->header();
        if ($this->index_courant != 58) {
            $res .=
                    '<div id="ActuJeu"><div><legend>Score des joueurs</legend>';
            foreach ($this->listUtil as $key => $value) {
                $res .= '<span class ="ScorePartie">' . $value[1] . '
                            : ' . $value[2] . '
                        </span>';
            }
            $res .= '</div><legend>Partie</legend>'
                    . '<h3 style="width:420px; display:inline-block" class="centre"><span class="label label-default">Votre Carte</span>'
                    . '<h3 style="width:420px; display:inline-block;" class="centre"><span class="label label-default">La pioche</span></h3>';
            // $ordre = array(1,2,3,4,0);
            $i = 0;
            foreach ($this->listStack as $id => $listSymbol) {
                if ($id == 2) {
                    $res .= '<h3 class="centre"><span class="label label-default">Cartes Adverses</span></h3>';
                    $res .= '<div class = "CarteC adversaire">';
                    foreach ($listSymbol as $key => $value) {
                        $res .= '<p class = \'Icone\'>
                            <img src="../ressource/image/0' . $value . '.png" alt=""/>
                   </p>';
                    }
                    $res .= '</div>';
                } elseif ($i == 3) {
                    $res .= '<div class = "CarteC adversaire">';
                    foreach ($listSymbol as $key => $value) {
                        $res .= '<p class = \'Icone\'>
                                <img src="../ressource/image/0' . $value . '.png" alt=""/>
                            </p>';
                    }
                    $res .= '</div>';
                } elseif ($i == 4) {
                    $res .= '<div class = "CarteC adversaire">';
                    foreach ($listSymbol as $key => $value) {
                        $res .= '<p class = \'Icone\'>
                                <img src="../ressource/image/0' . $value . '.png" alt=""/>
                            </p>';
                    }
                    $res .= '</div>';
                } elseif ($i == 1) {
                    $res .= '<div class = "CarteC Pile" >';
                    foreach ($listSymbol as $key => $value) {
                        $res .= '<p class = \'Icone\'>
                            <img src="../ressource/image/0' . $value . '.png" alt=""/>
                        </p>';
                    }
                    $res .= '</div>';
                    $res .= '</div>';
                } elseif ($i == 0) {
                    $res .= '<div id="block">';
                    $res .= '<div class = "CarteC perso">';
                    foreach ($listSymbol as $key => $value) {
                        $res .= '<p class = \'Icone Cperso\' id=' . $value . '>
                            <img src="../ressource/image/0' . $value . '.png" alt=""/>
                        </p>';
                    }
                    $res .= '</div>';
                }
                $i++;
            }
            $res .='<script>actualiserJeu(' . $this->index_courant . ');</script></div>';
        } else {
            //Afficher sous forme d'un tableau les gagnants avec leurs points respectifs
            $res .= '
            <table class="table table-hover">
                <thead>
                    <tr>    
                        <th colspan="3" class="head_underline">Classement des joueurs :</th>
                    </tr>
                    <tr>
                        <th class="centre">Pos</th>
                        <th class="centre">Login</th>
                        <th class="centre">Nombre de Points</th>
                    </tr>
                </thead>
                <tbody>';
            if (!empty($this->listUtilOrder)) {
                $i = 1;
                foreach ($this->listUtilOrder as $key => $value) {
                    $res .= '
                    <tr>
                        <td style="width:33%;">#' . $i . '</td>
                        <td style="width:33%;">' . $value[1] . '</td>
                        <td style="width:33%;">' . $value[5] . '</td>
                    </tr>';
                    $i++;
                }
            } else {
                $res .=' 
                    <tr>
                        <td colspan="3" > <font color="red">Nobody Win the GAME !</td>
                    </tr>';
            }

            $res .= '
                </tbody>
            </table>
            <button type="button" onClick="retour()" class="btn btn-primary" >Retour au Lobby</button>';
        }
        return $res;
    }

    private function header() {
        $res = '
        <header>
        <script src="./bootstrap/js/jquery-1.11.0.min.js"></script>
            <script type="text/JavaScript" src="./JS/Js_Jeu.js"></script>
            <div class = \'left-score\'>
                <span class = \'ScorePerso\'>
                    <img src=\'./img/The_D6_Icon.png\' title="Nombre de parties">
                    : ' .
                Utilisateur::findById($_SESSION['id_user'])->nb_partie
                . '
                <span>
            </div>
            <div class = \'left-score\'>
                <span class = \'ScorePerso\'>
                    <img src="./img/Holy_Grail_Icon.png" title="Nombre de victoires"/>
                    : ' .
                Utilisateur::findById($_SESSION['id_user'])->nb_victoire
                . '
                <span>
            </div>
            <div class = \'right\'>
                <span class = \'ScorePerso\'>
                    <img src="../ressource/image/027.png" title="Pseudo"/>
                    ' .
                $_SESSION['login']
                . '
                    <img onClick="deconnection()" style="cursor: pointer;" src="./img/exit.png" title="se déconnecter" height="20" width="20" />
                <span>
            </div>
        </header>';
        return $res;
    }

    //affichage de la barre de chargement en attente de 4 joueurs
    private function partie() {
        $pourcent = $this->nbPlayers * 25;
        $res = $this->header() .
                '<div class="head_underline">En attente de 4 joueurs :</div></br>
                 <div id="barre">
                    <div class="centre-taille40">
                         <div class="progress ">';
        if ($this->nbPlayers == 1) {
            $res .='        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">1 Joueur !</span>
                            </div>';
        }
        if ($this->nbPlayers == 2) {

            $res .='        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">1 Joueur !</span>
                            </div>
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">2 Joueurs !</span>
                            </div>';
        }
        if ($this->nbPlayers == 3) {
            $res .='        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">1 Joueur !</span>
                            </div>
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">2 Joueurs !</span>
                            </div>
                            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">3 Joueurs !</span>
                            </div>';
        }
        if ($this->nbPlayers == 4) {
            $res .= '<script>versJeux();</script>';
        }
        $res .='        </div>
                    </div>
              </div>
              <script>actualiserJoueurs(' . $this->nbPlayers . ');</script>
              <button type="button" onClick="retour()" class="btn btn-primary" >Quitter la partie en attente</button>';
        return $res;
    }

    //vue de l'inscription
    private function inscription() {
        $res = '
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
                                            <input placeholder= \'Pseudo\' type="text" id="log" name="user_name">
                                    </div>
                                    <div style="display: none;" id="messageErrorLogin"> 
                                        </br>
                                        <div class="alert alert-danger">
                                            <span class="glyphicon glyphicon-remove" ></span><strong> Erreur ! Ce pseudo est déjà utilisé !</strong>
                                        </div>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">Mot de passe</label>
                                    <div class="controls">
                                            <input placeholder= \'Mot de passe\' id="pwd" name="password" type=\'password\'>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">Email</label>
                                    <div class="controls">
                                            <input placeholder= \'Mail\' type="text" id="email" name="email">
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
                            <span class="glyphicon glyphicon-remove" ></span><strong> Erreur ! Le mot de passe ne doit pas être inférieur à 6 charactères et l\'adresse mail doit être correcte.</strong>
                        </div>
                    </div>
            </form>
        </div>';
        return $res;
    }

    //vue de la liste de partie en attente de joueurs
    private function lobby() {
        $res = $this->header() . '
        <div id="bs-example">
            <table class="table table-hover">
                <thead>
                    <tr>    
                        <th colspan="2" class="head_underline">Liste de toutes les parties en attentes :</th>
                        <th><button style="float:right;" class="btn btn-sm btn-primary" type="button" onClick="newGame()">Créer une nouvelle partie</button></th>
                    </tr>
                    <tr>
                        <th class="centre">Numéro Partie</th>
                        <th class="centre">Nombre de joueurs</th>
                        <th class="centre">Rejoindre</th>
                    </tr>
                </thead>
                <tbody>';
        $message_vide = false;
        $message_plein = false;
        if (!empty($this->tab_partie)) {
            foreach ($this->tab_partie as $key => $value) {
                if ($value['nbPlayers'] < 4) {
                    $message_plein = true;
                    $res .= '<tr>
                        <td style="width:33%;">' . $value['id'] . '</td>
                        <td style="width:33%;">' . $value['nbPlayers'] . '/4 </td>
                        <td style="width:33%;"><button class="btn btn-sm btn-success" type="button" onClick="joinGame(' . $value['id'] . ')">Join</button></td>
                    </tr>';
                } else {
                    $message_vide = true;
                }
            }
        } else {
            $message_vide = true;
        }
        if ($message_vide && !$message_plein) {
            $res .=' <tr>
                        <td colspan="3" > <font color="red">Aucune partie n\'est disponible !</td>
                    </tr>
                    <tr>
                        <td colspan="3" > <font color="red">Vous pouvez en créer une !</td>
                    </tr>';
        }

        $res .= '  
                </tbody>
            </table>
        </div>
        <script>actualiserLooby();</script>';
        return $res;
    }

    //acceuil
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
        <script src="./bootstrap/js/jquery-1.11.0.min.js"></script>
        <script src="./bootstrap/js/bootstrap.min.js"></script></body></html>';
        return $res;
    }

}

?>
