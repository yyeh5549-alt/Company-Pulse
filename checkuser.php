<?php
require_once 'includes/functions.php';

if (isset($_POST['user'])) {
    $user   = sanitizeString($_POST['user']);
    $result = queryMysql("SELECT * FROM members WHERE user=?", [$user]);

    if ($result->rowCount()) {
        echo "<span class='taken'>&times; Username '$user' is taken</span>";
    } else {
        echo "<span class='available'>&check; Username '$user' is available</span>";
    }
}
?>