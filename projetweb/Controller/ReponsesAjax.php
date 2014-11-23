<?php

include_once '../Model/Utilisateur.php';
$login = (isset($_GET['log'])) ? $_GET['log'] : $_GET['log'];
$password = (isset($_GET['pwd'])) ? $_GET['pwd'] : $_GET['pwd'];
$userList = Utilisateur::findAll();
foreach ($userList as $user) {
    
    if ($user->login == $login && $user->password == $password) {
        $res = "ok";
    }
}
if (!isset($res)) {
    $res = 'ko';
}

$res = array('find' => $res);
echo(json_encode($res));
?>