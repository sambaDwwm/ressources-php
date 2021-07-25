<section class="hero is-primary">
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">
        Un filtre une recherche un medecin
      </h1>
      <h2 class="subtitle">
        Pour vous prendre en charge
      </h2>
    </div>
  </div>
</section>

<div class="container">
  <div class="column is-two-thirds is-offset-one-quarter">
    <form method="post" action="index.php?action=listeMedecin">
      <div class="select is-medium">
        <select name="catMedecin">
          <option value="dermatologue">Dermatologue</option>
          <option value="generaliste">Généraliste</option>
          <option value="dentiste">Dentiste</option>
        </select>
      </div>
      <div class="select is-medium">
        <select name="catVille">
          <option value="paris">Paris</option>
          <option value="bordeaux">Bordeaux</option>
          <option value="lyon">Lyon</option>
          <option value="marseille">Marseille</option>
          <option value="toulouse">Toulouse</option>
          <option value="rennes">Rennes</option>
        </select>
      </div>
      <button class="button is-medium is-primary">
      Rechercher
      </button>  
    </form>
  </div>
</div>




