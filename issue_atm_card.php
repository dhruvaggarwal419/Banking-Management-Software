<?php
session_start();
if (!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] != true) {
    header("location: login.php");
    exit;
}
$user = $_SESSION['username'];
$server = "localhost";
$username = "root";
$password = "";
$database = "users19";
$conn = mysqli_connect($server, $username, $password, $database);
$existsSql = "SELECT isDebitCard FROM users WHERE username = '$user'";
$result = mysqli_query($conn, $existsSql);
$row = mysqli_fetch_assoc($result);
if ($row['isDebitCard'] == 0) {
    $updateSql = "UPDATE users SET isDebitCard = 1 WHERE username = '$user'";
    mysqli_query($conn, $updateSql)

    header("Location: generate_atm_card.php");
    exit;
} else {
    header("Location: generate_atm_card.php");
    exit;
}
?>