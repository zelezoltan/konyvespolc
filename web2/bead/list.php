<?php 

session_start();
include('includes/auth.php');
auth(USER_ONLY);

$dbConn = $dbConn = new PDO('mysql:dbname=wf2_fnd1qy;host=localhost', 'fnd1qy', 'fnd1qy');

$page = 1;
if(isset($_GET['page']) && $_GET['page']>0){
    $page = $_GET['page'];
}

$stmt = $dbConn->prepare('SELECT * FROM `bead_books` WHERE `userID` = :u LIMIT 5 OFFSET :o');
$offset = ($page-1)*5;
$userId = $_SESSION['user']['id'];
$stmt->bindParam(":o", $offset, PDO::PARAM_INT);
$stmt->bindParam(":u", $userId);
$stmt->execute();

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $dbConn->prepare('SELECT count(1) FROM `bead_books` WHERE `userID` = :u');
$stmt->execute([
    ':u' => $userId
]);
$count = $stmt->fetchColumn();


?>

<?php include('includes/header.php'); ?>

<div class="container">
    <h1><?= $_SESSION['user']['fullname'] ?> könyvei</h1>
    <table class="table" data-page="<?=$page?>" data-count="<?=$count?>">
    <thead>
        <tr>
        <th scope="col">Szerző</th>
        <th scope="col">Cím</th>
        <th scope="col">Kategória</th>
        <th scope="col">Elolvasva-e</th>
        <th scope="col">Akció</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($books as $book): ?>
        <tr data-id="<?= $book['id'] ?>">
            <td><?= $book['author']?></td>
            <td><?= $book['title']?></td>
            <td><?= $book['category']?></td>
            <td><?= $book['isread'] ? 'igen' : 'nem'?> </td>
            <td><a class="btn btn-info" data-id="<?= $book['id'] ?>"
             href="book.php?bookId=<?= $book['id'] ?>">Módosítás</a>
              <a class="btn btn-danger" data-id="<?= $book['id'] ?>"
               href="delete.php?id=<?=$book['id']?>&page=<?=$page?>">Törlés</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
        <?php if($page>1) : ?>
            <li class="page-item"><a class="page-link prev" href="#">Előző</a></li>
        <?php endif; ?>
        <?php if($page*5<$count) : ?>
            <li class="page-item"><a class="page-link next" href="#">Következő</a></li>
        <?php endif; ?>
        </ul>
    </nav>
    <a class="btn btn-primary" href="addbook.php">Új könyv hozzáadása</a>
</div>

<script src="script.js"></script>
<?php include('includes/footer.php'); ?>