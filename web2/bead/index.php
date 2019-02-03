<?php

session_start();
include('includes/auth.php');
auth(ANYBODY);
$dbConn = new PDO('mysql:dbname=wf2_fnd1qy;host=localhost', 'fnd1qy', 'fnd1qy');


$errors = [];
if(count($_POST) > 0){
    
    $stmt = $dbConn->prepare('SELECT * FROM `bead_users` WHERE `email` = :e');
    $stmt->execute([
        ':e' => $_POST['email']
    ]);

    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(count($user) === 0 || !password_verify($_POST['password'], $user[0]['passwd'])){
        $errors[] = 'Hibás E-mail cím vagy jelszó!';
    }else{
        $_SESSION['user'] = $user[0];
        header('Location: list.php');
        exit;
    }
}

$stmt = $dbConn->prepare('SELECT count(1) FROM `bead_users`');
$stmt->execute();
$userCount = $stmt->fetchColumn();

$stmt = $dbConn->prepare('SELECT count(1) FROM `bead_books`');
$stmt->execute();
$bookCount = $stmt->fetchColumn();
?>

<?php 
include('includes/header.php');
?>

<div class="jumbotron">
    <div class="container">
        <h1>Üdvözöllek az oldalon!</h1>
        <h4>Jelenleg <?= $userCount ?> felhasználónak <?= $bookCount ?> könyve van az alkalmazásban.</h4>
    </div>
    
</div>

<div class="container">
    <?php if(!isset($_SESSION['user'])) : ?>
    <h2>Bejelentkezés</h2>
    <ul>
        <?php foreach($errors as $error): ?>
            <li style="color:red"><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
    
    <div class="row">
        <div class="col-sm-10 col-md-8 col-lg-6">
            <form method="post">
                <div class="form-group">
                    <label for="email">E-mail cím</label>
                    <input type="email" value="<?= $_POST['email'] ?? '' ?>" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Add meg az e-mail címed">
                </div>
                <div class="form-group">
                    <label for="password">Jelszó</label>
                    <input type="password" value="<?= $_POST['password'] ?? '' ?>" name="password" class="form-control" id="password" placeholder="Jelszó">
                </div>
                <button type="submit" class="btn btn-primary">Küldés</button>
            </form>
        </div>
    </div>
    <?php else: ?>

    <h2><?= $_SESSION['user']['fullname']?></h2>
    <p>Tekintsd meg a könyveidet itt: <a href="list.php">Könyvek</a></p>

    <?php endif; ?>
</div>


<?php
include('includes/footer.php');
?>