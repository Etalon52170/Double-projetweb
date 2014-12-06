<?php

include_once '../Model/Utilisateur.php';
include_once '../Model/games.php';
include_once '../Model/stacks.php';
include_once '../Model/cards.php';
include_once '../Model/Base.php';
session_start();

$tabSelecteur = array(
    'conn' => 'connexion',
    'deconn' => 'deconnection',
    'inscri' => 'inscription',
    'actuJ' => 'actualiserJoueurs',
    'decPlay' => 'decrementerPlayers',
    'checkSym' => 'checkSymbol',
    'actuJeu' => 'actualiserJeu'
);

if (array_key_exists($_POST['action'], $tabSelecteur)) {
    $m = $tabSelecteur[$_POST['action']];
    $m();
}

function connexion() {
    $login = (isset($_POST['log'])) ? $_POST['log'] : "";
    $password = (isset($_POST['pwd'])) ? $_POST['pwd'] : "";
    $user = Utilisateur::findByLogPwd($login, $password);
    if ($user->login != "") {
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
    $res = session_destroy();
    actualiserJoueurs();
    $nbjoueurs = games::findById($_SESSION['game_id']);
    if($nbjoueurs->nbPlayers == 1){
        $nbjoueurs->delete();
    }else{
        $nb_end = $nbjoueurs->nbPlayers -1;
        games::incrementGame($_SESSION['game_id'], $nb_end);
    }
    Utilisateur::updatePartie($_SESSION['id_user'], NULL);
}

function inscription() {
    $login = (isset($_POST['log'])) ? $_POST['log'] : "";
    $password = (isset($_POST['pwd'])) ? $_POST['pwd'] : "";
    $email = (isset($_POST['email'])) ? $_POST['email'] : "";
    $user = Utilisateur::findByLog($login);
    $res = "ok";
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
    session_write_close();
    $compteur=0;
    while (games::findById($_SESSION['game_id'])->nbPlayers == $nbPlayers) {
        usleep(500000);
        $compteur++;
        if ($compteur > 50) {
            $res = array('code' => "relance");
            break;
        }
    }
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
        if($game->nbPlayers == 4){
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
    $compteur = 0;
    session_write_close();
    while (games::findById($_SESSION['game_id'])->indexx == $index_courant) {
        usleep(500000);
        $compteur++;
        if ($compteur > 50) {
            $res = array('code' => "relance");
            break;
        }
    }
    $code = '';
    if (games::findById($_SESSION['game_id'])->indexx != $index_courant) {
        $list_user = Utilisateur::findByGameId($_SESSION['game_id']);
        $decks = array();
        $id;
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
        shuffle($symbole);
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
                shuffle($symbole);
                $decks[$j] = $symbole;
                $j++;
            }
        }
        $listStack = $decks;
        $listUtil = $list_user;
        $code .='<div><legend>Score des joueurs</legend>';
        foreach ($listUtil as $key => $value) {
            $code .= '<span class ="ScorePartie">' . $value[1] . '
                            : 0
                        </span>';
        }
        $code .= '</div><legend>Partie</legend>'
                . '<h3 style="width:420px; display:inline-block" class="centre"><span class="label label-default">Votre Carte</span>'
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
    $_SESSION['game_id'] = "";
    echo(json_encode($res));
}

function checkSymbol() {
    $id_symbol = (isset($_POST['id_symbol'])) ? $_POST['id_symbol'] : "";
    $user = Utilisateur::findById($_SESSION['id_user']);
    $game = games::findById($_SESSION['game_id']);
    $stack = stacks::findByOrder($game->indexx);
    $carte = cards::findById($stack->card_id);
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
        }
    }
    $_POST['ind'] = $game->indexx;
    actualiserJeu();
}

?>