<?php
    session_start();
    if (!isset($_SESSION['logged-in'])) {
        header("location: login.php");
        exit;
    }
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "users19";
    $user = $_SESSION['username'];
    $conn = mysqli_connect($server, $username, $password, $database);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $p_no = $_POST['p_no'];
        $father_n = $_POST['father_n'];
        $mother_n = $_POST['mother_n'];
        $address = $_POST['address'];
        $sql = "UPDATE `users` SET 
                `username`='$username', 
                `p_no`='$p_no', 
                `address`='$address' 
                WHERE `mother_n`='$mother_n'";
        if (mysqli_query($conn, $sql)) {
            header("location: welcomeAdministrator.php");
        }
    }
?>