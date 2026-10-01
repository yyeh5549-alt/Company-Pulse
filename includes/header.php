<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) session_start();

require_once 'functions.php';

$userstr = 'Welcome Guest';
$loggedin = false;

if (isset($_SESSION['user'])) {
    $user     = $_SESSION['user'];
    $loggedin = true;
    $userstr  = "Logged in as: $user";
}

$currentPage = basename($_SERVER['SCRIPT_NAME']);

if ($loggedin) {
    $navItems = [
        'index.php'    => 'Home',
        'members.php'  => 'Members',
        'friends.php'  => 'Friends',
        'messages.php' => 'Messages',
        'profile.php'  => 'Edit Profile',
        'logout.php'   => 'Log Out',
    ];
} else {
    $navItems = [
        'index.php'  => 'Home',
        'signup.php' => 'Sign Up',
        'login.php'  => 'Log In',
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' : ''; ?>Company Pulse</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/main.js" defer></script>
</head>
<body>

  <header class="app-header">
    <nav class="navbar" aria-label="Main navigation">
      <a href="index.php" class="brand-logo">Company Pulse</a>

      <ul class="nav-list">
        <?php foreach ($navItems as $href => $label): ?>
            <li class="nav-item"><a href="<?php echo $href; ?>" class="nav-link<?php echo $currentPage === $href ? ' active' : ''; ?>"<?php echo $currentPage === $href ? ' aria-current="page"' : ''; ?>><?php echo $label; ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <p class="user-status"><?php echo $userstr; ?></p>
  </header>

  <main class="main-container">
<?php if (!empty($_SESSION['flash'])):
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']); ?>
    <div class="flash flash-<?php echo $flash['type']; ?>" role="status"><?php echo $flash['message']; ?></div>
<?php endif; ?>
