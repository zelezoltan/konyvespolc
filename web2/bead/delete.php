<?php 

session_start();
include('includes/auth.php');
auth(USER_ONLY);

$dbConn = $dbConn = new PDO('mysql:dbname=wf2_fnd1qy;host=localhost', 'fnd1qy', 'fnd1qy');

if(count($_GET)>0){
    $stmt = $dbConn->prepare('DELETE FROM `bead_books` WHERE `id` = :i AND `userID` = :id');
    $stmt->execute([
        ':i' => $_GET['id'],
        ':id' => $_SESSION['user']['id']
    ]);
    header('Location: list.php?page=' . $_GET['page']);
    exit;
}else{
    header('Location: list.php?page=' . $_GET['page']);
    exit;
}