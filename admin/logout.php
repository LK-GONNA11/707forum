<?php
require_once __DIR__.'/../config/load_config.php';

if(Admin::isAdmin()) {
    logSecurityEvent("ADMIN_LOGOUT from ".$_SERVER['REMOTE_ADDR']);
}

$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;
?>
