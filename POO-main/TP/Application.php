<?php

class Application
{

    public static function demarrer()
    {
        $partiesUrl = explode("/", $_GET["page"]);

        if (count($partiesUrl) > 0 && $partiesUrl[0] != "") {
            $partieUrlController = $partiesUrl[0];
        } else {
            $partieUrlController = "accueil";
        }

        if (count($partiesUrl) > 1 && $partiesUrl[1] != "") {
            $partieUrlMethode = $partiesUrl[1];
        } else {
            $partieUrlMethode = "index";
        }

        $nomController = "Controller\\" . ucfirst($partieUrlController) . "Controller";

        if (!method_exists($nomController, $partieUrlMethode)) {
            $nomController = "Controller\AccueilController";
            $partieUrlMethode = "nonTrouve";
        }

        $parametres = array_slice($partiesUrl, 2);

        $controller = new $nomController();
        $controller->$partieUrlMethode($parametres);
    }

}
