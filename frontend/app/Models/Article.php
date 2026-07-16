<?php

declare(strict_types=1);

/**
 * Article data access — CRUD and listing for the article table.
 */
class Article
{
    private PDO $pdo;

    /** Shared SELECT columns for list/detail queries. */
    private const SELECT_COLUMNS = '
        a.id,
        a.priority,
        a.slug,
        a.slug_en,
        a.meta_title,
        a.meta_title_en,
        a.meta_description,
        a.meta_description_en,
        a.meta_keywords,
        a.meta_keywords_en,
        a.meta_title AS title,
        a.meta_description AS description,
        a.source_url,
        a.category_id,
        c.name AS category,
        c.slug AS category_slug,
        a.cover_image AS image_path,
        a.cover_image_alt,
        a.content,
        a.author_id,
        COALESCE(au.display_name, \'\') AS author,
        a.status,
        a.created_at,
        a.updated_at';

    private const FROM_JOIN = '
        FROM article a
        LEFT JOIN categories c ON a.category_id = c.id
        LEFT JOIN authors au ON a.author_id = au.id';

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
            " WHERE a.status = 'published' AND a.deleted_at IS NULL ORDER BY a.priority ASC, a.created_at DESC"
        );

        return $stmt->fetchAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::SELECT_COLUMNS . self::FROM_JOIN . ' WHERE a.id = ? AND a.status = \'published\' AND a.deleted_at IS NULL'
        );
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCategoryList(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, slug, name FROM categories ORDER BY name ASC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Fetch related articles in the same category, excluding the current article.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getRelatedByCategory(int $categoryId, int $excludeId, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::SELECT_COLUMNS . self::FROM_JOIN
            . ' WHERE a.category_id = ? AND a.id <> ? AND a.status = ? AND a.deleted_at IS NULL'
            . ' ORDER BY a.priority ASC, a.created_at DESC LIMIT ?'
        );
        $stmt->execute([$categoryId, $excludeId, 'published', $limit]);

        return $stmt->fetchAll();
    }

    /**
     * Top categories sorted by published article count.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getTopCategories(int $limit = 6): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.id, c.slug, c.name, COUNT(a.id) as article_count
             FROM categories c
             JOIN article a ON a.category_id = c.id
                AND a.status = ?
                AND a.deleted_at IS NULL
             GROUP BY c.id, c.slug, c.name
             HAVING COUNT(a.id) > 0
             ORDER BY COUNT(a.id) DESC, c.name ASC
             LIMIT ?'
        );
        $stmt->execute(['published', $limit]);

        return $stmt->fetchAll();
    }

    /**
     * @param int|null $author_id
     */
    public function create(
        string $title,
        string $description,
        string $content,
        int $category_id,
        ?int $author_id = null,
        ?string $slug = null,
        ?string $cover_image = null,
        ?string $cover_image_alt = null,
        ?string $meta_keywords = null
    ): bool {
        $slug = $slug ?? $this->generateSlug($title);

        $stmt = $this->pdo->prepare(
            'INSERT INTO article (meta_title, meta_description, meta_keywords, content, category_id, author_id, slug, cover_image, cover_image_alt, status, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, \'draft\', NOW(), NOW())'
        );

        return $stmt->execute([$title, $description, $meta_keywords, $content, $category_id, $author_id, $slug, $cover_image, $cover_image_alt]);
    }

    /**
     * Partial update — only non-null arguments are written.
     */
    public function update(
        int $id,
        ?string $title = null,
        ?string $description = null,
        ?string $content = null,
        ?int $category_id = null,
        ?int $author_id = null,
        ?string $cover_image = null,
        ?string $cover_image_alt = null,
        ?string $meta_keywords = null
    ): bool {
        $fields = [];
        $values = [];

        if ($title !== null) {
            $fields[] = 'meta_title = ?';
            $values[] = $title;
        }
        if ($description !== null) {
            $fields[] = 'meta_description = ?';
            $values[] = $description;
        }
        if ($meta_keywords !== null) {
            $fields[] = 'meta_keywords = ?';
            $values[] = $meta_keywords;
        }
        if ($content !== null) {
            $fields[] = 'content = ?';
            $values[] = $content;
        }
        if ($category_id !== null) {
            $fields[] = 'category_id = ?';
            $values[] = $category_id;
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
            'UPDATE article SET ' . implode(', ', $fields) . ' WHERE id = ?'
        );

        return $stmt->execute($values);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM article WHERE id = ?');

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
