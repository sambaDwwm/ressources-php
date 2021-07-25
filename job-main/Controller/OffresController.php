<?php

namespace Controller;

class OffreController extends BaseController
{

    //url : localhost/.../panier/index ou localhost/.../panier
    public function index()
    {
        $this->afficherVue();
    }

    public function supprimerOffre($parametres)
    {
        echo "Suppression de l'article avec l'id ";
        echo $parametres[0] . "<br>";
        echo "L'article est bien supprimé";
    }
}
