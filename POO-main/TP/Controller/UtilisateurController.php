<?php

namespace Controller;

use DAO\UtilisateurDao;

class UtilisateurController extends BaseController
{

    public function connexion()
    {
        $dao = new UtilisateurDao();

        $utilisateur = $dao->findByPseudo($_POST["pseudo"]);

        if (isset($_POST["pseudo"])) {
            if (password_verify($_POST["password"], $utilisateur->getMotDePasse())) {
                $_SESSION["utilisateur"] = serialize($utilisateur);
                header("Location: /TP_POO_PHP/POO/TP");
            } else {
                echo "mauvais mot de passe";
            }
        } else {
            $this->afficherVue("login");
        }
    }

    public function deconnexion()
    {
        session_unset();
        session_destroy();
        header("Location: /TP_POO_PHP/POO/TP");
    }

    public function inscription()
    {
        $this->afficherVue("inscription");
    }
}
