<?php

namespace Controller;

class PanierController extends BaseController
{

    //url : localhost/.../panier/index ou localhost/.../panier
    public function index()
    {
        $this->afficherVue();
    }

    public function supprimerArticle($parametres)
    {
        echo "Suppression de l'article avec l'id ";
        echo $parametres[0] . "<br>";
        echo "L'article est bien supprimé";
    }
}
