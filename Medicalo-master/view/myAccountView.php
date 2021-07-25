<section class="hero is-primary">
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">
        Bienvenue venu dans votre espace personnel !
      </h1>
      <h2 class="subtitle">
        Vous pouvez modifer vos données ou visionner vos reservations
      </h2>
    </div>
  </div>
</section>

<div class="container is-widescreen">
<div class="columns">
  <div class="column is-half ">
    <p class="title is-3">Mes données personnelles</p>
    <?php require('view/editDataView.php'); ?> <!-- fragment mes donnes personnelles -->
  </div>
  <div class="column is-half">
    <p class="title is-3">Mes réservations médicales</p>
    is-half  <!-- fragment mes reservations -->
  </div>
</div>
</div>
