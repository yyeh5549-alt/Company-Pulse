<?php
require_once 'includes/header.php';
?>

<section class="card">
    <h2>Welcome to Company Pulse</h2>
    <p>Company Pulse is our internal social platform designed for employees to build community, share profiles, and communicate securely[cite: 1].</p>

    <?php if (!$loggedin): ?>
        <p style="margin-top: 1rem;">Please <a href="signup.php">Sign Up</a> or <a href="login.php">Log In</a> to join the conversation.</p>
    <?php else: ?>
        <p style="margin-top: 1rem; color: var(--color-success);">You are logged in, <strong><?php echo $user; ?></strong>!</p>
    <?php endif; ?>
</section>

<?php
require_once 'includes/footer.php';
?>