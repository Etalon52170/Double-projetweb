<?php

abstract class Controller {

    protected $actionlist;

    abstract protected function defaultAction($p);

    public function callAction($get) {
        if (!isset($get['a'])) {
            $this->defaultAction($get);
        } else {
            $r = $this->actionlist[$get['a']];
            $this->$r($get);
        }
    }

}

?>