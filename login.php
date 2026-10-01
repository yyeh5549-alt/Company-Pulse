<?php
$pageTitle = 'Log In';
require_once 'includes/header.php';

$error = "";
$enteredUser = "";

if (isset($_POST['user'])) {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    $enteredUser = $user;

    if ($user == "" || $pass == "") {
        $error = "Please enter both a username and a password.";
    } else {
        $result = queryMysql("SELECT user, pass FROM members WHERE user=?", [$user]);

        if ($result->rowCount() == 0) {
            $error = "Username or password is incorrect.";
        } else {
            $row = $result->fetch();
            if (password_verify($pass, $row['pass'])) {
                $_SESSION['user'] = $user;
                setFlash("Welcome back, $user!");
                redirect("index.php");
            } else {
                $error = "Username or password is incorrect.";
            }
        }
    }
}
?>

<section class="card narrow-card">
    <h2>Log In</h2>
    <?php if ($error): ?>
        <p class="status-message taken" role="alert"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <div class="form-group">
            <label for="user" class="form-label">Username</label>
            <input type="text" name="user" id="user" class="form-input" value="<?php echo $enteredUser; ?>" autocomplete="username" autofocus required>
        </div>

        <div class="form-group">
            <label for="pass" class="form-label">Password</label>
            <input type="password" name="pass" id="pass" class="form-input" autocomplete="current-password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Log In</button>
    </form>
    <p class="hint section-gap">No account yet? <a href="signup.php">Sign up here</a>.</p>
</section>

<?php
require_once 'includes/footer.php';
?>
