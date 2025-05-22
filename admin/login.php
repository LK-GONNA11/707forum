<?php
require_once __DIR__.'/../config/load_config.php';

if(Admin::isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

$error = null;
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_key'])) {
    if(Admin::login($_POST['admin_key'])) {
        
        logSecurityEvent("ADMIN_LOGIN_SUCCESS from ".$_SERVER['REMOTE_ADDR']);
        header('Location: dashboard.php');
        exit;
    } else {
        logSecurityEvent("ADMIN_LOGIN_FAILED from ".$_SERVER['REMOTE_ADDR']);
        $error = "Invalid access key";
        sleep(2); 
    }
}

include __DIR__.'/../includes/header.php';
?>

<div class="admin-container">
    <div class="admin-login-box">
        <h2 class="glitch" data-text="ADMIN PORTAL">ADMIN PORTAL</h2>
        
        <?php if($error): ?>
        <div class="admin-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="admin-form">
            <div class="form-group">
                <label for="admin_key">ACCESS KEY:</label>
                <input type="password" id="admin_key" name="admin_key" required 
                       class="admin-input" autocomplete="off">
            </div>
            <button type="submit" class="admin-btn">AUTHENTICATE</button>
        </form>

        <div class="admin-security-info">
            <p>Last login: <?= file_exists('../logs/admin.log') ? 
                date('Y-m-d H:i', filemtime('../logs/admin.log')) : 'Never' ?></p>
            <p>Your IP: <?= $_SERVER['REMOTE_ADDR'] ?></p>
        </div>
    </div>
</div>

<?php include __DIR__.'/../includes/footer.php'; ?>
