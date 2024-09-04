<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("location: login.php");
        exit;
    }
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "users19";
    $conn = mysqli_connect($server, $username, $password, $database);
    $user = $_SESSION['username'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $oldTitle = $_POST['titleEditOld'];
        $oldDescription = $_POST['descriptionEditOld'];
        $newTitle = $_POST['titleEdit'];
        $newDescription = $_POST['descriptionEdit'];
        $sql = "UPDATE `$user` SET `title`='$newTitle',`description`='$newDescription' WHERE `$users`.`title` = '$oldTitle'";
        $result = mysqli_query($conn, $sql); 
    }
    header("location: welcome.php");
?>