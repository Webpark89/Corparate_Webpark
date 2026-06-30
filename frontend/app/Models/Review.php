<?php

declare(strict_types=1);

/**
 * Review/testimonial data access for the public homepage.
 */
class Review
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Active reviews ordered for display on the homepage carousel.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getActive(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, reviewer_name, rating, content, reviewer_position, reviewer_company, reviewer_image_url, sort_order, is_active, created_at, updated_at
             FROM `review`
             WHERE is_active = 1
             ORDER BY sort_order ASC, created_at DESC'
        );

        return $stmt->fetchAll();
    }
}
