<?php 
    session_start();

    if(!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $id = $_SESSION['user_id'];
    $sql = "SELECT * FROM user WHERE id = ?";
?>