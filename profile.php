<?php
require_once 'includes/header.php';

if (!$loggedin) die("</main></body></html>");

echo "<section class='card'><h2>Your Profile</h2>";

$result = queryMysql("SELECT * FROM profiles WHERE user=?", [$user]);

if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    $text = preg_replace('/\s\s+/', ' ', $text);

    if ($result->rowCount()) {
        queryMysql("UPDATE profiles SET text=? WHERE user=?", [$text, $user]);
    } else {
        queryMysql("INSERT INTO profiles VALUES(?, ?)", [$user, $text]);
    }
} else {
    if ($result->rowCount()) {
        $row  = $result->fetch();
        $text = stripslashes($row['text']);
    } else {
        $text = "";
    }
}

if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
    $saveto = "uploads/avatars/$user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;

    switch($_FILES['image']['type']) {
        case "image/gif":   $src = imagecreatefromgif($saveto); break;
        case "image/jpeg":
        case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
        case "image/png":   $src = imagecreatefrompng($saveto); break;
        default:            $typeok = FALSE; break;
    }

    if ($typeok) {
        list($w, $h) = getimagesize($saveto);
        $max = 100;
        $tw  = $w;
        $th  = $h;

        if ($w > $h && $max < $w) {
            $th = $max / $w * $h;
            $tw = $max;
        } elseif ($h > $w && $max < $h) {
            $tw = $max / $h * $w;
            $th = $max;
        } elseif ($max < $w) {
            $tw = $th = $max;
        }

        $tmp = imagecreatetruecolor($tw, $th);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
        imagejpeg($tmp, $saveto);
        imagedestroy($tmp);
        imagedestroy($src);
    }
}
?>

    <form method="post" action="profile.php" enctype="multipart/form-data">
        <div class="form-group">
            <label class="form-label">Current Profile Avatar</label>
            <?php
            if (file_exists("uploads/avatars/$user.jpg")) {
                echo "<img src='uploads/avatars/$user.jpg' class='avatar-thumbnail' alt='Avatar'>";
            } else {
                echo "<img src='images/default-avatar.png' class='avatar-thumbnail' alt='Default Avatar'>";
            }
            ?>
        </div>

        <div class="form-group">
            <label for="bio" class="form-label">About Me Bio</label>
            <textarea name="text" id="bio" class="form-textarea" rows="4"><?php echo $text; ?></textarea>
        </div>

        <div class="form-group">
            <label for="image" class="form-label">Upload Profile Picture (.jpg, .png, .gif)</label>
            <input type="file" name="image" id="image" class="form-input">
        </div>

        <button type="submit" class="btn btn-primary">Save Profile</button>
    </form>
</section>

<?php
require_once 'includes/footer.php';
?>