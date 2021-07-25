<section class="hero is-primary">
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">
        Les médecins disponibles selon vos filtres
      </h1>
      <h2 class="subtitle">
        Cela ne vous convient pas ?
        <a class="button is-link is-inverted is-outlined is-small" href="index.php" >
        	<strong>Retourner au filtre</strong>
        </a>
      </h2>
    </div>
  </div>
</section>

<nav class="breadcrumb is-centered has-arrow-separator" aria-label="breadcrumbs">
  <ul>
    <li><strong>Filtre: </strong></li>
    <li><?=ucfirst($valueMedecin);?></li>
    <li><?=ucfirst($valueVille);?></li>
  </ul>
</nav>

<div class="columns is-multiline is-mobile">
<?php
while ($data = $dataDoctor->fetch())
	{
?>
	
	<div class="column is-one-third">
		<a href="index.php?action=ficheMedecin&idMedecin=<?=$data['id'];?>">
   		<div class="box">
  		<article class="media">
    		<div class="media-left">
      		<figure class="image is-64x64">
     		   	<img src="https://bulma.io/images/placeholders/128x128.png" alt="Image">
      		</figure>
    		</div>
    		<div class="media-content">
      			<div class="content">
      				<h3 class="title level-item has-text-centered">
      					<?= ucfirst($data['nomcabinet']) ;?>
      				</h3>
      				<h5 class="subtitle level-item has-text-centered">
      					<?= ucfirst($data['nom']) ." ". ucfirst($data['prenom']) ;?>
      				</h5>
        		<div class="content">
        			<ul>
    					 <li><?= $data['adresse'];?></li>
    					 <li><?= $data['codepostal']." ". ucfirst($data['ville']) ;?></li>
    					 <li><?= ucfirst($data['categorie']);?></li>
  					 </ul>
        		</div>
      			</div>
    		</div>
  		</article>
		</div>
	</a>	
  	</div>
  	
<?php
	}
?>
</div>

