<?php
session_start();
include('includes/auth.php');
auth(USER_ONLY);

$dbConn = $dbConn = new PDO('mysql:dbname=wf2_fnd1qy;host=localhost', 'fnd1qy', 'fnd1qy');

if(isset($_GET['page'])){
    $page = $_GET['page'];
    $stmt = $dbConn->prepare('SELECT * FROM `bead_books` WHERE `userID` = :u LIMIT 5 OFFSET :o');
    $offset = ($page-1)*5;
    $userId = $_SESSION['user']['id'];
    $stmt->bindParam(":o", $offset, PDO::PARAM_INT);
    $stmt->bindParam(":u", $userId);
    $stmt->execute();
    
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($books);

}else{
    header('HTTP/1.1 400 Bad Request');
}