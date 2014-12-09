<?php

/**
 * Ce fichier php permet la réponse du serveur lors d'une requête ajax
 */
include_once '../Model/Utilisateur.php';
include_once '../Model/games.php';
include_once '../Model/stacks.php';
include_once '../Model/cards.php';
include_once '../Model/Base.php';
session_start();

//Lors de l'appel ajax, on passe en parametre une action qui définiera la méthode à lancer
$tabSelecteur = array(
    'conn' => 'connexion',
    'deconn' => 'deconnection',
    'inscri' => 'inscription',
    'actuJ' => 'actualiserJoueurs',
    'actuL' => 'actualiserLobby',
    'decPlay' => 'decrementerPlayers',
    'checkSym' => 'checkSymbol',
    'actuJeu' => 'actualiserJeu',
    'retLob' => 'retourLobby'
);

if (array_key_exists($_POST['action'], $tabSelecteur)) {
    $m = $tabSelecteur[$_POST['action']];
    $m();
}

function connexion() {
    $login = (isset($_POST['log'])) ? $_POST['log'] : "";
    $password = (isset($_POST['pwd'])) ? $_POST['pwd'] : "";
    //On vérifie qu'il y a une personne avec un mdp et un pwd passé en parametre
    $user = Utilisateur::findByLogPwd($login, $password);
    if ($user->login != "") {
        //si c'est le cas on nourit le $_session et on renvoi ok sinon ko
        $_SESSION['id_user'] = $user->id_user;
        $_SESSION['login'] = $user->login;
        $_SESSION['nb_partie'] = $user->nb_partie;
        $_SESSION['nb_victoire'] = $user->nb_victoire;
        $_SESSION['mail'] = $user->mail;
        $_SESSION['nbCards'] = $user->nbCards;
        $_SESSION['indexx'] = $user->indexx;
        $_SESSION['game_id'] = $user->game_id;
        $res['find'] = 'ok';
    } else {
        $res['find'] = 'ko';
    }
    echo(json_encode($res));
}

function deconnection() {
    //On détruit la session lors de la deconnection
    $res = session_destroy();
    // s'il se deconnecte en partie on appelle la méthode "actualiserJoueurs" qui fera les modfications pour les personnes 
    // en attentes d'être 4 ! (cette méthode fait un echo qui réponds au serveur donc nous n'en refaisons pas).
    if (isset($_SESSION['game_id'])) {
        actualiserJoueurs();
        $nbjoueurs = games::findById($_SESSION['game_id']);
        if ($nbjoueurs->nbPlayers == 1) {
            $nbjoueurs->delete();
        } else {
            $nb_end = $nbjoueurs->nbPlayers - 1;
            games::incrementGame($_SESSION['game_id'], $nb_end);
        }
        Utilisateur::updatePartie($_SESSION['id_user'], NULL);
    } else {//si tout ce passe bien on renvoi ok
        $res = array('deco' => 'ok');
        echo(json_encode($res));
    }
}

function inscription() {
    $login = (isset($_POST['log'])) ? $_POST['log'] : "";
    $password = (isset($_POST['pwd'])) ? $_POST['pwd'] : "";
    $email = (isset($_POST['email'])) ? $_POST['email'] : "";
    $user = Utilisateur::findByLog($login);
    $res = "ok";
    //on test si le pseudo avec lequel veut s'inscrire une personne n'est pas déjà choisis si c'est le cas nous lui interdisons !
    if ($user->login != "") {
        $res = "ko";
    } else {
        $user = new Utilisateur();
        $user->login = $login;
        $user->password = $password;
        $user->nb_victoire = 0;
        $user->nb_partie = 0;
        $user->mail = $email;
        $user->nbCards = 0;
        $user->indexx = NULL;
        $user->insert();
    }
    $res = array('inscri' => $res);
    echo(json_encode($res));
}

function actualiserJoueurs() {
    $nbPlayers = (isset($_POST['nbPlayers'])) ? $_POST['nbPlayers'] : "";
    //permet de lancer une deuxieme requete ajax qui ne va pas attendre la fin de celle ci pour commencer
    session_write_close();

    //Méthode pour effectuer l'attente côté serveur
    $compteur = 0;
    while (games::findById($_SESSION['game_id'])->nbPlayers == $nbPlayers) {
        usleep(500000);
        $compteur++;
        if ($compteur > 50) {
            $res = array('code' => "relance");
            break;
        }
    }
    //Code html qui permet de changer la barre de chargement selon le nb de joueurs en attente
    $game = games::findById($_SESSION['game_id']);
    if ($nbPlayers != $game->nbPlayers) {
        $code = '<div class="centre-taille40">
                         <div class="progress ">';
        if ($game->nbPlayers == 1) {
            $code .='        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">1 Joueur !</span>
                            </div>';
        }
        if ($game->nbPlayers == 2) {

            $code .='        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">1 Joueur !</span>
                            </div>
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">2 Joueurs !</span>
                            </div>';
        }
        if ($game->nbPlayers == 3) {
            $code .='        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">1 Joueur !</span>
                            </div>
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">2 Joueurs !</span>
                            </div>
                            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">3 Joueurs !</span>
                            </div>';
        }
        if ($game->nbPlayers == 4) {
            $code .='<script>versJeux();</script>';
        }
        $code .='</div></div>';
        $res = array('nbJoueurs' => $game->nbPlayers,
            'code' => $code);
    }
    echo(json_encode($res));
}

function actualiserJeu() {
    $index_courant = (isset($_POST['ind'])) ? $_POST['ind'] : "";
    //permet de lancer une deuxieme requete ajax qui ne va pas attendre la fin de celle ci pour commencer
    session_write_close();

    //Méthode pour effectuer l'attente côté serveur
    $compteur = 0;
    while (games::findById($_SESSION['game_id'])->indexx == $index_courant) {
        usleep(500000);
        $compteur++;
        if ($compteur > 50) {
            $res = array('code' => "relance");
            break;
        }
    }
    //si on arrive à la fin de la partie
    //Afficher sous forme d'un tableau les gagnants avec leurs points respectifs
    if (games::findById($_SESSION['game_id'])->indexx == NULL) {
        $code = '
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
        //on récupère tout les joueurs
        $list_user = Utilisateur::findByGameIdOderBy($_SESSION['game_id']);
        if (!empty($list_user)) {
            $i = 1;
            //On garde l'id du gagnant dans le $_SESSION pour augmenter son nb de victoire par la suite
            foreach ($list_user as $key => $value) {
                $code .= '
                    <tr>
                        <td style="width:33%;">#' . $i . '</td>
                        <td style="width:33%;">' . $value[1] . '</td>
                        <td style="width:33%;">' . $value[5] . '</td>
                    </tr>';
                $i++;
            }
        } else {
            $code .=' 
                    <tr>
                        <td colspan="3" > <font color="red">Nobody Win the GAME !</td>
                    </tr>';
        }

        $code .= '
                </tbody>
            </table>
            <button type="button" onClick="retourLobby()" class="btn btn-primary" >Retour au Lobby</button>';
        //je préviens les autres qu'il y a une modification
        games::Index($_SESSION['game_id'], NULL);
        $res = array('code' => $code, 'end' => 'ok');
        $find = true;
        echo(json_encode($res));
    } else {
        //si ça n'est pas la fin de la partie alors on raffraichis la partie
        $code = '';
        //Si l'index de la pioche est différent de celui qu'il devrait etre alors on fait les modifications 
        //pour afficher la nouvelle carte de la pioche
        if (games::findById($_SESSION['game_id'])->indexx != $index_courant) {
            $list_user = Utilisateur::findByGameId($_SESSION['game_id']);
            $decks = array();
            $id;
            //on récupère l'id de la personne connecté pour lui affecté une carte qui n'est pas la meme qu'aux autres
            foreach ($list_user as $key => $value) {
                if ($value[1] == $_SESSION['login']) {
                    $id = $key;
                }
            }
            //on ajoute les symboles du joueur connecté dans l'array en position 0
            $me = Utilisateur::findById($_SESSION['id_user']);
            $stack = stacks::findByOrder($me->indexx);
            $carte = cards::findById($stack->card_id);
            $symbole = array($carte->symbol0, $carte->symbol1, $carte->symbol2, $carte->symbol3, $carte->symbol4, $carte->symbol5, $carte->symbol6, $carte->symbol7);
            $decks[0] = $symbole;


            //on ajoute la pioche à la place 1 dans l'array
            $game = games::findById($_SESSION['game_id']);
            $stack = stacks::findByOrder($game->indexx);
            $carte = cards::findById($stack->card_id);
            $symbole = array($carte->symbol0, $carte->symbol1, $carte->symbol2, $carte->symbol3, $carte->symbol4, $carte->symbol5, $carte->symbol6, $carte->symbol7);
            shuffle($symbole);
            $decks[1] = $symbole;

            $users = Utilisateur::findByGameId($_SESSION['game_id']);
            $j = 2;
            foreach ($users as $key => $value) {
                //si c'est un adverse alors on le met dans une array à la place 2-3-4
                if ($value[0] != $_SESSION['id_user']) {
                    $stack = stacks::findByOrder($value[3]);
                    $carte = cards::findById($stack->card_id);
                    $symbole = array($carte->symbol0, $carte->symbol1, $carte->symbol2, $carte->symbol3, $carte->symbol4, $carte->symbol5, $carte->symbol6, $carte->symbol7);
                    $decks[$j] = $symbole;
                    $j++;
                }
            }
            //ensuite on créé le code qui va permettre le raffraichissement
            $listStack = $decks;
            $listUtil = $list_user;
            $code .='<div><legend>Score des joueurs</legend>';
            foreach ($listUtil as $key => $value) {
                $code .= '<span class ="ScorePartie">' . $value[1] . '
                            : ' . $value[2] . '
                        </span>';
            }
            $code .= '</div><legend>Partie</legend>'
                    . '<h3 style="width:420px; display:inline-block" class="centre"><span id="plus" class="label label-default">Votre Carte</span>'
                    . '<h3 style="width:420px; display:inline-block;" class="centre"><span class="label label-default">La pioche</span></h3>';
            $i = 0;
            foreach ($listStack as $id => $listSymbol) {
                if ($id == 2) {
                    $code .= '<h3 class="centre"><span class="label label-default">Cartes Adverses</span></h3>';
                    $code .= '<div class = "CarteC adversaire">';
                    foreach ($listSymbol as $key => $value) {
                        $code .= '<p class = \'Icone\'>
                            <img src="../ressource/image/0' . $value . '.png" alt=""/>
                   </p>';
                    }
                    $code .= '</div>';
                } elseif ($i == 3) {
                    $code .= '<div class = "CarteC adversaire">';
                    foreach ($listSymbol as $key => $value) {
                        $code .= '<p class = \'Icone\'>
                                <img src="../ressource/image/0' . $value . '.png" alt=""/>
                            </p>';
                    }
                    $code .= '</div>';
                } elseif ($i == 4) {
                    $code .= '<div class = "CarteC adversaire">';
                    foreach ($listSymbol as $key => $value) {
                        $code .= '<p class = \'Icone\'>
                                <img src="../ressource/image/0' . $value . '.png" alt=""/>
                            </p>';
                    }
                    $code .= '</div>';
                    $code .= '</div>';
                } elseif ($i == 1) {
                    $code .= '<div class = "CarteC Pile" >';
                    foreach ($listSymbol as $key => $value) {
                        $code .= '<p class = \'Icone\'>
                            <img src="../ressource/image/0' . $value . '.png" alt=""/>
                        </p>';
                    }
                    $code .= '</div>';
                    $code .= '</div>';
                } elseif ($i == 0) {
                    $code .= '<div id="block">';
                    $code .= '<div class = "CarteC perso">';
                    foreach ($listSymbol as $key => $value) {
                        $code .= '<p class = \'Icone Cperso\' id=' . $value . '>
                            <img src="../ressource/image/0' . $value . '.png" alt=""/>
                        </p>';
                    }
                    $code .= '</div>';
                }
                $i++;
            }
            $new_index = $index_courant + 1;
            $res = array('code' => $code, 'new_ind' => $new_index);
        }
        echo(json_encode($res));
    }
}

function actualiserLobby() {
    $games = games::findAll();
    $resGames = array();
    foreach ($games as $key => $gamesObject) {
        $resGames[$key]['id'] = $gamesObject->id;
        $resGames[$key]['nbPlayers'] = $gamesObject->nbPlayers;
        $resGames[$key]['indexx'] = $gamesObject->indexx;
    }
    $res = '<table class="table table-hover">
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
    if (!empty($resGames)) {
        foreach ($resGames as $key => $value) {
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
            </table>';
    echo(json_encode($res));
}

function decrementerPlayers() {
    $game = games::findById($_SESSION['game_id']);
    Utilisateur::updatePartie($_SESSION['id_user'], NULL);
    //décremente le nombre de joueurs
    $nbPlayers = $game->nbPlayers - 1;
    if ($nbPlayers <= 0) {
        $games = games::findById($_SESSION['game_id']);
        $games->delete();
    } else {
        games::incrementGame($_SESSION['game_id'], $nbPlayers);
    }
    $res = array('decrementer' => "ok");
    unset($_SESSION['game_id']);
    echo(json_encode($res));
}

//cette méthode permet de savoir si le symbole sur lequel à cliqué le joeurs fait partie des symboles de la pioche
function checkSymbol() {
    $id_symbol = (isset($_POST['id_symbol'])) ? $_POST['id_symbol'] : "";
    $user = Utilisateur::findById($_SESSION['id_user']);
    $game = games::findById($_SESSION['game_id']);
    $stack = stacks::findByOrder($game->indexx);
    $carte = cards::findById($stack->card_id);
    $find = false;

    //tester si le symbole choisis fait partie de ceux de la pioche :
    for ($index = 0; $index < 8; $index++) {
        $symbol = "symbol" . $index;
        if ($carte->$symbol == $id_symbol) {
            try {
                $db = Base::getConnection();
                $db->beginTransaction();

                //on stock la valeur de l'index pour detecter les éventuels roolback à effectuer par la suite
                $id = $game->indexx;
                //on augmente le nb de points du joueurs de 1
                $nb_pts = $user->nbCards + 1;
                $sth = $db->exec("update utilisateur set  nbCards= " . $nb_pts . "
                                                 where id_user =" . $_SESSION['id_user']);
                //on change sa carte
                $sth = $db->exec("update utilisateur set  indexx=" . $game->indexx . "
                                                 where id_user =" . $_SESSION['id_user']);
                //on change la pioche
                $new_index = $game->indexx + 1;
                $sth = $db->exec("update games set indexx=" . $new_index . "
                                               where id =" . $_SESSION['game_id']);
                $new_id = $id + 1;
                //on test si l'index n'a pas évolué entre temps sinon on rollback
                if (games::findById($_SESSION['game_id'])->indexx != $new_id) {
                    $db->rollback();
                } else {//si tout ce passe bien on commit
                    $db->commit();
                }
            } catch (Exception $e) {
                $db->rollback();
            }
            //si on arrive à la fin de la partie
            if ($new_index == 58) {
                //On réveille les autres joueurs ! 
                games::Index($_SESSION['game_id'], NULL);
                //et on ajout le gagnant
                $list_user = Utilisateur::findByGameIdOderBy($_SESSION['game_id']);
                $user = Utilisateur::findById($list_user[0][0]);
                $nb_victoire_end = $user->nb_victoire + 1;
                Utilisateur::updateNbVictoire($user->id_user, $nb_victoire_end);
                $res = array('end' => 'ok');
                $find = true;
                echo(json_encode($res));
            } else {
                $find = true;
                $_POST['ind'] = $game->indexx;
                actualiserJeu();
                break;
            }
        }
    }
    //si il n'est pas trouvé, on send un message bad => ok
    if (!$find) {
        $res = array('code' => "bad");
        echo(json_encode($res));
    }
}

function retourLobby() {
    //On test si le $_session['id_game'] est null pour ne pas qu'il fasse F5 pour modifier 46 fois ses données !!
    if ($_SESSION['game_id'] != NULL) {

        //on met à null l'index de la carte qu'il possède en partie
        Utilisateur::updateIndexx($_SESSION['id_user'], NULL);
        //on met à null l'id de la game auquelle est lié le joueur
        Utilisateur::updatePartie($_SESSION['id_user'], NULL);
        //on met les points du joueurs à 0 
        Utilisateur::updatePoint($_SESSION['id_user'], 0);
        //On augmente le nb de partie du joueurs de 1.
        $nb_partie_end = $_SESSION['nb_partie'] + 1;
        Utilisateur::updateNbPartie($_SESSION['id_user'], $nb_partie_end);
        //on réinitialise les variables inutiles du $_Session
        unset($_SESSION['indexx']);
        unset($_SESSION['game_id']);
        unset($_SESSION['gagnant']);
        $res = array('retour' => "ok");
        echo(json_encode($res));
    }
}

?>