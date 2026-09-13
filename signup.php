<?php
require_once 'includes/header.php';

$error = '';
if (isset($_SESSION['user'])) destroySession();

if (isset($_POST['user'])) {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);

    if ($user == "" || $pass == "") {
        $error = 'Not all fields were entered<br>';
    } else {
        $result = queryMysql("SELECT * FROM members WHERE user=?", [$user]);

        if ($result->rowCount()) {
            $error = 'That username already exists<br>';
        } else {
            $token = password_hash($pass, PASSWORD_DEFAULT);
            queryMysql("INSERT INTO members VALUES(?, ?)", [$user, $token]);
            die('<section class="card"><h4>Account created</h4>Please <a href="login.php">log in</a>.</section></main></body></html>');
        }
    }
}
?>

<section class="card" style="max-width: 500px; margin: 0 auto;">
    <h2>Create Your Account</h2>
    <span class="status-message taken"><?php echo $error; ?></span>

    <form method="post" action="signup.php">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" maxlength="16" name="user" id="username" class="form-input" onblur="checkUser(this)" required>
            <span id="info" class="status-message"></span>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" maxlength="16" name="pass" id="password" class="form-input" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Sign Up</button>
    </form>
</section>

<?php
require_once 'includes/footer.php';
?>