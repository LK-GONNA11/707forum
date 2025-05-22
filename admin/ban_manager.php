<?php
require_once __DIR__.'/../config/load_config.php';

if(!Admin::isAdmin()) {
    header('Location: login.php');
    exit;
}

if(isset($_GET['action'])) {
    $ip = $_GET['ip'] ?? '';
    switch($_GET['action']) {
        case 'ban':
            if(filter_var($ip, FILTER_VALIDATE_IP)) {
                SecuritySystem::banIP($ip);
                logSecurityEvent("MANUAL_IP_BAN: $ip by admin");
            }
            break;
        case 'unban':
            if(filter_var($ip, FILTER_VALIDATE_IP)) {
                $banned = file('../logs/banned_ips.txt', FILE_IGNORE_NEW_LINES);
                $updated = array_diff($banned, [$ip]);
                file_put_contents('../logs/banned_ips.txt', implode(PHP_EOL, $updated));
                logSecurityEvent("MANUAL_IP_UNBAN: $ip by admin");
            }
            break;
    }
    header('Location: ban_manager.php');
    exit;
}

$banned_ips = Admin::getBannedIPs();
$current_ip = $_SERVER['REMOTE_ADDR'];

include __DIR__.'/../includes/header.php';
?>

<div class="admin-container">
    <div class="admin-header">
        <h2 class="glitch" data-text="IP BAN MANAGER">IP BAN MANAGER</h2>
        <div class="admin-meta">
            <span>Your IP: <?= $current_ip ?></span>
            <a href="dashboard.php" class="admin-back">BACK TO DASHBOARD</a>
        </div>
    </div>

    <div class="admin-card">
        <h3>BAN NEW IP</h3>
        <form method="GET" class="ban-form">
            <input type="hidden" name="action" value="ban">
            <div class="form-group">
                <label for="ip">IP ADDRESS:</label>
                <input type="text" id="ip" name="ip" required 
                       class="admin-input" placeholder="e.g. 192.168.1.1">
            </div>
            <button type="submit" class="admin-btn ban-btn">BAN IP</button>
        </form>
    </div>

    <div class="admin-card">
        <h3>CURRENTLY BANNED IPS (<?= count($banned_ips) ?>)</h3>
        <div class="banned-list">
            <?php if(empty($banned_ips)): ?>
                <p>No IPs currently banned.</p>
            <?php else: ?>
                <?php foreach($banned_ips as $ip): ?>
                <div class="banned-item <?= $ip === $current_ip ? 'current' : '' ?>">
                    <span class="ip-address"><?= $ip ?></span>
                    <div class="ban-actions">
                        <a href="?action=unban&ip=<?= $ip ?>" class="unban-btn">UNBAN</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__.'/../includes/footer.php'; ?>
