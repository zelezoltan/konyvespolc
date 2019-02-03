<?php 

session_start();
include('includes/auth.php');
auth(USER_ONLY);

if(!isset($_GET['bookId'])){
    header('Location: list.php');
    exit;
}

$dbConn = $dbConn = new PDO('mysql:dbname=wf2_fnd1qy;host=localhost', 'fnd1qy', 'fnd1qy');
$stmt = $dbConn->prepare('SELECT * FROM `bead_books` WHERE `id` = :i AND `userID` = :id');
$stmt->execute([
    ':i' => $_GET['bookId'],
    ':id' => $_SESSION['user']['id']
]);
$book = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
if(!$book){
    header('Location: list.php');
    exit;
}

$errors = [];
if(count($_POST)>0){

    if($_POST['author'] == ''){
        $errors['authorError'] = 'A szerző nem lehet üres!';
    }
    if($_POST['title'] == ''){
        $errors['titleError'] = 'A cím nem lehet üres!';
    }
    if($_POST['pagenum'] != '' && filter_var($_POST['pagenum'], FILTER_VALIDATE_INT) === FALSE){
        $errors['pagenumError'] = 'Az oldalszám csak egész szám lehet!';
    }
    if($_POST['isbn'] == '' || (strlen($_POST['isbn'])!=10 && strlen($_POST['isbn'])!=13) || filter_var($_POST['isbn'], FILTER_VALIDATE_INT) === FALSE){
        $errors['isbnError'] = 'Az ISBN számnak 10 vagy 13 hosszú számsornak kell lennie!';
    }
    if($_POST['category'] == ''){
        $errors['categoryError'] = 'A kategória megadása kötelező!';
    }

    if(count($errors) === 0){
        $stmt = $dbConn->prepare('UPDATE `bead_books` SET `author` = :a,`title` = :t,`pagenum` = :p,`category` = :c,`isbn` = :i,' . 
        '`isread` = :r WHERE id = :u');
        $stmt->execute([
            ':a' => $_POST['author'],
            ':t' => $_POST['title'],
            ':p' => $_POST['pagenum'],
            ':c' => $_POST['category'],
            ':i' => $_POST['isbn'],
            ':r' => (isset($_POST['isread']) ? true : false ),
            ':u' => $book['id']
        ]);

        header('Location: list.php');
        exit;
    }

}

?>

<?php include('includes/header.php');?>

<div class="container">
    
    <div class="row">
        <div class="col-sm-10 col-md-8 col-lg-6">
            <form method="post">
            <h1><?= $book['title'] ?> könyv módosítása</h1>
                 <div class="form-group">
                    <label for="author">Szerző</label>
                    <input type="text" value="<?= $_POST['author'] ?? $book['author']?>" name="author" class="form-control <?php if(isset($errors['authorError'])) echo 'is-invalid' ?>" id="author" placeholder="Szerző" required>
                    <div class="invalid-feedback">
                        <?= $errors['authorError']?>
                    </div>  
                </div>
                <div class="form-group">
                    <label for="title">Cím</label>
                    <input type="text" value="<?= $_POST['title'] ?? $book['title']?>" name="title" class="form-control <?php if(isset($errors['titleError'])) echo 'is-invalid' ?>" id="title" placeholder="Cím" required>
                    <div class="invalid-feedback">
                        <?= $errors['titleError'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pagenum">Oldalszám</label>
                    <input type="number" value="<?= $_POST['pagenum'] ?? $book['pagenum']?>" name="pagenum" class="form-control <?php if(isset($errors['pagenumError'])) echo 'is-invalid' ?>" id="pagenum" placeholder="Oldalszám">
                    <div class="invalid-feedback">
                        <?= $errors['pagenumError']?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="category">Kategória</label>
                    <input list="categories" name="category" value="<?= $_POST['category'] ?? $book['category']?>" class="form-control <?php if(isset($errors['categoryError'])) echo 'is-invalid' ?>" id="category" placeholder="Kategória">
                    <datalist id="categories">
                        <option value="Regény">
                        <option value="Sci-fi">
                        <option value="Dráma">
                        <option value="Horror">
                    </datalist> 
                    <div class="invalid-feedback">
                        <?= $errors['categoryError'] ?>
                    </div> 
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN szám</label>
                    <input type="number" value="<?= $_POST['isbn'] ?? $book['isbn']?>" name="isbn" class="form-control <?php if(isset($errors['isbnError'])) echo 'is-invalid' ?>" id="isbn" placeholder="ISBN" required>
                    <div class="invalid-feedback">
                        <?= $errors['isbnError']?>
                    </div>  
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" <?= (count($_POST)>0 ? (isset($_POST['isread']) ? 'checked' : '') : ($book['isread'] == 1 ? 'checked' : '')) ?> name="isread" class="form-check-input" id="isread"> 
                    <label class="form-check-label" for="isread">Elolvasva-e</label> 
                </div>
                
                <button type="submit" class="btn btn-primary">Módosít</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>