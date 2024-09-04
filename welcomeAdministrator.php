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
    $sql = "SELECT * FROM `users`";
    $result = mysqli_query($conn, $sql);
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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .logout-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            background-color: #dc3545;
            color: white;
            border-radius: 20px;
            font-weight: bold;
        }

        .card {
            width: auto;
            margin-bottom: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: black;
            font-weight: bold;
        }

        .btn-edit {
            background-color: #28a745;
            border: none;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            border: none;
            color: white;
        }

        .container {
            margin-top: 60px;
        }
    </style>
</head>

<body>
    <?php require 'nav.php'; ?>
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editUserForm" method="POST" action="update_user.php">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="p_no">Mobile Number</label>
                            <input type="text" class="form-control" id="p_no" name="p_no" required>
                        </div>

                        <div class="form-group">
                            <label for="father_n">Father's Name</label>
                            <input type="text" class="form-control" id="father_n" name="father_n" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="mother_n">Mother's Name</label>
                            <input type="text" class="form-control" id="mother_n" name="mother_n" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
     <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteUsername"></strong>'s account?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteUserForm" method="POST" action="delete_user.php">
                        <input type="hidden" id="deleteUserId" name="username">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form action="/dhruv/logout.php" method="POST" class="d-inline">
        <button type="submit" class="btn logout-btn">Logout</button>
    </form>
    <div class="container">
        <h1>Hello Administrator,</h1>
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Dear Dhruv Aggarwal (Manager of Dehradun Bank),</h4>
            <p>Welcome to iBankSecure. We are delighted to inform you that you have successfully logged in as <b>Dhruv
                    Aggarwal!</b>.</p>
        </div>
        <h2 class='my-4'>Dehradun Bank - All Account Details</h2>
        <div class="row">
            <?php
            if (mysqli_num_rows($result) > 0) {
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='col-md-4'>";
                    echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>". $count .') '. $row['fname']. ' ' . $row['lname'] . "</h5>";
                    echo "<h6 class='card-subtitle mb-2 text-muted'>" . ($row['username']) . "</h6>";
                    echo "<p class='card-text'><strong>Mother's Name:</strong> " . ($row['mother_n']) . "</p>";
                    echo "<p class='card-text'><strong>Father's Name:</strong> " . ($row['father_n']) . "</p>";
                    echo "<p class='card-text'><strong>Age:</strong> " . ($row['age']) . "</p>";
                    echo "<p class='card-text'><strong>Address:</strong> " . ($row['address']) . "</p>";
                    echo "<p class='card-text'><strong>Account Number:</strong> " . ($row['acc_num']) . "</p>";
                    echo "<p class='card-text'><strong>Aadhar Number:</strong> " . ($row['a_num']) . "</p>";
                    echo "<p class='card-text'><strong>Account Type:</strong> " . ($row['btype']) . "</p>";
                    echo "<p class='card-text'><strong>Balance:</strong> " . ($row['balance']) . "</p>";
                    echo "<p class='card-text'><strong>Created At:</strong> " . ($row['date']) . "</p>";
                    echo "<p class='card-text'><strong>Mobile Number:</strong> " . ($row['p_no']) . "</p>";
                    echo "<a href='' class='btn btn-edit' data-toggle='modal' data-target='#editUserModal' 
                    data-username='". $row['username'] ."' 
                    data-pno='". $row['p_no'] ."'
                    data-father_n='". $row['father_n'] ."'
                    data-mother_n='". $row['mother_n'] ."'
                    data-address='". $row['address'] ."'
                    data-name='". $row['fname'] . ' ' . $row['lname']."'>Edit</a> ";
                    echo "<a href='' class='btn btn-delete ml-2' data-toggle='modal' data-target='#deleteUserModal' 
                    data-username='". $row['username'] ."'>Delete</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<br>";
                    $count = $count + 1;
                }
            } else {
                echo "<div class='col-12'><p>No users found</p></div>";
            }
            ?>
        </div>
    </div>
    <script>
    $('#editUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id'); 
        var username = button.data('username');
        var p_no = button.data('pno');
        var father_n = button.data('father_n');
        var name = button.data('name');
        var mother_n = button.data('mother_n');
        var address = button.data('address');
        var modal = $(this);
        modal.find('.modal-body #user_id').val(id);
        modal.find('.modal-body #name').val(name);
        modal.find('.modal-body #username').val(username);
        modal.find('.modal-body #p_no').val(p_no);
        modal.find('.modal-body #father_n').val(father_n);
        modal.find('.modal-body #mother_n').val(mother_n);
        modal.find('.modal-body #address').val(address);
    });
    $('#deleteUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var username = button.data('username')

            var modal = $(this)
            modal.find('.modal-body #deleteUsername').text(username)
            modal.find('.modal-footer #deleteUserId').val(username)
        })
</script>
</body>
</html>