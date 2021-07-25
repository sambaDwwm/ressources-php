<?php
session_start();
// initialisation de l'autoLoader (il permet lors de l'utilisation du mot cle  use d'effectuer un include du fichier correspondant)
include("Autoloader.php");

use App\Autoloader;

Autoloader::register();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./asset/css/styles.css">
    <title>Offre emploi</title>

</head>

<body>
    <header>

        <nav>
            <div>
                <a href="/job/index.php">Emppol</a>
                <div class="navMenu">
                    <ul>
                        <li>
                            <a class="#" href="#">Offres</a>
                        </li>


                        <li>
                            <a class="#" href="#">Ajouter offre</a>
                        </li>
                        <li>
                            <a class="#" href="/job/utilisateur/deconnexion">Déconnexion</a>
                        </li>
                        <li>
                            <a class="#" href="/job/utilisateur/connexion">Connexion</a>
                        </li>
                        <li>
                            <a class="#" href="/job/utilisateur/inscription">Inscription</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

    </header>



    <?php



    //methode demarrer (static) 
    Application::demarrer();

    ?>
</body>

</html>