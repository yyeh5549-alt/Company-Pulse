<?php
$pageTitle = 'Edit Profile';
require_once 'includes/header.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    $text = preg_replace('/\s\s+/', ' ', $text);

    $exists = queryMysql("SELECT user FROM profiles WHERE user=?", [$user])->rowCount();
    if ($exists) {
        queryMysql("UPDATE profiles SET text=? WHERE user=?", [$text, $user]);
    } else {
        queryMysql("INSERT INTO profiles VALUES(?, ?)", [$user, $text]);
    }

    $uploadError = '';
    $file = $_FILES['image'] ?? null;

    if ($file && $file['name'] != "") {
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            $uploadError = 'That picture is too large. Please choose one under 2 MB.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $uploadError = 'The picture could not be uploaded. Please try again.';
        } elseif (!function_exists('imagecreatetruecolor')) {
            $uploadError = 'Picture uploads are not available on this server.';
        } else {
            $info = @getimagesize($file['tmp_name']);
            switch ($info ? $info[2] : null) {
                case IMAGETYPE_GIF:  $src = imagecreatefromgif($file['tmp_name']);  break;
                case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($file['tmp_name']); break;
                case IMAGETYPE_PNG:  $src = imagecreatefrompng($file['tmp_name']);  break;
                default:             $src = false; break;
            }

            if (!$src) {
                $uploadError = 'Please choose a .jpg, .png, or .gif picture.';
            } else {
                list($w, $h) = $info;
                $max   = 100;
                $scale = min(1, $max / max($w, $h));
                $tw    = max(1, (int) round($w * $scale));
                $th    = max(1, (int) round($h * $scale));

                $tmp = imagecreatetruecolor($tw, $th);
                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);

                if (!is_dir('uploads/avatars')) mkdir('uploads/avatars', 0755, true);
                imagejpeg($tmp, "uploads/avatars/$user.jpg");
            }
        }
    }

    if ($uploadError) {
        setFlash("Your bio was saved, but the picture was not changed. $uploadError", 'error');
    } else {
        setFlash('Profile saved.');
    }
    redirect('profile.php');
}

$row  = queryMysql("SELECT text FROM profiles WHERE user=?", [$user])->fetch();
$text = $row ? stripslashes($row['text']) : "";
?>

<section class="card">
    <h2>Edit Your Profile</h2>
    <p class="hint">Other members see your picture and bio when they open your page from the Members list.</p>

    <form method="post" action="profile.php" enctype="multipart/form-data" class="section-gap">
        <div class="form-group">
            <span class="form-label">Current picture</span>
            <?php echo avatarHtml($user, 'avatar-thumbnail'); ?>
        </div>

        <div class="form-group">
            <label for="bio" class="form-label">About me</label>
            <textarea name="text" id="bio" class="form-textarea" rows="4" maxlength="4000" placeholder="Tell your coworkers a bit about yourself..."><?php echo $text; ?></textarea>
        </div>

        <div class="form-group">
            <label for="image" class="form-label">Change picture (optional)</label>
            <input type="file" name="image" id="image" class="form-input" accept="image/jpeg,image/png,image/gif" aria-describedby="image-hint">
            <span id="image-hint" class="hint">.jpg, .png, or .gif, up to 2 MB. Leave empty to keep your current picture.</span>
        </div>

        <button type="submit" class="btn btn-primary">Save Profile</button>
    </form>
</section>

<?php
require_once 'includes/footer.php';
?>
