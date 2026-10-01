<?php
$pageTitle = 'Sign Up';
require_once 'includes/header.php';

$error = '';
$enteredUser = '';
if (isset($_SESSION['user'])) destroySession();

if (isset($_POST['user'])) {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    $enteredUser = $user;

    if ($user == "" || $pass == "") {
        $error = 'Please enter both a username and a password.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,16}$/', $user)) {
        $error = 'Username must be 3-16 characters: letters, numbers, or underscores only.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $result = queryMysql("SELECT * FROM members WHERE user=?", [$user]);

        if ($result->rowCount()) {
            $error = 'That username is already taken. Please choose another.';
        } else {
            $token = password_hash($pass, PASSWORD_DEFAULT);
            queryMysql("INSERT INTO members VALUES(?, ?)", [$user, $token]);
            setFlash('Account created! Please log in with your new username and password.');
            redirect('login.php');
        }
    }
}
?>

<section class="card narrow-card">
    <h2>Create Your Account</h2>
    <?php if ($error): ?>
        <p class="status-message taken" role="alert"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="signup.php">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" maxlength="16" name="user" id="username" class="form-input" value="<?php echo $enteredUser; ?>" autocomplete="username" aria-describedby="username-hint info" autofocus required>
            <span id="username-hint" class="hint">3-16 characters: letters, numbers, underscores.</span>
            <span id="info" class="status-message" aria-live="polite"></span>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" minlength="6" maxlength="16" name="pass" id="password" class="form-input" autocomplete="new-password" aria-describedby="password-hint" required>
            <span id="password-hint" class="hint">6-16 characters.</span>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
    </form>
    <p class="hint section-gap">Already have an account? <a href="login.php">Log in</a>.</p>
</section>

<?php
require_once 'includes/footer.php';
?>
