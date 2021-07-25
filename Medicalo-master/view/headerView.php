<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medicalo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  </head>
  <body>
<nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="index.php">
      <img src="https://bulma.io/images/bulma-logo.png" alt="Bulma: Free, open source, and modern CSS framework based on Flexbox" width="112" height="28">
    </a>

  </div>

<div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <?php
          if (isset($_SESSION['user'])) 
          {
          ?>
            <a class="button is-primary" href="index.php?action=myAccount">
              <strong>Mon Compte</strong>
            </a>
            <a class="button is-light" href="index.php?action=disconnect">
              Déconnexion
            </a>
          <?php
          }
          else
          {
          ?>
            <a class="button is-primary" href="index.php?action=vRegist">
              <strong>S'inscrire</strong>
            </a>
            <a class="button is-light" href="index.php?action=vlogin">
              Connexion
            </a>
          <?php
          }
          ?>
        </div>
      </div>
</div>

</nav>


