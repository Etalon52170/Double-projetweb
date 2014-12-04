<?php

include_once './Model/Utilisateur.php';
include_once './Model/games.php';
include_once 'Controller.php';
include_once './View/Vue.php';
include_once './Model/cards.php';
include_once './Model/stacks.php';

class WebController extends Controller {

    public function __construct() {
        $this->actionlist = array('jeux' => 'afficheJeux',
            'inscri' => 'inscription',
            'partie' => 'partie',
            'arene' => 'arene');
    }

    protected function afficheJeux() {
        if (isset($_SESSION['id_user'])) {
            $listPartie = games::findAll();
            $res = array();
            foreach ($listPartie as $key => $gamesObject) {
                $res[$key]['id'] = $gamesObject->id;
                $res[$key]['nbPlayers'] = $gamesObject->nbPlayers;
                $res[$key]['indexx'] = $gamesObject->indexx;
            }
            $view = new Vue();
            $view->tab_partie = $res;
            echo $view->affichageGeneral('jeux');
        } else {
            $this->defaultAction();
        }
    }

    protected function defaultAction() {
        if (isset($_SESSION['id_user'])) {
            $view = new Vue();
            echo $view->affichageGeneral('jeux');
        } else {
            $view = new Vue();
            echo $view->affichageGeneral('acceuil');
        }
    }

    protected function inscription() {
        $view = new Vue();
        echo $view->affichageGeneral('inscri');
    }

    protected function partie() {
        $view = new Vue();
        if (isset($_SESSION['id_user'])) {
            if (isset($_GET['g'])) {
                $game = games::findById($_GET['g']);
                if ($game->nbPlayers == "") {
                    //cette partie n'existe pas
                    echo 'partie n\'existe pas';
                } else {
                    if ($game->nbPlayers < 4) {
                        //rejoindre une partie existante avec un nb de joueur < 4
                        $nbPlayers = $game->nbPlayers + 1;
                        //on met à jour la partie dans laquelle joue le joueur
                        Utilisateur::updatePartie($_SESSION['id_user'], $_GET['g']);
                        $_SESSION['game_id'] = $_GET['g'];
                        //inscrémente le nb de joueurs
                        games::incrementGame($_GET['g'], $nbPlayers);
                        $view->nbPlayers = $nbPlayers;
                        echo $view->affichageGeneral('partie');
                    } else {
                        //partie pleine
                        echo 'partie pleine';
                    }
                }
            } else {
                //nouvelle partie
                $game = new games();
                $game->nbPlayers = 1;
                $id = $game->insert();
                //mise à jour du joueur
                Utilisateur::updatePartie($_SESSION['id_user'], $id);
                $_SESSION['game_id'] = $id;
                $view->nbPlayers = 1;
                echo $view->affichageGeneral('partie');
            }
        } else {
            echo $view->affichageGeneral('acceuil');
        }
    }

    protected function arene() {
        $view = new Vue();

        $nomjoueur = Utilisateur::findByGameId($_SESSION['game_id']);
        $piles = stacks::CreateOrFind($_SESSION['game_id']);
        $decks = array();
        $id;
        foreach ($nomjoueur as $key => $value) {
            if ($value[1] == $_SESSION['login']) {
                $id = $key;
            }
        }
        $carte = cards::findById($piles[$id]->card_id);
        $symbole = array($carte->symbol0, $carte->symbol1, $carte->symbol2, $carte->symbol3, $carte->symbol4, $carte->symbol5, $carte->symbol6, $carte->symbol7);
        shuffle($symbole);
        $decks[0] = $symbole;
        $carte = cards::findById($piles[4]->card_id);
        $symbole = array($carte->symbol0, $carte->symbol1, $carte->symbol2, $carte->symbol3, $carte->symbol4, $carte->symbol5, $carte->symbol6, $carte->symbol7);
        $decks[1] = $symbole;
        $j = 2;
        for ($i = 0; $i < 4; $i++) {
            if ($id == $i) {
                
            } else {
                $carte = cards::findById($piles[$i]->card_id);
                $symbole = array($carte->symbol0, $carte->symbol1, $carte->symbol2, $carte->symbol3, $carte->symbol4, $carte->symbol5, $carte->symbol6, $carte->symbol7);
                //shuffle($symbole);
                $decks[$j] = $symbole;
                $j++;
            }
        }
        $view->listStack = $decks;
        $view->listUtil = $nomjoueur;
        echo $view->affichageGeneral('arene');
        //print_r($nomjoueur);
    }

}

?>