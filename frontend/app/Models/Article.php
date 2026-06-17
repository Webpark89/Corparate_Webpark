<?php

declare(strict_types=1);

class Article
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Get all articles
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT 
                a.id,
                a.slug,
                a.meta_title,
                a.meta_description,
                a.meta_keywords,
                a.meta_title AS title,
                a.meta_description AS description,
                a.category_id,
                c.name as category,
                a.cover_image as image_path,
                a.cover_image_alt,
                a.content,
                a.author_id,
                COALESCE(au.display_name, '') as author,
                a.status,
                a.created_at,
                a.updated_at
            FROM article a
            LEFT JOIN categories c ON a.category_id = c.id
            LEFT JOIN authors au ON a.author_id = au.id
            ORDER BY a.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Get published articles only
     */
    public function getPublished(): array
    {
        $stmt = $this->pdo->query(
            "SELECT 
                a.id,
                a.slug,
                a.meta_title,
                a.meta_description,
                a.meta_keywords,
                a.meta_title AS title,
                a.meta_description AS description,
                a.category_id,
                c.name as category,
                a.cover_image as image_path,
                a.cover_image_alt,
                a.content,
                a.author_id,
                COALESCE(au.display_name, '') as author,
                a.status,
                a.created_at,
                a.updated_at
            FROM article a
            LEFT JOIN categories c ON a.category_id = c.id
            LEFT JOIN authors au ON a.author_id = au.id
            WHERE a.status IN ('publish', 'published')
            ORDER BY a.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Get article by ID
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT 
                a.id,
                a.slug,
                a.meta_title,
                a.meta_description,
                a.meta_keywords,
                a.meta_title AS title,
                a.meta_description AS description,
                a.category_id,
                c.name as category,
                a.cover_image as image_path,
                a.cover_image_alt,
                a.content,
                a.author_id,
                COALESCE(au.display_name, '') as author,
                a.status,
                a.created_at,
                a.updated_at
            FROM article a
            LEFT JOIN categories c ON a.category_id = c.id
            LEFT JOIN authors au ON a.author_id = au.id
            WHERE a.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create new article
     */
    public function create(string $title, string $description, string $content, int $category_id, ?int $author_id = null, ?string $slug = null, ?string $cover_image = null, ?string $cover_image_alt = null, ?string $meta_keywords = null): bool
    {
        $slug = $slug ?? $this->generateSlug($title);

        $stmt = $this->pdo->prepare(
            "INSERT INTO article (meta_title, meta_description, meta_keywords, content, category_id, author_id, slug, cover_image, cover_image_alt, status, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft', NOW(), NOW())"
        );
        return $stmt->execute([$title, $description, $meta_keywords, $content, $category_id, $author_id, $slug, $cover_image, $cover_image_alt]);
    }

    /**
     * Update article
     */
    public function update(int $id, ?string $title = null, ?string $description = null, ?string $content = null, ?int $category_id = null, ?int $author_id = null, ?string $cover_image = null, ?string $cover_image_alt = null, ?string $meta_keywords = null): bool
    {
        $fields = [];
        $values = [];

        if ($title !== null) {
            $fields[] = "meta_title = ?";
            $values[] = $title;
        }
        if ($description !== null) {
            $fields[] = "meta_description = ?";
            $values[] = $description;
        }
        if ($meta_keywords !== null) {
            $fields[] = "meta_keywords = ?";
            $values[] = $meta_keywords;
        }
        if ($content !== null) {
            $fields[] = "content = ?";
            $values[] = $content;
        }
        if ($category_id !== null) {
            $fields[] = "category_id = ?";
            $values[] = $category_id;
        }
        if ($author_id !== null) {
            $fields[] = "author_id = ?";
            $values[] = $author_id;
        }
        if ($cover_image !== null) {
            $fields[] = "cover_image = ?";
            $values[] = $cover_image;
        }
        if ($cover_image_alt !== null) {
            $fields[] = "cover_image_alt = ?";
            $values[] = $cover_image_alt;
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = "updated_at = NOW()";
        $values[] = $id;

        $stmt = $this->pdo->prepare(
            "UPDATE article SET " . implode(", ", $fields) . " WHERE id = ?"
        );
        return $stmt->execute($values);
    }

    /**
     * Delete article
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM article WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Generate URL slug from title
     */
    private function generateSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
