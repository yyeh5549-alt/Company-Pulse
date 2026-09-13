<?php
require_once 'includes/header.php';

if (!$loggedin) die("</main></body></html>");

if (isset($_GET['add'])) {
    $add = sanitizeString($_GET['add']);

    $result = queryMysql("SELECT * FROM friends WHERE user=? AND friend=?", [$add, $user]);
    if (!$result->rowCount()) {
        queryMysql("INSERT INTO friends VALUES (?, ?)", [$add, $user]);
    }
} elseif (isset($_GET['remove'])) {
    $remove = sanitizeString($_GET['remove']);
    queryMysql("DELETE FROM friends WHERE user=? AND friend=?", [$remove, $user]);
}

$result = queryMysql("SELECT user FROM members ORDER BY user");
$num    = $result->rowCount();

echo "<section class='card'><h2>Member Directory</h2><ul>";

while ($row = $result->fetch()) {
    if ($row['user'] == $user) continue;

    echo "<li style='margin-bottom: 0.5rem;'>";
    echo "<a href='messages.php?view=" . $row['user'] . "'>" . $row['user'] . "</a> ";

    $followResult = queryMysql("SELECT * FROM friends WHERE user=? AND friend=?", [$row['user'], $user]);
    $following = $followResult->rowCount();

    if ($following) {
        echo " [<a href='members.php?remove=" . $row['user'] . "'>Drop Connection</a>]";
    } else {
        echo " [<a href='members.php?add=" . $row['user'] . "'>Follow Member</a>]";
    }
    echo "</li>";
}
echo "</ul></section>";

require_once 'includes/footer.php';
?>