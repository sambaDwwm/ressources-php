<?php

namespace Controller;

use DAO\ProduitDao;
use DAO\UtilisateurDao;

class AccueilController extends BaseController
{

    //url : localhost/.../accueil/index ou localhost/.../accueil
    public function index()
    {
        $dao = new ProduitDao();

        $listeProduit = $dao->findAll();

        $dao = new UtilisateurDao();

        $listeUtilisateur = $dao->findAll();

        $donnees = compact('listeProduit', 'listeUtilisateur');
        //note equivaut à faire :
        /*$donnees = [
            'listeProduit' => $listeProduit,
            'listeUtilisateur' => $listeUtilisateur
        ];*/

        $this->afficherVue('index', $donnees);
    }

    public function nonTrouve()
    {
        $this->afficherVue("404");
    }
}
