<?php
if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true) {
    $loggedin = true;
} else {
    $loggedin = false;
}

echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="navbar-brand">Dehradun Bank</div>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">';

    // Check if the logged-in user is not 'aggarwaldhruv419@gmail.com'
    if (isset($_SESSION['logged-in']) && ($_SESSION['username'] !== "aggarwaldhruv419@gmail.com")) {
        echo '<li class="nav-item">
                <a class="nav-link" href="/dhruv/welcome.php">Home <span class="sr-only">(current)</span></a>
              </li>';
    }
    if (!$loggedin) {
        echo '<li class="nav-item">
                <a class="nav-link" href="/dhruv/login.php">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/dhruv/signup.php">Signup</a>
              </li>';
    }
echo '</ul>
  </div>
</nav>';
?>