<?php
require_once 'includes/header.php';

if (!$loggedin) die("</main></body></html>");

$followers = [];
$following = [];

$result = queryMysql("SELECT * FROM friends WHERE user=?", [$user]);
while ($row = $result->fetch()) {
    $followers[] = $row['friend'];
}

$result = queryMysql("SELECT * FROM friends WHERE friend=?", [$user]);
while ($row = $result->fetch()) {
    $following[] = $row['user'];
}

$mutual    = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);

echo "<section class='card'><h2>Connections & Friends Hub</h2>";

if (sizeof($mutual)) {
    echo "<h3>Mutual Friends</h3><ul>";
    foreach($mutual as $friend)
        echo "<li><a href='messages.php?view=$friend'>$friend</a></li>";
    echo "</ul>";
}

if (sizeof($followers)) {
    echo "<h3>Your Followers</h3><ul>";
    foreach($followers as $friend)
        echo "<li><a href='messages.php?view=$friend'>$friend</a></li>";
    echo "</ul>";
}

if (sizeof($following)) {
    echo "<h3>Members You Follow</h3><ul>";
    foreach($following as $friend)
        echo "<li><a href='messages.php?view=$friend'>$friend</a></li>";
    echo "</ul>";
}

if (!sizeof($mutual) && !sizeof($followers) && !sizeof($following)) {
    echo "<p>No active connections yet.</p>";
}

echo "</section>";

require_once 'includes/footer.php';
?>