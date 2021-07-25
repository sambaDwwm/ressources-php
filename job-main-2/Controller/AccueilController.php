<?php


namespace Controller;



class AccueilController extends BaseController{
    public function index(){

    }
    public function nonTrouve(){
        $this->afficherVue('404');
    }
}