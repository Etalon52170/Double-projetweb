<?php

include_once '../Model/Utilisateur.php';
include_once '../Model/games.php';
session_start();

$tabSelecteur = array(
    'conn' => 'connexion',
    'deconn' => 'deconnection',
    'inscri' => 'inscription',
    'actuJ' => 'actualiserJoueurs',
    'decPlay' => 'decrementerPlayers'
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
        $_SESSION['game_id'] = $user->game_id;
        $res['find'] = 'ok';
    } else {
        $res['find'] = 'ko';
    }
    echo(json_encode($res));
}

function deconnection() {
    $res = session_destroy();
    $res = array('deco' => $res);
    echo(json_encode($res));
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
        $user->insert();
    }
    $res = array('inscri' => $res);
    echo(json_encode($res));
}

function actualiserJoueurs() {
    $nbPlayers = (isset($_POST['nbPlayers'])) ? $_POST['nbPlayers'] : "";
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
        $code .='</div></div>';
        $res = array('nbJoueurs' => $game->nbPlayers,
            'code' => $code);
        echo(json_encode($res));
    }
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

?>