<?php
require_once __DIR__.'/../config/load_config.php';

if(!Admin::isAdmin()) {
    header('Location: login.php');
    exit;
}

$posts = Forum::getPosts(50);
$banned_ips = Admin::getBannedIPs();

include __DIR__.'/../includes/header.php';
?>

<div class="admin-container">
    <div class="admin-header">
        <h2 class="glitch" data-text="ADMIN DASHBOARD">ADMIN DASHBOARD</h2>
        <div class="admin-meta">
            <span>Logged in as: MASTER ADMIN</span>
            <span>Session IP: <?= $_SERVER['REMOTE_ADDR'] ?></span>
            <a href="logout.php" class="admin-logout">LOGOUT</a>
        </div>
    </div>

    <div class="admin-grid">
        <div class="admin-card">
            <h3>QUICK ACTIONS</h3>
            <ul class="admin-actions">
                <li><a href="ban_manager.php">IP Ban Manager</a></li>
                <li><a href="#" id="purgeSessions">Purge Guest Sessions</a></li>
                <li><a href="#" id="enableSleepMode">Enable Sleep Mode</a></li>
            </ul>
        </div>

        <div class="admin-card">
            <h3>SYSTEM STATUS</h3>
            <div class="status-indicators">
                <div class="status-item <?= count($banned_ips) > 0 ? 'active' : '' ?>">
                    Banned IPs: <?= count($banned_ips) ?>
                </div>
                <div class="status-item">
                    Active Threads: <?= count($posts) ?>
                </div>
                <div class="status-item">
                    Security Level: MAXIMUM
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3>RECENT POSTS MONITOR</h3>
        <div class="post-monitor">
            <?php foreach($posts as $post): ?>
            <div class="monitored-post">
                <div class="post-meta">
                    <span class="post-author"><?= sanitize($post['author']) ?></span>
                    <span class="post-ip"><?= sanitize($post['ip_address']) ?></span>
                    <span class="post-date"><?= date('M j H:i', strtotime($post['created_at'])) ?></span>
                </div>
                <div class="post-content"><?= substr(sanitize($post['content']), 0, 100) ?>...</div>
                <div class="post-actions">
                    <a href="delete_post.php?id=<?= $post['id'] ?>" class="delete-btn">DELETE</a>
                    <a href="ban_manager.php?ip=<?= $post['ip_address'] ?>" class="ban-btn">BAN IP</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('enableSleepMode').addEventListener('click', function(e) {
    e.preventDefault();
    if(confirm('WARNING: This will put the forum into sleep mode. Continue?')) {
        fetch('../security.php?action=sleep_mode')
            .then(response => location.reload());
    }
});

document.getElementById('purgeSessions').addEventListener('click', function(e) {
    e.preventDefault();
    if(confirm('Purge all guest sessions? This will log out all non-admin users.')) {
        fetch('../security.php?action=purge_sessions')
            .then(response => location.reload());
    }
});
</script>

<?php include __DIR__.'/../includes/footer.php'; ?>
