<?php
namespace controller;
use model\Utilisateur;
use PDO;

class UtilisateurController {


    public function afficheListeUtilisateur()
    {
       require_once("./view/listeUtilisateur.php");

    
require_once('./model/Utilisateur.php');



$connexion = new PDO('mysql:host=localhost:3306;dbname=poo_2021', 'root','');
$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$resultat = $connexion->query("SELECT * FROM utilisateur ");

$listeUtilisateurBdd = $resultat->fetchAll();
$listeUtilisateurObjet =[];

foreach ($listeUtilisateurObjet as $utilisateur){
    // echo $user['nom']. " ".$user['prenom'] .'<br>';
    $user = new Utilisateur(
        $utilisateurBdd['nom'],
        $utilisateurBdd['prenom'],
        $utilisateurBdd['age']
        

    );
    $listeUtilisateurObjet[] = $utilisateur;
}

    }

    // les details d'un utilsateur


    public function afficheFormulaire()
    {
        echo "affichage du formaulaire pour ajouter ";
    }


}