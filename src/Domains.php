<?php
namespace App;

use PDO;

class Domains {
    public static function allowed(): array {
        $raw = $_ENV['ALLOWED_DOMAINS'] ?? '';
        if (trim($raw) === '*') {
            return self::getAll();
        }

        return array_filter(array_map('trim', explode(',', $raw)));
    }

    public static function isAllowed(string $domain): bool {
        return in_array($domain, self::allowed(), true);
    }
	
	public static function getAll(): array
    {
        $db = DB::getConnection();

        $stmt = $db->query('SELECT domain FROM domains ORDER BY domain ASC');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
	
	public static function getAllFull(): array
    {
        $db = DB::getConnection();

        $stmt = $db->query('SELECT * FROM domains ORDER BY domain ASC');
        return $stmt->fetchAll();
    }
	
	public static function getById(int $id): ?array
    {
        $stmt = DB::getConnection()->prepare("SELECT * FROM domains WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function create(string $domain): bool
    {
        $stmt = DB::getConnection()->prepare("INSERT INTO domains (domain) VALUES (?)");
        return $stmt->execute([$domain]);
    }

    public static function delete(int $id): bool
    {
        $stmt = DB::getConnection()->prepare("DELETE FROM domains WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

