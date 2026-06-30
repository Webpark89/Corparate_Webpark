<?php

declare(strict_types=1);

/**
 * Portfolio/project data access — CRUD and category filtering.
 */
class Portfolio
{
    private PDO $pdo;

    private const SELECT_COLUMNS = '
        p.id,
        p.meta_title,
        p.meta_description,
        p.meta_keywords,
        p.meta_title AS title,
        p.meta_description AS summary,
        p.client_name,
        p.category_id,
        c.name AS category,
        p.description,
        p.tech_stack,
        p.author_id,
        COALESCE(au.display_name, \'\') AS author,
        p.status,
        p.slug,
        p.cover_image AS image_path,
        p.cover_image_alt,
        p.created_at,
        p.updated_at';

    private const FROM_JOIN = '
        FROM portfolio p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN authors au ON p.author_id = au.id';

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->getPublished();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPublished(): array
    {
        $stmt = $this->pdo->query(
            'SELECT ' . self::SELECT_COLUMNS . self::FROM_JOIN .
            " WHERE p.status = 'published' AND p.deleted_at IS NULL ORDER BY p.created_at DESC"
        );

        return $stmt->fetchAll();
    }

    /**
     * @return array<string, mixed>|false
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::SELECT_COLUMNS . self::FROM_JOIN . ' WHERE p.id = ? AND p.status = \'published\' AND p.deleted_at IS NULL'
        );
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getByCategoryId(int $categoryId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::SELECT_COLUMNS . self::FROM_JOIN
            . ' WHERE p.category_id = ? ORDER BY p.created_at DESC'
        );
        $stmt->execute([$categoryId]);

        return $stmt->fetchAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getByCategoryName(string $categoryName): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::SELECT_COLUMNS . self::FROM_JOIN
            . ' WHERE LOWER(TRIM(c.name)) = LOWER(TRIM(?)) ORDER BY p.created_at DESC'
        );
        $stmt->execute([$categoryName]);

        return $stmt->fetchAll();
    }

    public function create(
        string $title,
        string $description,
        int $category_id,
        ?string $summary = null,
        ?string $tech_stack = null,
        ?int $author_id = null,
        ?string $slug = null,
        ?string $cover_image = null,
        ?string $cover_image_alt = null,
        ?string $client_name = null,
        ?string $meta_keywords = null
    ): bool {
        $slug = $slug ?? $this->generateSlug($title);

        $stmt = $this->pdo->prepare(
            'INSERT INTO portfolio (meta_title, client_name, category_id, meta_keywords, meta_description, description, tech_stack, author_id, status, slug, cover_image, cover_image_alt, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, \'draft\', ?, ?, ?, NOW(), NOW())'
        );

        return $stmt->execute([$title, $client_name, $category_id, $meta_keywords, $summary, $description, $tech_stack, $author_id, $slug, $cover_image, $cover_image_alt]);
    }

    public function update(
        int $id,
        ?string $title = null,
        ?string $description = null,
        ?int $category_id = null,
        ?string $summary = null,
        ?string $tech_stack = null,
        ?int $author_id = null,
        ?string $cover_image = null,
        ?string $cover_image_alt = null,
        ?string $client_name = null,
        ?string $meta_keywords = null
    ): bool {
        $fields = [];
        $values = [];

        if ($title !== null) {
            $fields[] = 'meta_title = ?';
            $values[] = $title;
        }
        if ($client_name !== null) {
            $fields[] = 'client_name = ?';
            $values[] = $client_name;
        }
        if ($category_id !== null) {
            $fields[] = 'category_id = ?';
            $values[] = $category_id;
        }
        if ($meta_keywords !== null) {
            $fields[] = 'meta_keywords = ?';
            $values[] = $meta_keywords;
        }
        if ($summary !== null) {
            $fields[] = 'meta_description = ?';
            $values[] = $summary;
        }
        if ($description !== null) {
            $fields[] = 'description = ?';
            $values[] = $description;
        }
        if ($tech_stack !== null) {
            $fields[] = 'tech_stack = ?';
            $values[] = $tech_stack;
        }
        if ($author_id !== null) {
            $fields[] = 'author_id = ?';
            $values[] = $author_id;
        }
        if ($cover_image !== null) {
            $fields[] = 'cover_image = ?';
            $values[] = $cover_image;
        }
        if ($cover_image_alt !== null) {
            $fields[] = 'cover_image_alt = ?';
            $values[] = $cover_image_alt;
        }

        if ($fields === []) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';
        $values[] = $id;

        $stmt = $this->pdo->prepare(
            'UPDATE portfolio SET ' . implode(', ', $fields) . ' WHERE id = ?'
        );

        return $stmt->execute($values);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM portfolio WHERE id = ?');

        return $stmt->execute([$id]);
    }

    private function generateSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        $slug = trim($slug, '-');

        return $slug;
    }
}
