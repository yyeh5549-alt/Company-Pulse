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

function destroySession() {
    $_SESSION = array();

    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 2592000, '/');
    }

    session_destroy();
}
?>