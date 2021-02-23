<!doctype html>
<html lang="cs">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Spectral:ital,wght@0,400;0,700;1,600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="assets/vendors/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">

    <title>E-library</title>
  </head>   
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="http://localhost/">E-library</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <?php if(!isset($_SESSION["identity"])) : ?>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="registrace.php">Registrace</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php#login">Přihlášení</a>
            </li>          
            <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="knihovna.php">Knihovna</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="profil.php?id=<?php echo $_SESSION["identity"]["id"]; ?>"><?php echo $_SESSION["identity"]["username"]; ?></a>
            </li>          
            <?php endif; ?>
        </div>
      </div>
    </nav>
    <?php if(isset($_SESSION["flash"])) : ?>
    <section class="alerts pt-5">
        <div class="container">
        <?php foreach($_SESSION["flash"] as $flash) : ?>
        <p class="alert alert-<?php echo $flash["type"]; ?>"><?php echo $flash["content"]; ?></p>
        <?php 
            endforeach; 
            unset($_SESSION["flash"]);
        ?>
        </div>
    </section>
    <?php endif; ?>