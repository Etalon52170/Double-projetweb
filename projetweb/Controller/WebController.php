<?php

include_once './Model/Utilisateur.php';
include_once 'Controller.php';
include_once './View/Vue.php';

class WebController extends Controller {

    public function __construct() {
        $this->actionlist = array('jeux' => 'afficheJeux',
            'inscri' => 'inscription');
    }

    protected function afficheJeux() {
        if (isset($_SESSION['id_user'])) {
            $view = new Vue();
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

}

?>