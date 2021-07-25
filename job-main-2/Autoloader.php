<?php

namespace App;

class Autoloader
{
    static function register()
    {
        spl_autoload_register([
            __CLASS__,
            'autoload'
        ]);
    }

// cette methode sera a chaque fois que l'on utilise le mot cle use ou lorsqu'on cree un objet avec le mot clé  NEW suivi d'un nom de namespace (ex :  new Model\Utilisateur())
//  le but cette methode est de charger le fichier correspondant a la  class

//  emple:  via l'instruction use Model\Utilisateur;
// cette instruction sera appelée
// requre_on(./model/utilisateur.php)
// on retire app\ et on obtient: "Model\Utilisateur
    static function autoload($class)
    {
        // On récupère dans $class la totalité du namespace de la classe concernée (App\Client\Compte)
        // On retire App\ (Client\Compte)
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);

        // On remplace les \ par des /
        $class = str_replace('\\', '/', $class);

    // Note : __DIR__ contient l'arborecence du fichier autolaoder.php
        $fichier = __DIR__ . '/' . $class . '.php';
        // On vérifie si le fichier existe
        if (file_exists($fichier)) {
            require_once $fichier;
        }
    }
}
