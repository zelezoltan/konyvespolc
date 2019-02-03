<?php
session_start();
include('includes/auth.php');
auth(USER_ONLY);
unset($_SESSION['user']);
header('Location: index.php');