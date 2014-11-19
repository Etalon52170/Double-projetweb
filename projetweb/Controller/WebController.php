<?php

include_once './Model/Utilisateur.php';
include_once 'Controller.php';

class WebController extends Controller {

    public function __construct() {
        $this->actionlist = array('jeux' => 'afficheJeux');
    }

    protected function afficheJeux() {
        $view = new Vue();
        echo $view->affichageGeneral('jeux');
    }

    protected function defaultAction($p) {
        $view = new Vue();
        echo $view->affichageGeneral('acceuil');
    }

}

?>