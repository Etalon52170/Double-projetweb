<?php

include_once './Model/Utilisateur.php';
include_once 'Controller.php';

class WebController extends Controller {

    public function __construct() {
        $this->actionlist = array('jeux'=>'afficheJeux');
    }

    protected function afficheJeux(){
         if ($_POST['confirme2'] == "OK") {
            $tab = Enfant::findByNom($_POST['Nom']);
            $view = new Vue();
            $view->tab = $tab;
            echo $view->affichageGeneral('listE');
        }
    }
    
    protected function defaultAction($p) {
        echo $view->affichageGeneral('defaut');
    }

}

?>