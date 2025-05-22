<?php
require_once 'config.php';

class Forum {
    public static function generateGuestName() {
        $adjectives = ['Dark', 'Shadow', 'Silent', 'Ghost', 'Stealth', 'Hidden'];
        $nouns = ['Visitor', 'Stranger', 'User', 'Anon', 'Guest', 'Entity'];
        return $adjectives[array_rand($adjectives)] . $nouns[array_rand($nouns)] . rand(100,999);
    }

    public static function createPost($content, $author = null) {
        global $pdo;
        $author = $author ?: self::generateGuestName();
        $stmt = $pdo->prepare("INSERT INTO posts (content, author, ip_address, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$content, $author, $_SERVER['REMOTE_ADDR']]);
    }

    public static function getPosts($limit = 50) {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT $limit");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deletePost($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

class Admin {
    public static function isAdmin() {
        return isset($_SESSION['admin_key']) && $_SESSION['admin_key'] === ADMIN_SECRET;
    }

    public static function login($key) {
        if ($key === ADMIN_SECRET) {
            $_SESSION['admin_key'] = $key;
            return true;
        }
        return false;
    }

    public static function getBannedIPs() {
        return file_exists('logs/banned_ips.txt') ? 
            array_filter(explode(PHP_EOL, file_get_contents('logs/banned_ips.txt'))) : [];
    }
}
?>
