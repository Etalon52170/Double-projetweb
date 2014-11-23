<?php
if(empty($_SESSION))
{
    session_start();
}

include './Controller/WebController.php';
$controleur = new WebController();
$controleur->callAction($_GET);
?>
