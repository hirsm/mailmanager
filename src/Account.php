<?php

namespace App;

use PDO;

class Account
{
    public static function find(int $id): ?array
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM accounts WHERE id = ?");
        $stmt->execute([$id]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        return $account ?: null;
    }
	
	public static function findAll(): ?array
    {
        $db = DB::getConnection();
		
		// Nur Aliase mit erlaubten source_domains abrufen
        $allowedDomains = Domains::allowed();
        $inQuery = implode(',', array_fill(0, count($allowedDomains), '?'));
        $stmt = $db->prepare("SELECT * FROM accounts WHERE domain IN ($inQuery) ORDER BY domain, username");
        $stmt->execute($allowedDomains);
        $account = $stmt->fetchAll();
        return $account ?: null;
    }

    public static function update(int $id, array $data): bool
	{
		$db = DB::getConnection();

		$fields = [
			'display_name' => $data['display_name'],
			'quota' => $data['quota'],
			'enabled' => $data['enabled'] ?? 0,
			'sendonly' => $data['sendonly'] ?? 0,
		];

		$sqlParts = [];
		foreach ($fields as $field => $value) {
			$sqlParts[] = "$field = :$field";
		}

		// Optional Passwort hinzufügen
		if (isset($data['password'])) {
			$sqlParts[] = "password = :password";
			$fields['password'] = $data['password'];
		}

		$sql = "UPDATE accounts SET " . implode(", ", $sqlParts) . " WHERE id = :id";

		$stmt = $db->prepare($sql);
		$fields['id'] = $id;

		return $stmt->execute($fields);
	}
	
	public static function delete(int $id): bool
	{
		$db = DB::getConnection();
		
		$stmt = $db->prepare('DELETE from accounts WHERE id = ?');
		return $stmt->execute([$id]);
	}

}