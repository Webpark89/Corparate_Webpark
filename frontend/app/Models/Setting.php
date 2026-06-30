<?php

declare(strict_types=1);

/**
 * Key-value settings stored in the settings table.
 */
class Setting
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Fetch a single setting value by key.
     */
    public function getByKey(string $key, mixed $default = null): mixed
    {
        $stmt = $this->pdo->prepare('SELECT config_value FROM settings WHERE config_key = ? LIMIT 1');
        $stmt->execute([$key]);
        $row = $stmt->fetch();

        if ($row === false) {
            return $default;
        }

        return $row['config_value'];
    }

    /**
     * Fetch multiple settings in one query.
     *
     * @param array<int, string> $keys
     * @return array<string, mixed>
     */
    public function getByKeys(array $keys): array
    {
        if ($keys === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($keys), '?'));
        $stmt = $this->pdo->prepare("SELECT config_key, config_value FROM settings WHERE config_key IN ({$placeholders})");
        $stmt->execute(array_values($keys));

        $items = [];
        while ($row = $stmt->fetch()) {
            $items[$row['config_key']] = $row['config_value'];
        }

        return $items;
    }

    /**
     * Fetch all settings belonging to a logical group.
     *
     * @return array<string, mixed>
     */
    public function getByGroup(string $group): array
    {
        $stmt = $this->pdo->prepare('SELECT config_key, config_value FROM settings WHERE `group` = ? ORDER BY config_key');
        $stmt->execute([$group]);

        $items = [];
        while ($row = $stmt->fetch()) {
            $items[$row['config_key']] = $row['config_value'];
        }

        return $items;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT config_key, config_value FROM settings ORDER BY `group`, config_key');

        $items = [];
        while ($row = $stmt->fetch()) {
            $items[$row['config_key']] = $row['config_value'];
        }

        return $items;
    }

    /**
     * Insert or update a setting row.
     */
    public function save(
        string $key,
        string $value,
        string $group = 'general',
        ?string $description = null,
        bool $isProtected = false
    ): bool {
        $existing = $this->getByKey($key, null);

        if ($existing === null) {
            $stmt = $this->pdo->prepare(
                'INSERT INTO settings (config_key, config_value, `group`, description, is_protected) VALUES (?, ?, ?, ?, ?)'
            );

            return $stmt->execute([$key, $value, $group, $description, $isProtected ? 1 : 0]);
        }

        $stmt = $this->pdo->prepare(
            'UPDATE settings SET config_value = ?, `group` = ?, description = ?, is_protected = ?, updated_at = NOW() WHERE config_key = ?'
        );

        return $stmt->execute([$value, $group, $description, $isProtected ? 1 : 0, $key]);
    }

    /**
     * Delete a non-protected setting.
     */
    public function delete(string $key): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM settings WHERE config_key = ? AND is_protected = 0');

        return $stmt->execute([$key]);
    }
}
