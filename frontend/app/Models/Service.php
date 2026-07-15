<?php

declare(strict_types=1);

/**
 * Service catalog and feature data access.
 */
class Service
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Active services for listing pages (excludes ERP — handled separately on homepage).
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAllActive(): array
    {
        $stmt = $this->pdo->query(
            'SELECT
                id,
                slug,
                title,
                title AS home_title,
                summary,
                details_json,
                image,
                is_active,
                created_at,
                updated_at
            FROM service
            WHERE is_active = 1
            AND slug != \'erp\'
            ORDER BY id ASC'
        );

        $services = [];

        while ($row = $stmt->fetch()) {
            $services[] = $this->hydrateServiceRow($row);
        }

        return $services;
    }

    /**
     * @return array<string, mixed>|false
     */
    public function getBySlug(string $slug): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                id,
                slug,
                title,
                title AS home_title,
                summary,
                details_json,
                image,
                is_active,
                created_at,
                updated_at
            FROM service
            WHERE slug = ?
            AND is_active = 1
            LIMIT 1'
        );

        $stmt->execute([$slug]);

        $row = $stmt->fetch();

        if ($row === false) {
            return false;
        }

        return $this->hydrateServiceRow($row);
    }

    /**
     * @return array<string, mixed>|false
     */
    public function getFeatureBySlugs(string $serviceSlug, string $featureSlug): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                sf.*,
                s.id AS service_id,
                s.slug AS service_slug,
                s.title AS service_title,
                s.summary AS service_summary,
                s.image AS service_image
            FROM service_features sf
            JOIN service s ON sf.service_id = s.id
            WHERE s.slug = ?
            AND sf.slug = ?
            AND s.is_active = 1
            LIMIT 1'
        );

        $stmt->execute([$serviceSlug, $featureSlug]);

        $row = $stmt->fetch();

        return $row === false ? false : $row;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getFeaturesByServiceId(int $serviceId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                id,
                title,
                slug,
                summary,
                image,
                created_at,
                updated_at
            FROM service_features
            WHERE service_id = ?
            ORDER BY id ASC'
        );

        $stmt->execute([$serviceId]);

        return $stmt->fetchAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllErpModules(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM erp_modules WHERE is_active = 1 ORDER BY sort_order ASC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Legacy fallback when erp_module_features table is absent.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getErpModuleFeatures(int $moduleId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM erp_modules WHERE id = ? AND is_active = 1 LIMIT 1'
        );

        $stmt->execute([$moduleId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? [$row] : [];
    }

    /**
     * Decode details_json and normalize nested items/features for views.
     *
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function hydrateServiceRow(array $row): array
    {
        $details = [];

        if (!empty($row['details_json'])) {
            $decoded = json_decode((string) $row['details_json'], true);

            if (is_array($decoded)) {
                $details = $decoded;
            }
        }

        $items = $this->extractItemsFromDetails($details);

        // Fetch features from service_features table
        $features = [];
        if (isset($row['id'])) {
            $stmt = $this->pdo->prepare('SELECT title FROM service_features WHERE service_id = ? ORDER BY id ASC');
            $stmt->execute([(int)$row['id']]);
            $features = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        // Fallback to legacy details_json if service_features is empty (migration)
        if (empty($features) && !empty($details['features'])) {
            $features = $details['features'];
        }

        return [
            'id' => (int) $row['id'],
            'slug' => (string) $row['slug'],
            'title' => (string) $row['title'],
            'home_title' => (string) ($row['home_title'] ?? $row['title']),
            'summary' => (string) ($row['summary'] ?? ''),
            'description' => (string) ($row['summary'] ?? ''),
            'image' => (string) ($row['image'] ?? ''),
            'details' => $details,
            'features' => $features,
            'items' => $items,
            'topics' => !empty($details['topics']) && is_array($details['topics'])
                ? $details['topics']
                : [],
            'created_at' => (string) ($row['created_at'] ?? ''),
            'updated_at' => (string) ($row['updated_at'] ?? ''),
        ];
    }

    /**
     * @param array<string, mixed> $details
     * @return array<int, array{slug: string, title: string}>
     */
    private function extractItemsFromDetails(array $details): array
    {
        $items = [];

        if (!empty($details['items']) && is_array($details['items'])) {
            foreach ($details['items'] as $item) {
                if (is_array($item)) {
                    $title = (string) ($item['title'] ?? $item['label'] ?? '');
                    $slug = (string) ($item['slug'] ?? $this->slugFromTitle($title));

                    $items[] = [
                        'slug' => $slug,
                        'title' => $title,
                    ];
                } else {
                    $title = (string) $item;

                    $items[] = [
                        'slug' => $this->slugFromTitle($title),
                        'title' => $title,
                    ];
                }
            }
        } elseif (!empty($details['features']) && is_array($details['features'])) {
            foreach ($details['features'] as $feature) {
                $title = (string) $feature;

                $items[] = [
                    'slug' => $this->slugFromTitle($title),
                    'title' => $title,
                ];
            }
        }

        return $items;
    }

    private function slugFromTitle(string $title): string
    {
        return strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $title) ?? '', '-'));
    }
}
