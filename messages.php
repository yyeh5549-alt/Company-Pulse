<?php
$pageTitle = 'Messages';
require_once 'includes/header.php';
requireLogin();

$view   = isset($_GET['view']) ? sanitizeString($_GET['view']) : $user;
$isSelf = ($view == $user);

if (!$isSelf && !userExists($view)) {
    setFlash("We couldn't find a member named $view.", 'error');
    redirect('messages.php');
}

if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    if (trim($text) == "") {
        setFlash('Please type a message before sending.', 'error');
    } else {
        $pm   = substr(sanitizeString($_POST['pm']), 0, 1);
        $time = time();
        queryMysql("INSERT INTO messages VALUES(NULL, ?, ?, ?, ?, ?)",
                   [$user, $view, $pm, $time, $text]);
        setFlash($pm == '1' ? "Private whisper sent to $view." : 'Message posted.');
    }
    redirect("messages.php?view=$view");
}

if (isset($_GET['erase'])) {
    $erase = sanitizeString($_GET['erase']);
    queryMysql("DELETE FROM messages WHERE id=? AND recip=?", [$erase, $user]);
    setFlash('Message erased.', 'info');
    redirect("messages.php?view=$view");
}

$bio = getBio($view);

echo "<section class='card profile-header'>" . avatarHtml($view, 'avatar-thumbnail');
echo "<div><h2>" . ($isSelf ? "My Messages" : "Messages for $view") . "</h2>";
echo $bio != '' ? "<p>$bio</p>" : "<p class='hint'>" . ($isSelf ? "You haven't written a bio yet. <a href='profile.php'>Add one</a>." : "$view hasn't written a bio yet.") . "</p>";
echo "<p class='hint'>" . ($isSelf ? "" : "<a href='members.php'>&larr; Back to Members</a> &middot; ") . "<a href='friends.php?view=$view'>" . ($isSelf ? "My connections" : "View $view's connections") . "</a></p>";
echo "</div></section>";

echo "<section class='card'><h3>" . ($isSelf ? "Post on your own page" : "Write to $view") . "</h3>";

echo "<form method='post' action='messages.php?view=$view'>
        <div class='form-group'>
            <label for='text' class='form-label'>Your message</label>
            <textarea name='text' id='text' class='form-textarea' rows='3' maxlength='4000' placeholder='Type your message here...' required></textarea>
        </div>
        <div class='form-group'>
            <label><input type='radio' name='pm' value='0' checked> Public (everyone can read it)</label>
            <label class='radio-gap'><input type='radio' name='pm' value='1'> Private whisper (only you and $view can read it)</label>
        </div>
        <button type='submit' class='btn btn-primary'>Send Message</button>
      </form></section>";

echo "<section class='card'><h3>Messages</h3>";

$result = queryMysql("SELECT * FROM messages WHERE recip=? ORDER BY time DESC", [$view]);
$shown  = 0;

while ($row = $result->fetch()) {
    if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user) {
        $shown++;
        echo "<div class='message-item'>";
        echo "<strong>" . $row['auth'] . "</strong> ";
        echo "<span class='text-muted'>" . date('M jS \'y g:ia', $row['time']) . ":</span> ";

        if ($row['pm'] == 1) {
            echo "<span class='whisper-note'>Whispered: \"" . $row['message'] . "\"</span>";
        } else {
            echo "\"" . $row['message'] . "\"";
        }

        if ($row['recip'] == $user) {
            echo " [<a href='messages.php?view=$view&erase=" . $row['id'] . "' data-confirm='Erase this message? This cannot be undone.'>Erase</a>]";
        }
        echo "</div>";
    }
}

if (!$shown) {
    echo "<p class='hint'>No messages yet. " . ($isSelf ? "Messages others send you will appear here." : "Be the first to write to $view!") . "</p>";
}

echo "</section>";

require_once 'includes/footer.php';
?>
