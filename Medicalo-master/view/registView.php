<section class="hero">
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">
        Nouveau sur Medicalo ?
      </h1>
      <h2 class="subtitle">
       Inscrivez-vous
      </h2>
    </div>
  </div>
</section>

<div class="container">
  <div class="column is-half is-offset-one-quarter">
    <form method="post" action="index.php?action=regist">

      <div class="field">
        <label class="label">Nom</label>
        <div class="control">
          <input class="input" type="text" name="nom" placeholder="Votre nom" maxlength="30" required >
        </div>
      </div>

      <div class="field">
        <label class="label">Prenom</label>
        <div class="control">
          <input class="input" type="text" name="prenom" placeholder="Votre prenom" maxlength="30" required>
        </div>
      </div>

      <div class="field">
        <label class="label">Numéro de téléphone</label>
        <div class="control">
          <input class="input" type="text" name="numero" placeholder="Votre numéro de téléphone (sinon fixe)" maxlength="10" required>
        </div>
      </div>

      <div class="field">
        <label class="label">Email</label>
        <div class="control">
          <input class="input" type="email" name="email" placeholder="votreMail@gmail.com" required>
        </div>
      </div>
      
      <div class="field">
        <label class="label">Mot de passe</label>
        <p class="control has-icons-left">
          <input class="input" name="password" type="password" placeholder="Mot de passe" required maxlength="25">
          <span class="icon is-small is-left">
          <i class="fas fa-lock"></i>
          </span>
        </p>
      </div>

    <div class="field">
        <label class="label">Confirmez le mot de passe</label>
        <p class="control has-icons-left">
          <input class="input" name="passwordC" type="password" placeholder="Confirmation du mot de passe" required maxlength="25">
          <span class="icon is-small is-left">
          <i class="fas fa-lock"></i>
          </span>
        </p>
    </div>

    <div class="field level-right">
      <p class="control">
        <button class="button is-primary">
        S'inscrire
        </button>
      </p>
    </div>

    </form>
  </div>
</div>