<section class="hero is-primary">
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">
        Fiche médecin <?= ucfirst($dataM['nom']) ." ". ucfirst($dataM['prenom']) ;?>
      </h1>
      <h2 class="subtitle">
        Ce médecin ne vous convient pas ?
        <a class="button is-link is-inverted is-outlined is-small" href="javascript:history.go(-1)" >
        	<strong>Retourner à la liste</strong>
        </a>
      </h2>
    </div>
  </div>
</section>


<div class="columns">
  <div class="column"></div>
  <div class="column is-four-fifths">
    <div class="card">
  <div class="card-content">
    <div class="media">
      <div class="media-left">
        <figure class="image is-128x128">
          <img src="https://bulma.io/images/placeholders/256x256.png" alt="Placeholder image">
        </figure>
      </div>
      <div class="media-content">
        <h3 class="title level-item has-text-centered">
               Nom du cabinet: <?= ucfirst($dataM['nomcabinet']) ;?>
              </h3>
              <h5 class="subtitle level-item has-text-centered">
                Nom du médecin: <?= ucfirst($dataM['nom']) ." ". ucfirst($dataM['prenom']) ;?>
              </h5>
        <ul>
          <li><strong>Adresse: </strong><?= $dataM['adresse'];?></li>
          <li><strong>Code postal: </strong><?= $dataM['codepostal']." ". ucfirst($dataM['ville']) ;?></li>
          <li><strong>Spécialité: </strong><?= ucfirst($dataM['categorie']);?></li>
        </ul>
      </div>
    </div>

    <div class="content">
      ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
      consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
      cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
      proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </div>
  </div>
</div>
  </div>
  <div class="column"></div>
</div>

<div class="columns">
  <div class="column">
    
  </div>
  <div class="column">
  <?php 
    $m = '5';
    $y = '2020';
    calendar($m, $y, $idMedecinP);
  ?> 
  </div>
  <div class="column">
    
  </div>
</div>







