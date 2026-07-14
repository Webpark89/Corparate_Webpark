<?php

declare(strict_types=1);

/**
 * Partner data access — retrieves partner logos from the database.
 */
class Partner
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Fetch all active partners, ordered by sort_order.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getActive(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, name, image_url, image_alt, category_id, sort_order, is_active, created_at, updated_at
             FROM partners
             WHERE is_active = 1
             ORDER BY sort_order ASC, created_at DESC'
        );

        return $stmt->fetchAll();
    }
}
