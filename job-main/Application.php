<?php



class Application
{

    public static function demarrer()
    { 
       

        //l'utilisateur saisie l'url :
        //--> localhost/.../nomController/nomMethode
        //via la réecriture d'URL devient :
        //-->localhost/.../index.php?page=nomController/nomMethode
        
        // explode permet de diviser l'url en plusieur partis 
        // permiere partie : accueil , second partie: index
        $partiesUrl = explode("/", $_GET["page"]);

        // Si ya rien ou vide ; prend le controller par defaut :  ex: accueil
        if (count($partiesUrl) > 0 && $partiesUrl[0] != "") {
            $partieUrlController = $partiesUrl[0];
        } else {
            $partieUrlController = "accueil";
        }
// s'il n'est pas vide  , dans ce cas , prend methode par defaut :  index
        if (count($partiesUrl) > 1 && $partiesUrl[1] != "") {
            $partieUrlMethode = $partiesUrl[1];
        } else {
            $partieUrlMethode = "index";
        }

        //ucfist permet de mettre la première lettre en Majuscule
        //dans $nomController il y a par exemple : "Controller\AccueilController"
        $nomController = "Controller\\" . ucfirst($partieUrlController) . "Controller";

        //si le controlleur ou la méthode n'existe pas, on redirige vers une page 404
        if (!method_exists($nomController, $partieUrlMethode)) {

            $nomController = "Controller\AccueilController";
            $partieUrlMethode = "nonTrouve";

            //http_response_code(404);
            //die();
            //ou 
            //$nomController = "Controller\AccueilController"
            //$nomMethode = "index";
            //ou
            //afficher une page avec les article en top des vente ... etc
        }

        //on recupère les potentiel paramètres
        //ex localhost/.../panier/supprimerArticle/42
        //42 serait à la position 2 du tableau $partiesUrl
        //(mais il peut y en avoir plus)
        $parametres = array_slice($partiesUrl, 2);

        //PHP regarde le texte à l'intérieur de la variable $nomController,
        //il créait un nouvelle objet se basant sur le nom de la classe que contient $nomController (ex : "Controller\AccueilController")
        $controller = new $nomController();

        $controller->$partieUrlMethode($parametres);
    }
}
