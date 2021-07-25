<?php

session_start();

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
    <link rel="stylesheet" href="https://bootswatch.com/5/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="/TP_POO_PHP/POO/TP/assets/css/style.css">
    <title>Paul Emploi</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/TP_POO_PHP/POO/TP">Paul Emploi</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarColor02">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Offres</a>
                        </li>

                       
                        <li class="nav-item">
                            <a class="nav-link" href="#">Ajouter offre</a>
                        </li>
                        <li class="nav-item">

                            

                                <a class="nav-link" href="/TP_POO_PHP/POO/TP/utilisateur/deconnexion">Déconnexion</a>
                        </li>

                  

                        <li class="nav-item">
                            <a class="nav-link" href="/TP_POO_PHP/POO/TP/utilisateur/connexion">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/TP_POO_PHP/POO/TP/utilisateur/inscription">Inscription</a>
                        </li>
                    
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <?php

    Application::demarrer();

    ?>

</body>

</html>