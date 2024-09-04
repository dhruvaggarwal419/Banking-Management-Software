<?php
    session_start();
    $login = false;
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "users19";
    $conn = mysqli_connect($server, $username, $password, $database);
    $err = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST["username"];
        $password = $_POST["password"];
        if ($user === "aggarwaldhruv419@gmail.com" && $password == "Krishna@hare1") {
            $login = true;
            $_SESSION['username'] = $user;
            $_SESSION['logged-in'] = true;
            header("location: welcomeAdministrator.php");
            exit();
        }
        $sql = "SELECT * FROM `users` WHERE `username` = '$user'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['pass'])) {
                $login = true;
                session_start();
                $_SESSION['logged-in'] = true;
                $_SESSION['username'] = $user;
                header("location: welcome.php");
            } else {
                $err = true;
            }
        } else {
            $err = true;
        }
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Login</title>
    <style>
        body {
            background: white;
            color: #333;
        }
        .container {
            max-width: 500px;
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            margin-top: 80px;
        }
        h1 {
            margin-bottom: 30px;
            color: #333;
            font-weight: bold;
        }
        .form-control {
            border-radius: 0.5rem;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 0.5rem;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .alert {
            position: relative;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.5rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .alert-dismissible .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 1.25rem 1rem;
            color: inherit;
        }
    </style>
  </head>
  <body>
    <?php require 'nav.php'; ?>
    <div class="container">
        <?php
            if ($login) {
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Success!</strong> You are logged in!
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
                </button>
                </div>";
            } else if ($err) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>Error!</strong> Invalid Credentials!
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
                </button>
                </div>";
            }
        ?>
        <h1 class="text-center">Log-in to your Account</h1>
        <form action="/dhruv/login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">We'll never share your username with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>