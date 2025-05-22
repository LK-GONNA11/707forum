<?php
require_once __DIR__.'/../config/load_config.php';

if(Admin::isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

header('Location: login.php');
exit;
?>
