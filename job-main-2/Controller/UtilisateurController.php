<?php
namespace Controller;


use DAO\UtilisateurDao;
use Model\Utilisateur;

class UtilisateurController extends BaseController
{


    public function index()
    {
        $this->afficherVue("login");
    }
    public function  connexion()
    {

        if (isset($_POST["pseudo"])) {


            $dao = new UtilisateurDao();


            // note:  utilisez l'instruction ci-dessous pour geneer un mt passe 
            // echo password_hash("root",PASSWOR8BCRYPT)

            echo password_hash("root", PASSWORD_BCRYPT);

            $utilisateur = $dao->findByPseudo($_POST["pseudo"]);

            if (
                $utilisateur && password_verify($_POST["mot_de_passe"], $utilisateur->getMotDePasse())
            ) {
                // pour evte les problemes dans lea session
                // on transforme  l'objet en texte (on ele deserialisera par la suite)
                $_SESSION["utilisateur"] = serialize($utilisateur);
                // on dirige vers l'accueil
                header("Location: /job");
            } else {
                echo "mauvais mot de passe";
            }
        }

        $this->afficherVue("login");
    }
    public function  inscription()
    {
        if (isset($_POST['pseudo']))
         {
        
            $utilisateur = new Utilisateur();

            $sql = 'INSERT INTO utilisateur(pseudo , mot_de_passe) VALUE(?,?)';
           $utilisateur= $sql->prepare($utilisateur)->execute([$pseudo , $mot_de_passe]);
           
        

        }
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
