<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "users19"; 
    $conn = mysqli_connect($server, $username, $password, $database);
    $showAlert = false;
    $showError = false;
    $showErr = false;
    $acc_no = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST["username"];
        $pass = $_POST["password"];
        $cpass = $_POST["cpassword"];
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $balance = $_POST["balance"];
        $bType = $_POST["btype"];
        $phn_no = $_POST["p_no"];
        $address = $_POST["address"];
        $age = $_POST["age"];
        $ftname = $_POST["ftname"];
        $mname = $_POST["mname"];
        $a_no = $_POST["a_num"];
        $existsSql = "Select * from `users` where `username` = '$user'";
        $result = mysqli_query($conn, $existsSql);
        if ($user == "aggarwaldhruv419@gmail.com") {
            $isExists = true;
        }
        else if (mysqli_num_rows($result) > 0) {
            $isExists = true;
        } else {
            $isExists = false;
        }
        if (($pass == $cpass) && $isExists == false) {
            if (($bType == "Saving" && $balance < 1500) || ($bType == "Current" && $balance < 4000)) {
                $showError = true;
                $showErr = ($bType == "Saving" ? "Minimum balance for Savings account is 1500!" : "Minimum balance for Current account is 4000!");
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` (`fname`, `lname`, `username`,`pass`, `date`, `balance`, `btype`, `p_no`, `age`, `address`, `father_n`, `mother_n`, `a_num`) VALUES ('$fname', '$lname', '$user', '$hash', current_timestamp(), '$balance', '$bType', '$phn_no', '$age', '$address', '$ftname', '$mname', '$a_no')";
                $result = mysqli_query($conn, $sql);
                $createTableSql = "CREATE TABLE `users19`.`$user` (`transaction` TEXT NOT NULL , `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, `message` TEXT NOT NULL) ENGINE = InnoDB;";
                $resultTable = mysqli_query($conn, $createTableSql);
                $transac = "Congratulations! Your Account is Created!";
                $mess = "Current Balance: {$balance}";
                $sql = "INSERT INTO `$user` (`transaction`, `message`) VALUES ('$transac', '$mess')";
                $resultSet = mysqli_query($conn, $sql);
                if ($result) {
                    $showAlert = true;
                    $acc_no_query = "SELECT * FROM `users` WHERE `username` = '$user'";
                    $acc_no_result = mysqli_query($conn, $acc_no_query);
                    if ($acc_no_result) {
                        $acc_no_row = mysqli_fetch_assoc($acc_no_result);
                        $acc_no = $acc_no_row['acc_num']; 
                    }
                    echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        $('#signupModal').modal('show');
                    });
                  </script>";
                } else {
                    $showErr = "Your account is not created, Try Again!";
                    $showError = true;
                }
            }
        } else {
            $showError = true;
            if ($pass != $cpass) {
                $showErr = "Password do not match!";
            } else {
                $showErr = "This already exists!";
            }
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Sign-up</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .form-control {
            border-radius: 0.25rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 0.25rem;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        h1.text-center {
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: 600;
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
    <?php require 'nav.php' ?>
    <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalLabel">Account Created Successfully</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Your account has been created successfully. Here are your details:</p>
                    <ul>
                        <li><strong>Name: </strong>
                            <?php echo $fname . ' ' . $lname; ?>
                        </li>
                        <li><strong>Mother's Name: </strong>
                            <?php echo $mname; ?>
                        </li>
                        <li><strong>Father's Name: </strong>
                            <?php echo $ftname; ?>
                        </li>
                        <li><strong>Email: </strong>
                            <?php echo $user; ?>
                        </li>
                        <li><strong>Phone Number: </strong>
                            <?php echo $phn_no; ?>
                        </li>
                        <li><strong>Account Type: </strong>
                            <?php echo $bType; ?>
                        </li>
                        <li><strong>Account Number: </strong>
                            <?php echo $acc_no; ?>
                        </li>
                        <li><strong>Initial Balance: </strong>
                            <?php echo $balance; ?>
                        </li>
                    </ul>
                    <p>You can now log in with your credentials.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary closeHead" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php
        if ($showError) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <strong>Error!</strong> $showErr
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
            </button>
            </div>";
        }
    ?>
    <div class="container">
        <h1 class="text-center">Create Your Bank Account</h1>
        <form action="/dhruv/signup.php" method="post">
            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" class="form-control" name="fname" id="fname" required>
            </div>
            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control" name="lname" id="lname" required>
            </div>
            <div class="form-group">
                <label for="mname">Mother's Name</label>
                <input type="text" class="form-control" name="mname" id="mname" required>
            </div>
            <div class="form-group">
                <label for="ftname">Father's Name</label>
                <input type="text" class="form-control" name="ftname" id="ftname" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" class="form-control" name="age" id="age" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" name="address" id="address" required>
            </div>
            <div class="form-group">
                <label for="a_num">Aadhar Number</label>
                <input type="text" class="form-control" name="a_num" id="a_num" required>
            </div>
            <div class="form-group">
                <label for="username">Email Address</label>
                <input type="email" class="form-control" id="username" name="username" aria-describedby="emailHelp"
                    required>
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                    else.</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" class="form-control" name="cpassword" id="cpassword" required>
            </div>
            <div class="form-group">
                <label for="p_no">Phone Number</label>
                <input type="number" class="form-control" name="p_no" id="p_no" required>
            </div>
            <div class="form-group">
                <label for="btype">Account Type</label>
                <select name="btype" id="btype" class="form-control" required>
                    <option value="Saving" selected>Savings</option>
                    <option value="Current">Current</option>
                </select>
            </div>
            <div class="form-group">
                <label for="balance">Initial Balance</label>
                <input type="number" class="form-control" name="balance" id="balance" required>
                <small id="balanceHelp" class="form-text text-muted">Minimum Balance Required: 1500 for Savings and 4000
                    for Current Account.</small>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
        <script>
            document.querySelector('.closeHead').addEventListener('click', function () {
                window.location.href = "login.php";
            });
        </script>
</body>
</html>