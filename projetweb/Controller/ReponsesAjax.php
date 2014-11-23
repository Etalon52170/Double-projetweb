<?php

include_once '../Model/Utilisateur.php';
session_start();

$tabSelecteur = array(
    'conn' => 'connexion',
    'deconn' => 'deconnection',
    'inscri' => 'inscription'
);

if (array_key_exists($_GET['action'], $tabSelecteur)) {
    $m = $tabSelecteur[$_GET['action']];
    $m();
}

function connexion() {
    $login = (isset($_GET['log'])) ? $_GET['log'] : "";
    $password = (isset($_GET['pwd'])) ? $_GET['pwd'] : "";
    $userList = Utilisateur::findAll();
    foreach ($userList as $user) {

        if ($user->login == $login && $user->password == $password) {
            $id = $user->id_user;
            $res = "ok";
        }
    }
    if (!isset($res)) {
        $res = 'ko';
    } else {
        $userConnect = Utilisateur::findById($id);
        $_SESSION['id_user'] = $userConnect->id_user;
        $_SESSION['login'] = $userConnect->login;
        $_SESSION['nb_partie'] = $userConnect->nb_partie;
        $_SESSION['nb_victoire'] = $userConnect->nb_victoire;
        $_SESSION['mail'] = $userConnect->mail;
    }

    $res = array('find' => $res);
    echo(json_encode($res));
}

function deconnection(){
    $res = session_destroy();
    $res = array('deco' => $res);
    echo(json_encode($res));
}


function inscription(){
    $login = (isset($_GET['log'])) ? $_GET['log'] : "";
    $password = (isset($_GET['pwd'])) ? $_GET['pwd'] : "";
    $email = (isset($_GET['email'])) ? $_GET['email'] : "";
    $user = new Utilisateur();
    $user->login = $login;
    $user->password = $password;
    $user->nb_victoire = 0;
    $user->nb_partie = 0;
    $user->mail = $email;
    $user->insert();
    $res = array('inscri' => "true");
    echo(json_encode($res));
}
?>