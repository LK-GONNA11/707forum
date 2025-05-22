<?php
require_once 'config.php';

class SecuritySystem {
    private static $threat_level = 0;

    public static function checkForThreats() {
        self::checkBannedIP();
        self::detectLawEnforcement();
        self::analyzeBehavior();
        
        if(SLEEP_MODE) {
            self::activateSleepMode();
        }
    }

    private static function checkBannedIP() {
        $banned_ips = Admin::getBannedIPs();
        if(in_array($_SERVER['REMOTE_ADDR'], $banned_ips)) {
            header('HTTP/1.1 403 Forbidden');
            die("Access Denied");
        }
    }

    private static function detectLawEnforcement() {
        global $law_enforcement_patterns;
        
        foreach($_REQUEST as $input) {
            if(is_string($input)) {
                foreach($law_enforcement_patterns as $pattern) {
                    if(stripos($input, $pattern) !== false) {
                        self::$threat_level += 10;
                        logSecurityEvent("LEO keyword detected: $pattern");
                    }
                }
            }
        }
    }

    private static function analyzeBehavior() {
        // Detect rapid requests
        if(isset($_SESSION['last_request'])) {
            $time_diff = microtime(true) - $_SESSION['last_request'];
            if($time_diff < 0.5) { // Less than 500ms between requests
                self::$threat_level += 5;
                logSecurityEvent("Rapid requests detected");
            }
        }
        $_SESSION['last_request'] = microtime(true);

        // Detect suspicious URLs
        $suspicious_paths = ['wp-admin', 'phpmyadmin', 'admin', 'config', '.env'];
        foreach($suspicious_paths as $path) {
            if(strpos($_SERVER['REQUEST_URI'], $path) !== false) {
                self::$threat_level += 3;
                logSecurityEvent("Suspicious path accessed: $path");
            }
        }

        // Take action based on threat level
        if(self::$threat_level > 15) {
            self::banIP($_SERVER['REMOTE_ADDR']);
        } elseif(self::$threat_level > 10) {
            self::delayResponse();
        } elseif(self::$threat_level > 5) {
            $_SESSION['suspicious'] = true;
            header('Location: hold.php');
            exit;
        }
    }

    public static function banIP($ip) {
        file_put_contents('logs/banned_ips.txt', $ip.PHP_EOL, FILE_APPEND);
        logSecurityEvent("IP Banned: $ip");
    }

    private static function delayResponse() {
        sleep(SUSPICIOUS_DELAY);
    }

    public static function activateSleepMode() {
        if(SLEEP_MODE) {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            include 'sleep_mode.html';
            exit;
        }
    }
}
?>
