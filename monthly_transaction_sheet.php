<?php
session_start();
if (!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] != true) {
    header("location: login.php");
    exit;
}
$server = "localhost";
$username = "root";
$password = "";
$database = "users19";
$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$user = $_SESSION['username'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <title>Monthly Transactions</title>
</head>
<body>
    <?php require 'nav.php'; ?>
    <div class="container my-4">
        <h2 class="text-center">Monthly Transactions</h2>
        <h4 class="text-center"><?php echo htmlspecialchars($user); ?></h4>
        <?php
        $query = "SELECT * FROM `$user` WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()) ORDER BY date DESC";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            echo "<table class='table table-bordered'>";
            echo "<thead><tr><th>Date</th><th>Transaction</th><th>Message</th></tr></thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['transaction']) . "</td>";
                echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='text-center'>No transactions found for this month.</p>";
        }
        ?>
        <div class="text-center mt-4">
            <a href="welcome.php" class="btn btn-primary">Back to Welcome Page</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>
</body>
</html>