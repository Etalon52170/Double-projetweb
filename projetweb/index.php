<?php
include './Controller/WebController.php';
$controleur = new WebController();
$controleur->callAction($_GET);
?>
