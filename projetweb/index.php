<?php

if (empty($_SESSION)) {
    session_start();
}

include './Controller/WebController.php';
$controleur = new WebController();
$controleur->callAction($_GET);
/*
  include './Model/cards.php';
  $a = cards();
  // de 0 à 56
  foreach ($a as $idCards => $arrayIcon) {
  $cards = new cards();
  $cards->symbol0 = $arrayIcon[0];
  $cards->symbol1 = $arrayIcon[1];
  $cards->symbol2 = $arrayIcon[2];
  $cards->symbol3 = $arrayIcon[3];
  $cards->symbol4 = $arrayIcon[4];
  $cards->symbol5 = $arrayIcon[5];
  $cards->symbol6 = $arrayIcon[6];
  $cards->symbol7 = $arrayIcon[7];
  $cards->insert();
  }
  function cards() {

  function c($i, $j) {
  return $i + 7 * $j + 9;
  }

  $A = array();
  $B = array();
  $C = array();
  for ($i = 0; $i < 8; $i++) {
  $A[0][] = 1 + $i;
  }
  for ($i = 0; $i < 7; $i++) {
  $B[$i][] = 1;
  for ($j = 0; $j < 7; $j++) {
  $B[$i][] = c($i, $j);
  }
  }
  for ($i = 0; $i < 7; $i++) {
  for ($j = 0; $j < 7; $j++) {
  $C[$i + 7 * $j][] = $i + 2;
  for ($k = 0; $k < 7; $k++) {
  $C[$i + 7 * $j][] = c($k, ($k * $i + $j) % 7);
  }
  }
  }
  return array_merge($A, $B, $C);
  } */
?>
