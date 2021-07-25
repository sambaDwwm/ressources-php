<form method="post" action="index.php?action=editUser">
<div class="field has-addons">
  <div class="control">
     <div>
    <label class="label">Nom</label>
  </div>
    <input class="input" name="data" type="text" maxlength="25" value="<?= ucfirst(htmlspecialchars($dataUser['nom']));?>">
    <input type="hidden" name="label" value="nom">
    <input type="hidden" name="idUser" value="<?= $dataUser['id'];?>">
  </div>
  <div class="control">
     <div><br></div>
    <button class="button is-primary">
      Modifier
    </button>
  </div>
</div>
</form>

<form method="post" action="index.php?action=editUser">
<div class="field has-addons">
  <div class="control">
     <div>
    <label class="label">Prenom</label>
  </div>
  <input class="input" name="data" type="text" maxlength="25" value="<?=ucfirst(htmlspecialchars($dataUser['prenom']));?>">
    <input type="hidden" name="label" value="prenom">
    <input type="hidden" name="idUser" value="<?= $dataUser['id'];?>">
  </div>
  <div class="control">
     <div><br></div>
    <button class="button is-primary">
      Modifier
    </button>
  </div>
</div>
</form>

<form method="post" action="index.php?action=editUser">
<div class="field has-addons">
  <div class="control">
     <div>
    <label class="label">N° de téléphone</label>
  </div>
    <input class="input" name="data" type="text" maxlength="10" value="<?= htmlspecialchars($dataUser['numero']);?>" >
    <input type="hidden" name="label" value="numero">
    <input type="hidden" name="idUser" value="<?= $dataUser['id'];?>">
  </div>
  <div class="control">
     <div><br></div>
    <button class="button is-primary">
      Modifier
    </button>
  </div>
</div>
</form>

<fieldset disabled>
<div class="field has-addons">
  <div class="control">
     <div>
    <label class="label">Email</label>
  </div>
    <input class="input" type="text" value="<?= htmlspecialchars($dataUser['email']);?>" >
  </div>
  <div class="control">
     <div><br></div>
    <button class="button is-primary">
      Modifier
    </button>
  </div>
</div>
</fieldset>

<br>

<div>
  <a class="button is-primary is-medium" href="index.php?action=editPassword">
    Modifier le mot de passe
  </a>
</div>
      

