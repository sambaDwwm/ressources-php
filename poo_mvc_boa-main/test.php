<?php
require_once('./model/Utilisateur.php');

use model\Utilisateur;

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
    $listeUtilisateurObjet[] = $utlisateur;
}



foreach($listeUtilisateurObjet as $utilisateur){
    echo $utilisateur->nomComplet();
}
// $utilisateur = new Utilisateur();
// $utilisateur->setNom($utilisateurBdd['nom']);
// $utilisateur->setPrenom($utilisateurBdd['prenom']);
// $utilisateur->setAge($utilisateurBdd['age']);

// $listeUtilisateurObjet[] = $utilisateur;

?>


