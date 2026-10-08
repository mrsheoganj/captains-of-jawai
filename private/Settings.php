<?php
namespace App;

require_once __DIR__ . '/config/Database.php';

use App\Config\Database;
use PDO;
use PDOException;

class Settings {
    public static function get($key, $default = '') {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ? LIMIT 1");
            $stmt->execute([$key]);
            $result = $stmt->fetch();
            if ($result) {
                return $result['setting_value'];
            }
        } catch (PDOException $e) {
            // DB might not be connected yet
        }
        return $default;
    }

    public static function set($key, $value, $group = 'general') {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO site_settings (setting_key, setting_value, setting_group) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = ?, setting_group = ?
            ");
            $stmt->execute([$key, $value, $group, $value, $group]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
