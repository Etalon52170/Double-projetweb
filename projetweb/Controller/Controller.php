<?php

abstract class Controller {

    protected $actionlist;

    abstract protected function defaultAction();

    public function callAction($get) {
        if (!isset($get['a'])) {
            $this->defaultAction();
        } else {
            $r = $this->actionlist[$get['a']];
            $this->$r($get);
        }
    }

}

?>