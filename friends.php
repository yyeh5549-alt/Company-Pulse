<?php
$pageTitle = 'Friends';
require_once 'includes/header.php';
requireLogin();

$view   = isset($_GET['view']) ? sanitizeString($_GET['view']) : $user;
$isSelf = ($view == $user);

if (!$isSelf && !userExists($view)) {
    setFlash("We couldn't find a member named $view.", 'error');
    redirect('friends.php');
}

$followers = [];
$following = [];

$result = queryMysql("SELECT * FROM friends WHERE user=?", [$view]);
while ($row = $result->fetch()) {
    $followers[] = $row['friend'];
}

$result = queryMysql("SELECT * FROM friends WHERE friend=?", [$view]);
while ($row = $result->fetch()) {
    $following[] = $row['user'];
}

$mutual    = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);

function friendList($title, $hint, $names) {
    echo "<h3>$title</h3><p class='hint'>$hint</p><ul class='member-list'>";
    foreach ($names as $name) {
        echo "<li class='member-row'>" . avatarHtml($name) .
             "<div class='member-info'><a href='messages.php?view=$name'>$name</a></div>" .
             "<a class='btn btn-secondary btn-sm' href='messages.php?view=$name'>Message</a></li>";
    }
    echo "</ul>";
}

$who   = $isSelf ? 'You' : $view;
$whose = $isSelf ? 'Your' : "$view's";

echo "<section class='card'><h2>" . ($isSelf ? "My Connections" : "$view's Connections") . "</h2>";
if (!$isSelf) {
    echo "<p class='hint'><a href='messages.php?view=$view'>&larr; Back to $view's page</a></p>";
}

if (sizeof($mutual)) {
    friendList('Mutual Friends', $isSelf ? 'You follow each other.' : "$view and these members follow each other.", $mutual);
}

if (sizeof($followers)) {
    friendList("$whose Followers", $isSelf ? 'These members follow you.' : "These members follow $view.", $followers);
}

if (sizeof($following)) {
    friendList($isSelf ? 'Members You Follow' : "Members $view Follows",
               $isSelf ? 'You follow these members, but they have not followed you back yet.' : "$view follows these members, but they have not followed back yet.",
               $following);
}

if (!sizeof($mutual) && !sizeof($followers) && !sizeof($following)) {
    echo $isSelf
        ? "<p>No connections yet. Visit the <a href='members.php'>Members page</a> and follow a coworker to get started.</p>"
        : "<p>$view has no connections yet.</p>";
}

echo "</section>";

require_once 'includes/footer.php';
?>
