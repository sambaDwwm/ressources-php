<?php

namespace Controller;

use DAO\UtilisateurDao;

class UtilisateurController extends BaseController
{


    public function index()
    {
        $this->afficherVue("login");
    }
    public function  connexion()
    {
        $dao = new UtilisateurDao();


        // note:  utilisez l'instruction ci-dessous pour geneer un mt passe 
        // echo password_hash("root",PASSWOR8BCRYPT)

        echo password_hash("root", PASSWORD_BCRYPT);

        $utilisateur = $dao->findByPseudo($_POST["pseudo"]);

        if (password_verify($_POST["mot_de_passe"], $utilisateur->getMotDePasse())) {
            // pour evte les problemes dans lea session
            // on transforme  l'objet en texte (on ele deserialisera par la suite)
            $_SESSION["utilisateur"] = serialize($utilisateur);
            // on dirige vers l'accueil
            header("Location: /job");
        } else {
            echo "mauvais mot de passe";
        }
    }
    public function  Inscription()
    {
        
       $this->afficherVue("inscription");
    }
    public function deconnexion()
    {
        if (isset($_SESSION["utilisateur"])) {
            session_destroy();
        }
        header('location: /job');
    }
}
