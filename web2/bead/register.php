<?php 

session_start();
include('includes/auth.php');
auth(GUEST_ONLY);

$dbConn = new PDO('mysql:dbname=wf2_fnd1qy;host=localhost', 'fnd1qy', 'fnd1qy');


$errors = [];
if(count($_POST) > 0){
    if($_POST['fullname'] == ''){
        $errors['fullnameError'] = 'A teljes név nem lehet üres!';
    }
    if($_POST['password'] == ''){
        $errors['passwordError'] = 'A jelszó megadása kötelező!';
    }else if(strlen($_POST['password']) < 6){
        $errors['passwordError'] = 'A jelszónak legalább 6 karakter hosszúnak kell lennie!';
    }
    if($_POST['email'] == '' || filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === FALSE){
        $errors['emailError'] = 'Az E-mail cím rossz formátumú!';
    }else{
        $stmt = $dbConn->prepare('SELECT * FROM `bead_users` WHERE `email` = :e');
        $stmt->execute([
            ':e' => $_POST['email']
        ]);
        if($stmt->rowCount() !== 0){
            $errors['emailError'] = 'Az adott E-mail cím már foglalt';
        }
    }

    if(count($errors) === 0){
        $stmt = $dbConn->prepare('INSERT INTO `bead_users` (`fullname`,`email`,`passwd`) VALUES (:f, :e, :p)');
        $stmt->execute([
            ':f' => $_POST['fullname'],
            ':e' => $_POST['email'],
            ':p' => password_hash($_POST['password'], PASSWORD_DEFAULT)
        ]);
        header('Location: index.php');
        exit;
    }
    
}

?>

<?php include('includes/header.php');?>

<div class="container">
    
    <div class="row">
        <div class="col-sm-10 col-md-8 col-lg-6">
            <form method="post">
            <h1>Regisztráció</h1>
                 <div class="form-group">
                    <label for="fullname">Teljes név</label>
                    <input type="text" value="<?= $_POST['fullname'] ?? ''?>" name="fullname" class="form-control <?php if(isset($errors['fullnameError'])) echo 'is-invalid' ?>" id="fullname" placeholder="Teljes név" required>
                    <div class="invalid-feedback">
                        <?= $errors['fullnameError']?>
                    </div>  
                </div>
                <div class="form-group">
                    <label for="email">E-mail cím</label>
                    <input type="email" value="<?= $_POST['email'] ?? ''?>" name="email" class="form-control <?php if(isset($errors['emailError'])) echo 'is-invalid' ?>" id="email" placeholder="E-mail cím" required>
                    <div class="invalid-feedback">
                        <?= $errors['emailError'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Jelszó</label>
                    <input type="password" value="<?= $_POST['password'] ?? ''?>" name="password" class="form-control <?php if(isset($errors['passwordError'])) echo 'is-invalid' ?>" id="password" placeholder="Jelszó" required>
                    <div class="invalid-feedback">
                        <?= $errors['passwordError'] ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Küldés</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>