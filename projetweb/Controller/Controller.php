<?php
/**
 * Classe qui est créé dans l'index.php au lancement d'une page et qui permet de choisir les méthodes à lancer.
 */
abstract class Controller {

    protected $actionlist;

    abstract protected function defaultAction();

    //Cette méthode va appeler soit une action par défaut si il n'y a pas le parametre a ( pour action ) dans l'url.
    public function callAction($get) {
        if (!isset($get['a'])) {
            $this->defaultAction();
        } else {
            //sinon on appelle une méthode correspondante à l'action.
            $r = $this->actionlist[$get['a']];
            $this->$r($get);
        }
    }

}

?>