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
$existsSql = "SELECT * FROM `users` WHERE `username` = '$user'";
$result = mysqli_query($conn, $existsSql);
$row = mysqli_fetch_assoc($result);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Your ATM Card</title>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .atm-card {
            width: 400px;
            height: 250px;
            border-radius: 15px;
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            position: relative;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px 
        }
        .atm-card h3 {
            margin: 0;
            font-size: 24px;
        }
        .atm-card .card-number {
            margin-top: 20px;
            font-size: 18px;
            letter-spacing: 3px;
        }
        .atm-card .holder-name {
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .atm-card .expiry-date {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 16px;
        }
        .buttons-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        function printCard() {
            document.querySelector('.print-btn').classList.add('hidden');
            document.querySelector('.back-btn').classList.add('hidden');
            window.print();
            window.onafterprint = function() {
                document.querySelector('.print-btn').classList.remove('hidden');
                document.querySelector('.back-btn').classList.remove('hidden');
            };
            setTimeout(function() {
                document.querySelector('.print-btn').classList.remove('hidden');
                document.querySelector('.back-btn').classList.remove('hidden');
            }, 100);
        }
    </script>
</head>
<body>
    <h1 style='text-align : center;'>ATM Card - Dehradun Bank</h1>
    <div class="atm-card">
        <h3><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></h3>
        <p class="card-number"><?php echo htmlspecialchars($row['acc_num']); ?></p>
        <p>Mother's Name : <strong><?php echo htmlspecialchars($row['mother_n']); ?></strong></p>
        <p>Father's Name : <strong><?php echo htmlspecialchars($row['father_n']); ?></strong></p>
        <p>Address : <?php echo htmlspecialchars($row['address']); ?></p>
        <p class="holder-name">Dehradun Bank</p>
        <p class="expiry-date">Valid Till: <?php 
        $date = new DateTime($row['date']);
        $date->modify('+4 years');
        $expiryDate = $date->format('Y-m-d');
        echo htmlspecialchars($expiryDate);
         ?>
        </p>
    </div>
    <div class="buttons-container">
        <button class="btn print-btn" onclick="printCard()">Print as PDF</button>
        <a href="/dhruv/welcome.php"><button class="btn back-btn">Back to Page</button></a>
    </div>
</body>
</html>