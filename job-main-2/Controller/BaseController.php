<?php

namespace Controller;

class BaseController
{
    // note :  on utilise les methode compact et extract pour transfere plusieur variable
    // a la vue : les variable sont ajoutee dan un tableau "$donnees" dans le controller
    // est sont de nouveau affectee àdes variable dans cette méthode
     
    
    public function afficherVue($fichier = "index"  , $donnees = [])
    {
        // estract let les index du ttableau et cree une variable du meme nom et y affect la valeur associees.
        // ex :  si le tableau est le suivant ["listeA"  =>["a","b","c"] , "autreIndes" =>42]
        // il creera une variable $listeA contenant ["a", "b" , "c"]
        // et une autre variable $autreIndrex contenant 42 

        extract($donnees);

        //si la classe s'appelle Controller\AccueilController
        //on enlève les 11 caractères de "Controller\" et les 10
        //caractères de fin : "Controller"
        //On obtient la chaine "Accueil" dans $dossier
        $dossier =  substr(get_class($this), 11, -10);
        // pouquoi include passe ces des vues et les  vues sont de fichiers

        include("./View/" . $dossier . "/" . $fichier . ".php");
    }
}
