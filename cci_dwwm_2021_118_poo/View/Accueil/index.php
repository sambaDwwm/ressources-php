<ul>

    <?php
    foreach ($listeProduit as $produit) {
    ?>

        <div class="card text-white bg-primary mb-3" style="max-width: 20rem;">
            <div class="card-header"><?php echo $produit->getPrix() ?>€</div>
            <div class="card-body">
                <h4 class="card-title"><?php echo $produit->getNom() ?></h4>
                <p class="card-text"><?php echo $produit->getDescription() ?></p>
            </div>
            <button type="button" class="btn btn-success">Ajouter au panier</button>
            <button type="button" class="btn btn-warning">Modifier</button>
        </div>

    <?php
    }
    ?>

</ul>