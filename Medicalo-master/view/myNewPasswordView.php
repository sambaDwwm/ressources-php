<section class="hero is-primary">
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">
        Votre mot de passe actuel ne vous plait pas ?
      </h1>
      <h2 class="subtitle">
        Modifiez le pour plus de sécurité et ne l'oubliez pas !
      </h2>
    </div>
  </div>
</section>

<div class="container">
  <div class="column is-half is-offset-one-quarter">
    <form method="post" action="index.php?action=newPswd">
    <div class="field">
        <label class="label">Nouveau mot de passe</label>
        <p class="control has-icons-left">
          <input class="input" name="newPswd" type="password" placeholder="Nouveau mot de passe" required maxlength="25">
          <span class="icon is-small is-left">
          <i class="fas fa-lock"></i>
          </span>
        </p>
      </div>

    <div class="field">
        <label class="label">Confirmez le nouveau mot de passe</label>
        <p class="control has-icons-left">
          <input class="input" name="confNewPswd" type="password" placeholder="Confirmation du mot de passe" required maxlength="25">
          <span class="icon is-small is-left">
          <i class="fas fa-lock"></i>
          </span>
        </p>
    </div>

    <div class="field level-right">
      <p class="control">
        <button class="button is-primary">
        Modifier
        </button>
      </p>
    </div>
    </form>
  </div>
</div>



      
