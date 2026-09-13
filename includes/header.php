<?php
session_start();

require_once 'functions.php';

$userstr = 'Welcome Guest';
$loggedin = false;

if (isset($_SESSION['user'])) {
    $user     = $_SESSION['user'];
    $loggedin = true;
    $userstr  = "Logged in as: $user";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Pulse</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/main.js" defer></script>
</head>
<body>

  <header class="app-header">
    <nav class="navbar">
      <div class="brand-logo">Company Pulse</div>

      <ul class="nav-list">
        <?php if ($loggedin): ?>
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="members.php" class="nav-link">Members</a></li>
            <li class="nav-item"><a href="friends.php" class="nav-link">Friends</a></li>
            <li class="nav-item"><a href="messages.php" class="nav-link">Messages</a></li>
            <li class="nav-item"><a href="profile.php" class="nav-link">Edit Profile</a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link">Log Out</a></li>
        <?php else: ?>
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="signup.php" class="nav-link">Sign Up</a></li>
            <li class="nav-item"><a href="login.php" class="nav-link">Log In</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <main class="main-container">