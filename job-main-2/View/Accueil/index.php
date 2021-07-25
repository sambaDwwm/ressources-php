

<ul>

    <?php
    foreach ($listOffre as $Offre) {
    ?>
        <div>
            
            <div>
                <h4 ><?php echo $offre->getTitre() ?></h4>
                <p ><?php echo $offre->getDescription() ?></p>

            </div>
            <button>Ajouter au panier</button>

            <?php


            if (isset($_SESSION["utilisateur"])) {
                //serialize (transforme un text en objet) une serialize (transforme un objet en text)
                $utilisateur = unserialize($_SESSION["utilisateur"]);
                if ($utilisateur->getIsAdmin()) {
            ?>
                    <button type="button">Modifier</button>
            <?php
                }
            }
            ?>
            <?php
            if (isset($_SESSION["utilisateur"])) {
                $utilisateur = unserialize($_SESSION["utilisateur"]);
                if ($utilisateur->getIsAdmin()) {
                    if ($utilisateur->getDeleteId()) {


            ?>

                        <a type="button" href="/job/utilisateur/offre">Suprimer</a>
            <?php
                    }
                }
            } ?>



        </div>
    <?php
    }

    ?>
</ul>