<?php 

namespace controller;

use Controller\BaseController;

class AccueilController extends BaseController {
    public function index() {

    }

    public function nonTrouve() {
        $this->afficherVue("404");
    }
}