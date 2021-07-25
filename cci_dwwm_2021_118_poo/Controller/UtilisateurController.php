<?php

namespace Controller;

use DAO\UtilisateurDao;

class UtilisateurController extends BaseController
{
    public function index()
    {
        $this->afficherVue("login");
    }

    public function connexion()
    {
        $dao = new UtilisateurDao();

        //note : utilisez l'instruction ci-dessous pour générer un mot de passe
        //echo password_hash("root", PASSWORD_BCRYPT);

        $utilisateur = $dao->findByPseudo($_POST["pseudo"]);

        var_dump($utilisateur);

        if (password_verify($_POST["motDePasse"], $utilisateur->getMotDePasse())) {

            //pour eviter les problème de stockage dans la session, 
            //on transforme l'objet en texte (on le deserialisera par la suite)
            $_SESSION["utilisateur"] = serialize($utilisateur);

            //on redirige vers l'accueil
            header("Location: /cci_dwwm_2021_118_poo");
        } else {

            echo "mauvais mot de passe";
        }
    }
}
