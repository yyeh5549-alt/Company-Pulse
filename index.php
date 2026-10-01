<?php
$pageTitle = 'Home';
require_once 'includes/header.php';
?>

<?php if (!$loggedin): ?>
<section class="card">
    <h2>Welcome to Company Pulse</h2>
    <p>Company Pulse is our internal social platform where employees build community, share profiles, and message each other.</p>
    <p class="section-gap">New here? <a href="signup.php">Create an account</a>. Already have one? <a href="login.php">Log in</a>.</p>
</section>
<?php else:
    $bio = getBio($user); ?>
<section class="card profile-header">
    <?php echo avatarHtml($user, 'avatar-thumbnail'); ?>
    <div>
        <h2>Welcome, <?php echo $user; ?>!</h2>
        <?php if ($bio != ''): ?>
            <p><?php echo $bio; ?></p>
        <?php else: ?>
            <p class="hint">You haven't written a bio yet. <a href="profile.php">Add one</a> so coworkers can get to know you.</p>
        <?php endif; ?>
    </div>
</section>

<section class="card">
    <h3>What would you like to do?</h3>
    <ul class="quick-links section-gap">
        <li><a class="btn btn-primary" href="members.php">Find &amp; follow members</a></li>
        <li><a class="btn btn-secondary" href="messages.php">Read my messages</a></li>
        <li><a class="btn btn-secondary" href="friends.php">See my connections</a></li>
        <li><a class="btn btn-secondary" href="profile.php">Edit my profile</a></li>
    </ul>
</section>
<?php endif; ?>

<?php
require_once 'includes/footer.php';
?>
