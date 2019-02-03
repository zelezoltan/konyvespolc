<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <title>Könyvespolc</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Könyvespolc</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Főoldal</a>
      </li>
    <?php if(!isset($_SESSION['user'])): ?>
      <li class="nav-item">
        <a class="nav-link" href="register.php">Regisztráció</a>
      </li>
    <?php endif; ?>
    <?php if(isset($_SESSION['user'])): ?>
      <li class="nav-item">
        <a class="nav-link" href="list.php">Könyvek</a>
      </li>
    <?php endif; ?>
    </ul>
    <?php if(isset($_SESSION['user'])): ?>
        <span>Bejelentkezve mint: <strong><?= $_SESSION['user']['fullname'] ?></strong></span>
        <a class="nav-link" href="logout.php">Kijelentkezés</a>
    <?php endif; ?>
  </div>
</nav>
