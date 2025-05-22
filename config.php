<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'forum707');
define('DB_USER', 'forum_user');
define('DB_PASS', 'password0');
define('ADMIN_SECRET', 'admin@998');

define('SITE_NAME', '707Forum');
define('SITE_DESC', 'Uncensored Free Speech Platform');
define('MAX_POST_LENGTH', 5000);

define('SECURITY_MODE', true);
define('SLEEP_MODE', false);
define('BAN_THRESHOLD', 3);
define('SUSPICIOUS_DELAY', 5); 

session_start();

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$law_enforcement_patterns = [
    'police', 'fbi', 'cia', 'interpol', 'europol', 
    'law enforcement', 'warrant', 'subpoena', 'investigation'
];

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function logSecurityEvent($event) {
    $log = date('[Y-m-d H:i:s]') . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . $event . PHP_EOL;
    file_put_contents('logs/security.log', $log, FILE_APPEND);
}
?>
