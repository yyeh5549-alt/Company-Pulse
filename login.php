<?php
require_once 'includes/header.php';

$error = "";

if (isset($_POST['user'])) {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);

    if ($user == "" || $pass == "") {
        $error = "Not all fields were entered";
    } else {
        $result = queryMysql("SELECT user, pass FROM members WHERE user=?", [$user]);

        if ($result->rowCount() == 0) {
            $error = "Invalid login attempt";
        } else {
            $row = $result->fetch();
            if (password_verify($pass, $row['pass'])) {
                $_SESSION['user'] = $user;
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid login attempt";
            }
        }
    }
}
?>

<section class="card" style="max-width: 500px; margin: 0 auto;">
    <h2>Log In</h2>
    <span class="status-message taken"><?php echo $error; ?></span>

    <form method="post" action="login.php">
        <div class="form-group">
            <label for="user" class="form-label">Username</label>
            <input type="text" name="user" id="user" class="form-input" required>
        </div>

        <div class="form-group">
            <label for="pass" class="form-label">Password</label>
            <input type="password" name="pass" id="pass" class="form-input" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Log In</button>
    </form>
</section>

<?php
require_once 'includes/footer.php';
?>