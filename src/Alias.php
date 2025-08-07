<?php
namespace App;

class Alias
{
    public static function getAll(): array
    {
        $db = DB::getConnection();

        // Nur Aliase mit erlaubten source_domains abrufen
        $allowedDomains = Domains::allowed();
        $inQuery = implode(',', array_fill(0, count($allowedDomains), '?'));

        $stmt = $db->prepare("SELECT * FROM aliases WHERE source_domain IN ($inQuery) ORDER BY source_domain, source_username");
        $stmt->execute($allowedDomains);

        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array
    {
        $db = DB::getConnection();

        $stmt = $db->prepare("SELECT * FROM aliases WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $alias = $stmt->fetch();

        if (!$alias || !in_array($alias['source_domain'], Domains::allowed(), true)) {
            return null;
        }

        return $alias;
    }

    public static function create(array $data): bool
    {
        if (!in_array($data['source_domain'], Domains::allowed(), true)) {
            return false;
        }

        $db = DB::getConnection();

        $stmt = $db->prepare(<<<SQL
            INSERT INTO aliases (source_username, source_domain, destination_username, destination_domain, enabled)
            VALUES (:source_username, :source_domain, :destination_username, :destination_domain, :enabled)
        SQL);

        return $stmt->execute([
            'source_username' => $data['source_username'],
            'source_domain' => $data['source_domain'],
            'destination_username' => $data['destination_username'],
            'destination_domain' => $data['destination_domain'],
            'enabled' => $data['enabled'],
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        if (!in_array($data['source_domain'], Domains::allowed(), true)) {
            return false;
        }

        $db = DB::getConnection();

        $stmt = $db->prepare(<<<SQL
            UPDATE aliases SET
                source_username = :source_username,
                source_domain = :source_domain,
                destination_username = :destination_username,
                destination_domain = :destination_domain,
                enabled = :enabled
            WHERE id = :id
        SQL);

        return $stmt->execute([
            'source_username' => $data['source_username'],
            'source_domain' => $data['source_domain'],
            'destination_username' => $data['destination_username'],
            'destination_domain' => $data['destination_domain'],
			'enabled' => $data['enabled'],
            'id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = DB::getConnection();

        $stmt = $db->prepare("SELECT source_domain FROM aliases WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $alias = $stmt->fetch();

        if (!$alias || !in_array($alias['source_domain'], Domains::allowed(), true)) {
            return false;
        }

        $stmt = $db->prepare("DELETE FROM aliases WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
