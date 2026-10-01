<?php
$pageTitle = 'Members';
require_once 'includes/header.php';
requireLogin();

if (isset($_GET['add']) || isset($_GET['remove'])) {
    $isAdd  = isset($_GET['add']);
    $target = sanitizeString($isAdd ? $_GET['add'] : $_GET['remove']);

    if ($target == $user || !userExists($target)) {
        setFlash('That member could not be found.', 'error');
    } elseif ($isAdd) {
        $result = queryMysql("SELECT * FROM friends WHERE user=? AND friend=?", [$target, $user]);
        if (!$result->rowCount()) {
            queryMysql("INSERT INTO friends VALUES (?, ?)", [$target, $user]);
        }
        setFlash("You are now following $target.");
    } else {
        queryMysql("DELETE FROM friends WHERE user=? AND friend=?", [$target, $user]);
        setFlash("You stopped following $target.", 'info');
    }
    redirect('members.php');
}

$iFollow = [];
$result = queryMysql("SELECT user FROM friends WHERE friend=?", [$user]);
while ($row = $result->fetch()) $iFollow[] = $row['user'];

$followMe = [];
$result = queryMysql("SELECT friend FROM friends WHERE user=?", [$user]);
while ($row = $result->fetch()) $followMe[] = $row['friend'];

$result = queryMysql("SELECT user FROM members WHERE user<>? ORDER BY user", [$user]);
$members = $result->fetchAll();
?>

<section class="card">
    <h2>Member Directory</h2>
    <p class="hint">Follow a member to add them to your Friends page. Click a name to see their profile and messages.</p>

    <?php if (!$members): ?>
        <p class="section-gap">You are the only member so far. Invite a coworker to sign up!</p>
    <?php else: ?>
        <div class="form-group section-gap">
            <label for="member-search" class="form-label">Search members</label>
            <input type="search" id="member-search" class="form-input" placeholder="Type a name to filter the list">
        </div>

        <ul class="member-list" id="member-list">
        <?php foreach ($members as $row):
            $name = $row['user']; ?>
            <li class="member-row" data-name="<?php echo strtolower($name); ?>">
                <?php echo avatarHtml($name); ?>
                <div class="member-info">
                    <a href="messages.php?view=<?php echo $name; ?>"><?php echo $name; ?></a>
                    <?php if (in_array($name, $followMe) && in_array($name, $iFollow)): ?>
                        <span class="badge">&harr; Mutual friend</span>
                    <?php elseif (in_array($name, $followMe)): ?>
                        <span class="badge">&rarr; Follows you</span>
                    <?php endif; ?>
                </div>
                <?php if (in_array($name, $iFollow)): ?>
                    <a class="btn btn-secondary btn-sm" href="members.php?remove=<?php echo $name; ?>">Unfollow</a>
                <?php else: ?>
                    <a class="btn btn-primary btn-sm" href="members.php?add=<?php echo $name; ?>">Follow</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        </ul>
        <p id="no-results" class="hint" hidden>No members match your search.</p>
    <?php endif; ?>
</section>

<?php
require_once 'includes/footer.php';
?>
