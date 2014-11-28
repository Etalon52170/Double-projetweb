<?php

include_once '../Model/Utilisateur.php';
session_start();

$tabSelecteur = array(
    'conn' => 'connexion',
    'deconn' => 'deconnection',
    'inscri' => 'inscription',
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
        $res = "ok";
    } else {
        $res = 'ko';
    }
    $res = array('find' => $res);
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
        $user->insert();
    }
    $res = array('inscri' => $res);
    echo(json_encode($res));
}

?>