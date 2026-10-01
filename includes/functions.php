<?php
/* ==========================================================================
   Company Pulse - Utility Functions & Security Sanitization
   ========================================================================== */

$dbhost  = 'localhost';
$dbname  = 'company_pulse';
$dbuser  = 'root';
$dbpass  = '';

try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

function createTable($name, $query) {
    global $pdo;
    $pdo->query("CREATE TABLE IF NOT EXISTS $name ($query)");
    echo "Table '$name' created or already exists.<br>";
}

function queryMysql($query, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

function sanitizeString($var) {
    $var = strip_tags($var);
    $var = htmlentities($var);
    return stripslashes($var);
}

function setFlash($message, $type = 'success') {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function requireLogin() {
    global $loggedin;
    if (!$loggedin) {
        setFlash('Please log in to view that page.', 'error');
        redirect('login.php');
    }
}

function userExists($name) {
    return queryMysql("SELECT user FROM members WHERE user=?", [$name])->rowCount() > 0;
}

function getBio($name) {
    $row = queryMysql("SELECT text FROM profiles WHERE user=?", [$name])->fetch();
    return $row ? stripslashes($row['text']) : '';
}

function avatarHtml($name, $class = 'avatar-small') {
    $file = "uploads/avatars/$name.jpg";
    if (file_exists($file)) {
        return "<img src='$file?v=" . filemtime($file) . "' class='$class' alt='$name avatar'>";
    }
    $initial = strtoupper(substr($name, 0, 1));
    return "<span class='avatar-placeholder $class' role='img' aria-label='$name avatar'>$initial</span>";
}

function destroySession() {
    $_SESSION = array();

    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 2592000, '/');
    }

    session_destroy();

    session_id(session_create_id());
    session_start();
}
?>