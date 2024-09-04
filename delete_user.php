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
$conn = mysqli_connect($server, $username, $password, $database);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $sql = "DELETE FROM `users` WHERE `username` = '$username'";
    if (mysqli_query($conn, $sql)) {
        header("Location: welcomeAdministrator.php");
    } 
}
?>