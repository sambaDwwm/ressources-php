<?php
require_once("./controller/UtilisateurController.php");

$partieUrl = explode("/",$_GET["page"]);
$partieController= $partieUrl[0];
$partieMethode = $partieUrl[1];



$nomController = "\\controller\\" . ucfirst($_GET["page"]) . "Controller";


// "UtilisateuControleur"

$controller = new $nomController();


// $nomMethode = "afficheUtilisateur";

$controller->$partieMethode();


// 


// $controller->afficheListeUtilisateur();

// $controller->$nomMethode();
