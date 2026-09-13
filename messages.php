<?php
require_once 'includes/header.php';

if (!$loggedin) die("</main></body></html>");

if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
else                      $view = $user;

if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    if ($text != "") {
        $pm   = substr(sanitizeString($_POST['pm']), 0, 1);
        $time = time();
        queryMysql("INSERT INTO messages VALUES(NULL, ?, ?, ?, ?, ?)",
                   [$user, $view, $pm, $time, $text]);
    }
}

if (isset($_GET['erase'])) {
    $erase = sanitizeString($_GET['erase']);
    queryMysql("DELETE FROM messages WHERE id=? AND recip=?", [$erase, $user]);
}

echo "<section class='card'><h2>Message Feed for $view</h2>";

echo "<form method='post' action='messages.php?view=$view'>
        <div class='form-group'>
            <label class='form-label'>Type Message:</label>
            <textarea name='text' class='form-textarea' rows='3'></textarea>
        </div>
        <div class='form-group'>
            <label><input type='radio' name='pm' value='0' checked> Public Message</label>
            <label style='margin-left: 1rem;'><input type='radio' name='pm' value='1'> Private Whisper</label>
        </div>
        <button type='submit' class='btn btn-primary'>Send Message</button>
      </form><hr style='margin: 1.5rem 0;'>";

$result = queryMysql("SELECT * FROM messages WHERE recip=? ORDER BY time DESC", [$view]);

while ($row = $result->fetch()) {
    if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user) {
        echo "<div class='message-item'>";
        echo "<strong>" . $row['auth'] . "</strong> ";
        echo "<span class='color-text-muted'>" . date('M jS \'y g:ia', $row['time']) . ":</span> ";

        if ($row['pm'] == 1) {
            echo "<span class='whisper-note'>Whispered: \"" . $row['message'] . "\"</span>";
        } else {
            echo "\"" . $row['message'] . "\"";
        }

        if ($row['recip'] == $user) {
            echo " [<a href='messages.php?view=$view&erase=" . $row['id'] . "'>Erase</a>]";
        }
        echo "</div>";
    }
}

echo "</section>";

require_once 'includes/footer.php';
?>