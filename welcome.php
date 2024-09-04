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
$user = $_SESSION['username'];
?>
<?php
$showAlert = false;
$showError = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["depositAmount"])) {
        $balanceCheck = "SELECT `balance` FROM `users` WHERE `username` = '$user'";
        $balanceResult = mysqli_query($conn, $balanceCheck);
        $balanceRow = mysqli_fetch_assoc($balanceResult);
        $balance = $balanceRow['balance'];
        $depositAmount = $_POST["depositAmount"];
        $sql = "UPDATE `users` SET `balance` = `balance` + $depositAmount WHERE `username` = '$user'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $ans = $balance + $depositAmount;
            $transac = "Success! Rs. {$depositAmount} Credited, Current Balance: {$ans}";
            $mess = "Rs. {$depositAmount} is deposited Successfully";
            $sql1 = "INSERT INTO `$user` (`transaction`, `message`) VALUES ('$transac', '$mess')";
            $resultSet = mysqli_query($conn, $sql1);
            $showAlert = "Amount deposited successfully!";
        } else {
            echo "Error in depositing amount: " . mysqli_error($conn); // Print the SQL error
        }
    }
    if (isset($_POST["withdrawAmount"])) {
        $withdrawAmount = $_POST["withdrawAmount"];
        $balanceCheck = "SELECT `balance`, `btype` FROM `users` WHERE `username` = '$user'";
        $balanceResult = mysqli_query($conn, $balanceCheck);
        $balanceRow = mysqli_fetch_assoc($balanceResult);
        $accType = $balanceRow['btype'];
        $balance = $balanceRow['balance'];
        if ($balanceRow["balance"] >= $withdrawAmount) {
            if ($accType === 'Current' && ($balanceRow['balance'] - $withdrawAmount) <= 4000) {
                $showError = "Minimum 4000 should be there in Current Account!";
                $transac = "Failure! Rs. {$withdrawAmount} Not Debited, Current Balance: {$balance}";
                $mess = "Rs. {$withdrawAmount} is Not Withdrawn Successfully";
                $sql = "INSERT INTO `$user` (`transaction`, `message`) VALUES ('$transac', '$mess')";
                $resultSet = mysqli_query($conn, $sql);
            } else if (($balanceRow['balance'] - $withdrawAmount) <= 1500) {
                $showError = "Minimum 1500 should be there in Savings Account!";
                $transac = "Failure! Rs. {$withdrawAmount} Not Debited, Current Balance: {$balance}";
                $mess = "Rs. {$withdrawAmount} is Not Withdrawn Successfully";
                $sql = "INSERT INTO `$user` (`transaction`, `message`) VALUES ('$transac', '$mess')";
                $resultSet = mysqli_query($conn, $sql);
            } else {
                $sql = "UPDATE `users` SET `balance` = `balance` - $withdrawAmount WHERE `username` = '$user'";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $ans = $balance - $withdrawAmount;
                    $transac = "Success! Rs. {$withdrawAmount} Debited, Current Balance: {$ans}";
                    $mess = "Rs. {$withdrawAmount} is Withdrawn Successfully";
                    $sql1 = "INSERT INTO `$user` (`transaction`, `message`) VALUES ('$transac', '$mess')";
                    $resultSet = mysqli_query($conn, $sql1);
                    $showAlert = "Amount withdrawn successfully!";
                } else {
                    $showError = "Error in withdrawing amount!";
                }
            }
        } else {
            $transac = "Failure! Insufficient Balance, Rs. {$withdrawAmount} Not Debited";
            $mess = "Rs. {$withdrawAmount} is Not Withdrawn Successfully, Current Balance: {$balance}";
            $sql = "INSERT INTO `$user` (`transaction`, `message`) VALUES ('$transac', '$mess')";
            $resultSet = mysqli_query($conn, $sql);
            $showError = "Insufficient balance!";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["issueATM"])) {
    $checkCardIssued = "SELECT `isDebitCardIssued`, `balance` FROM `users` WHERE `username` = '$user'";
    $checkResult = mysqli_query($conn, $checkCardIssued);
    $checkRow = mysqli_fetch_assoc($checkResult);
    $balance = $checkRow['balance'];
    if ($checkRow["isDebitCardIssued"] == 0) {
        $issueATMCost = 50;
        if ($checkRow["balance - 50;"] >= $issueATMCost) {
            $ans = $balance
            $deductSql = "UPDATE `users` SET `balance` = `balance` - $issueATMCost, `isDebitCardIssued` = 1 WHERE `username` = '$user'";
            $deductResult = mysqli_query($conn, $deductSql);
            $transac = "Rs. 50 is debited for ATM Card Issued!";
            $mess = "Rs. 50 Debited, Current Balance: {$ans}";
            $sql = "INSERT INTO `$user` (`transaction`, `message`) VALUES ('$transac', '$mess')";
            $resultSet = mysqli_query($conn, $sql);
            if ($deductResult) {
                echo "<script>
                        alert('Rs. 50 will be debited from your account');
                        window.location.href = 'generate_atm_card.php';
                      </script>";
                exit();
            } else {
                $showError = "Error in issuing ATM card!";
            }
        } else {
            $showError = "Insufficient balance to issue ATM card!";
        }
    } else {
        header("Location: generate_atm_card.php");
        exit();
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
    <title>Welcome -
        <?php echo $_SESSION['username']; ?>
    </title>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-section {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-bottom: 1px solid #0056b3;
            text-align: center;
        }

        .account-details {
            margin-top: 20px;
        }

        .bank-info {
            margin-top: 30px;
        }

        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 1rem;
        }

        .modal-header {
            background-color: #007bff;
            color: white;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 0.25rem;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .alert {
            margin-top: 20px;
            font-weight: bold;
        }

        .close {
            font-size: 1.2rem;
        }

        .card-body {
            background-color: #f8f9fa; 
            border-radius: 8px; 
            padding: 20px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            border: 1px solid #ddd; 
            transition: transform 0.2s ease-in-out; 
        }

        .card-body:hover {
            transform: translateY(-5px); 
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); 
            background-color: #ffffff; 
        }
    </style>
</head>
<body>
    <?php require 'nav.php'; ?>
    <?php
        if ($showAlert) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> $showAlert
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
            </button>
            </div>";
        }
        if ($showError) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <strong>Error!</strong> $showError
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
            </button>
            </div>";
        }
        
    ?>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Bank Balance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalBodyContent"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
<div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="depositModalLabel">Deposit Money</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="depositForm" method="post">
          <div class="form-group">
            <label for="depositAmount">Amount</label>
            <input type="number" class="form-control" id="depositAmount" name="depositAmount" required>
          </div>
          <button type="submit" class="btn btn-primary">Deposit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="withdrawModalLabel">Withdraw Money</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="withdrawForm" method="post">
          <div class="form-group">
            <label for="withdrawAmount">Amount</label>
            <input type="number" class="form-control" id="withdrawAmount" name="withdrawAmount" required>
          </div>
          <button type="submit" class="btn btn-primary">Withdraw</button>
        </form>
      </div>
    </div>
  </div>
</div>
    <div class="container my-4">
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Dear Valued Customer,</h4>
            <p>Welcome to iBankSecure. We are delighted to inform you that you have successfully logged in. As a valued
                member, you are now accessing your account with the username <b>
                    <?php
                $existsSql = "SELECT * FROM `users` WHERE `username` = '$user'";
                $result = mysqli_query($conn, $existsSql);
                $row = mysqli_fetch_assoc($result);
                echo $row['fname'] . ' ' . $row['lname'] . '!</b>';
                ?>
            </p>
            <p>Thank you for choosing iBankSecure.</p>
        </div>
        <div class="button-group text-center">
            <form action="javascript:void(0);" method="POST" class="d-inline">
            <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#depositModal">Deposit</button>            </form>
            <form action="javascript:void(0);" method="POST" class="d-inline mx-2">
            <button type="submit" class="btn btn-warning" data-toggle="modal" data-target="#withdrawModal">Withdraw</button>            </form>
            <form action="javascript:void(0);" method="POST" class="d-inline mx-2">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editModal" id="checkBalanceButton">Check Balance</button>
            </form>
            <form method="POST" class="d-inline mx-2">
                <input type="hidden" name="issueATM" value="true">
                <button type="submit" class="btn btn-primary"><?php 
                 $checkCardIssued = "SELECT `isDebitCardIssued` FROM `users` WHERE `username` = '$user'";
                 $checkResult = mysqli_query($conn, $checkCardIssued);
                 $checkRow = mysqli_fetch_assoc($checkResult);
                 if ($checkRow['isDebitCardIssued'] == 0) {
                    echo "Issue ATM Card";
                 } else {
                    echo "View ATM Card";
                 } 
                ?></button>
            </form>
            <form action="monthly_transaction_sheet.php" method="POST" class="d-inline mx-2">
                <button type="submit" class="btn btn-secondary">Monthly Transactions</button>
            </form>
            <form action="/dhruv/logout.php" method="POST" class="d-inline mx-2">
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
        <div>
        <?php
                $existsSql = "SELECT * FROM `users` WHERE `username` = '$user'";
                $result = mysqli_query($conn, $existsSql);
                $row = mysqli_fetch_assoc($result);
                
       echo "
        <div class='account-details'>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Account Holder</h5>
                            <p class='card-text'>" . $row['fname'] .' '. $row['lname'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Date and Time of Joining</h5>
                            <p class='card-text'>". $row['date'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Account Type</h5>
                            <p class='card-text'>". $row['btype'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Mother Name</h5>
                            <p class='card-text'>". $row['mother_n'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Father Name</h5>
                            <p class='card-text'>". $row['father_n'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Phone Number</h5>
                            <p class='card-text'>". $row['p_no'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Age</h5>
                            <p class='card-text'>". $row['age'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Address</h5>
                            <p class='card-text'>". $row['address'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Account Number</h5>
                            <p class='card-text'>". $row['acc_num'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Aadhar Number</h5>
                            <p class='card-text'>". $row['a_num'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Debit Card (1/0)</h5>
                            <p class='card-text'>". $row['isDebitCardIssued'] . "</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Bank Information -->
        <div class='bank-info'>
            <div class='card'>
                <div class='card-body'>
                    <h5 class='card-title p-2'>Bank Details</h5>
                    <p class='card-text p-3'>
                        <strong>Bank Name:</strong> Dehradun Bank<br>
                        <strong>Branch:</strong> Main Street Branch<br>
                        <strong>Contact:</strong> +91-769-123-4567<br>
                        <strong>Address:</strong> 566/6 Bell Road, Dehradun, Uttarakhand, 248002
                    </p>
                </div>
            </div>
        </div> "
        ?>
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
            document.getElementById('checkBalanceButton').addEventListener('click', function () {
            <?php
            $balanceSql = "SELECT balance FROM `users` WHERE `username` = '$user'";
            $balanceResult = mysqli_query($conn, $balanceSql);
            $balanceRow = mysqli_fetch_assoc($balanceResult);
            $balance = $balanceRow['balance'];
            ?>
            document.getElementById('modalBodyContent').innerHTML = "<h5>Your Current Balance: ₹<?php echo $balance; ?></h5>";
            });
        </script>
</body>
</html>