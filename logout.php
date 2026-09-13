<?php
require_once 'includes/header.php';

if (isset($_SESSION['user'])) {
    destroySession();
    echo "<section class='card'><h2>Logged Out</h2>You have been logged out. Please <a href='index.php'>click here</a> to refresh.</section>";
} else {
    echo "<section class='card'>You cannot log out because you are not logged in.</section>";
}

require_once 'includes/footer.php';
?>