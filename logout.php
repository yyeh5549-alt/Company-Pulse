<?php
session_start();
require_once 'includes/functions.php';

$wasLoggedIn = isset($_SESSION['user']);
if ($wasLoggedIn) destroySession();

require_once 'includes/header.php';

setFlash($wasLoggedIn ? 'You have been logged out. See you soon!' : 'You were not logged in.', $wasLoggedIn ? 'success' : 'info');
redirect('index.php');
?>
