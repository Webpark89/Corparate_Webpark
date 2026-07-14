<?php

declare(strict_types=1);

/**
 * Primary frontend controller — handles all public page routes.
 *
 * Data loading stays here; persistence lives in Models; presentation in views.
 */
class HomeController
{
    private Controller $renderer;

    public function __construct()
    {
        $this->renderer = new Controller();
    }

    private function getServiceModel(): Service
    {
        return new Service();
    }

    /** Homepage with services, insights, reviews, and portfolio highlights. */
    public function index(): void
    {
        $rawTab = $_GET['tab'] ?? 'technology';
        $tab = in_array($rawTab, ['technology', 'business'], true) ? $rawTab : 'technology';

        // Load recent articles from DB and group into insight tabs (fallback to empty arrays)
        $articleModel = new Article();
        $insights = ['technology' => [], 'business' => []];
        $latestArticles = [];
        $reviews = [];

        try {
            $rows = $articleModel->getPublished();
            $lang = getCurrentLang();

            // Extract 3 latest articles
            $latestRows = array_slice($rows, 0, 3);
            foreach ($latestRows as $row) {
                $content = trim((string) ($row['content'] ?? ''));
                $summary = $content === '' ? '' : (mb_strimwidth(get_article_summary_text($content, $lang), 0, 140, '...'));

                $latestArticles[] = [
                    'id' => (int) ($row['id'] ?? 0),
                    'title' => (string) ($row['title'] ?? ''),
                    'summary' => $summary,
                    'description' => $summary,
                    'category' => (string) ($row['category'] ?? 'Knowledge'),
                    'date' => (string) ($row['created_at'] ?? ''),
                    'image_path' => (string) ($row['image_path'] ?? $row['cover_image'] ?? ''),
                    'image' => (string) ($row['image_path'] ?? $row['cover_image'] ?? ''),
                ];
            }

            foreach ($rows as $row) {
                $cat = strtolower(trim((string) ($row['category'] ?? '')));
                if (!in_array($cat, ['technology', 'business'], true)) {
                    $cat = 'business';
                }

                $content = trim((string) ($row['content'] ?? ''));
                $summary = $content === '' ? '' : (mb_strimwidth(get_article_summary_text($content, $lang), 0, 140, '...'));

                $insights[$cat][] = [
                    'id' => (int) ($row['id'] ?? 0),
                    'tag' => $cat,
                    'title' => (string) ($row['title'] ?? ''),
                    'description' => $summary,
                    'date' => (string) ($row['created_at'] ?? ''),
                    'image' => (string) ($row['image_path'] ?? ''),
                ];
            }
        } catch (Throwable $e) {
            // keep default empty insights on error
        }

        try {
            $reviews = (new Review())->getActive();
        } catch (Throwable $e) {
            $reviews = [];
        }

        // Load portfolios for homepage
        $displayPortfolios = [];
        try {
            $portfolioModel = new Portfolio();
            $portfolioRows = $portfolioModel->getPublished();
            $displayPortfolios = array_map(static function (array $row): array {
                return [
                    'id' => (int) ($row['id'] ?? 0),
                    'title' => (string) ($row['title'] ?? ''),
                    'description' => (string) ($row['description'] ?? ''),
                    'category' => (string) ($row['category'] ?? 'Portfolio'),
                    'image_path' => (string) ($row['image_path'] ?? $row['cover_image'] ?? ''),
                    'cover_image' => (string) ($row['cover_image'] ?? $row['image_path'] ?? ''),
                    'items' => (string) ($row['items'] ?? ''),
                ];
            }, array_slice($portfolioRows, 0, 4));
        } catch (Throwable $e) {
            $displayPortfolios = [];
        }

        // Build simplified services for homepage using the full catalog (first 4)
        $serviceModel = $this->getServiceModel();
        $catalog = $serviceModel->getAllActive();
        $homeServices = array_map(static function (array $svc): array {
            $firstTopic = null;
            if (!empty($svc['topics']) && is_array($svc['topics'])) {
                $firstKey = array_key_first($svc['topics']);
                if ($firstKey !== null && isset($svc['topics'][$firstKey]['image'])) {
                    $firstTopic = $svc['topics'][$firstKey];
                }
            }

            $image = $firstTopic['image'] ?? asset_url('images/default-service.png');

            $items = [];
            if (!empty($svc['items']) && is_array($svc['items'])) {
                $items = array_map(static fn($it) => is_array($it) ? ($it['title'] ?? '') : (string) $it, $svc['items']);
            }

            return [
                'slug' => $svc['slug'] ?? '',
                'title' => $svc['home_title'] ?? $svc['title'] ?? '',
                'description' => $svc['description'] ?? '',
                'image' => $image,
                'items' => $items,
            ];
        }, array_slice($catalog, 0, 4));

        $erpService = [
            'slug' => 'erp',
            'title' => 'ERP / ERM',
            'description' => 'ระบบบริหารทรัพยากรองค์กร ครอบคลุมการผลิต การขาย การเงิน และทรัพยากรบุคคล',
            'image' => asset_url('images/services/default-service-1.png'),
            'items' => [
                'Production Management',
                'Purchase & Inventory',
                'Sales & CRM',
                'Accounting & Finance',
                'Human Resources',
                'BI & Analytics',
            ],
        ];

        // Add ERP as the first card if it's not already present
        $exists = false;
        foreach ($homeServices as $svc) {
            if (($svc['slug'] ?? '') === 'erp') {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            array_unshift($homeServices, $erpService);
        }

        $partners = [];
        try {
            $partners = (new Partner())->getActive();
        } catch (Throwable $e) {
            $partners = [];
        }

        $this->view('pages/home.php', array_merge($this->sharedData('home', 'Home'), [
            'activeTab' => $tab,
            'services' => $homeServices,
            'insights' => $insights,
            'latestArticles' => $latestArticles,
            'reviews' => $reviews,
            'displayPortfolios' => $displayPortfolios,
            'partners' => $partners,
        ]));
    }

    public function services(): void
    {
        $serviceModel = $this->getServiceModel();
        $services = $serviceModel->getAllActive();

        $this->view('pages/services.php', array_merge($this->sharedData('services', 'Services'), [
            'services' => $services,
            'introService' => $services[0] ?? null,
            'strengths' => [
                ['title' => 'Engineering-Led', 'description' => 'ทีมวิศวกรที่ออกแบบระบบจริง ไม่ใช่แค่บริการให้คำปรึกษา'],
                ['title' => 'Measurable Impact', 'description' => 'ทุกโครงการมีตัวชี้วัดทางธุรกิจชัดเจน วัด ROI ได้'],
                ['title' => 'Enterprise-Ready', 'description' => 'มาตรฐาน Security และ Scalability พร้อมใช้งานจริง'],
                ['title' => 'Long-term Partnership', 'description' => 'เราดูแลระบบของคุณตลอด Lifecycle ไม่ใช่แค่ส่งมอบ'],
            ],
            'process' => [
                ['number' => '01', 'title' => 'Discover', 'description' => 'ทำความเข้าใจธุรกิจ เป้าหมาย และข้อจำกัดของคุณ'],
                ['number' => '02', 'title' => 'Design', 'description' => 'ออกแบบโซลูชันและสถาปัตยกรรมระบบที่เหมาะกับองค์กร'],
                ['number' => '03', 'title' => 'Develop', 'description' => 'พัฒนาด้วยมาตรฐานวิศวกรรม ทดสอบ และส่งมอบเป็นระยะ'],
                ['number' => '04', 'title' => 'Deliver & Scale', 'description' => 'ปรับปรุงต่อเนื่อง ขยายระบบให้เติบโตไปกับธุรกิจ'],
            ],
        ]));
    }

    public function serviceDetail(): void
    {
        $serviceSlug = (string) ($_GET['service'] ?? '');
        $topicSlug = (string) ($_GET['topic'] ?? '');

        if ($serviceSlug === '') {
            $this->notFound();
            return;
        }

        $serviceModel = $this->getServiceModel();
        $service = $serviceModel->getBySlug($serviceSlug);

        if ($service === false) {
            $this->notFound();
            return;
        }

        $features = $serviceModel->getFeaturesByServiceId($service['id']);

        if (empty($features)) {
            $this->notFound();
            return;
        }


        if ($topicSlug === '') {
            $topicSlug = (string)($features[0]['slug'] ?? '');
        }

        $this->serviceFeature($serviceSlug, $topicSlug);
    }

    public function serviceFeature(string $serviceSlug, string $featureSlug): void
    {
        $serviceSlug = trim($serviceSlug);
        $featureSlug = trim($featureSlug);

        if ($serviceSlug === '' || $featureSlug === '') {
            $this->notFound();
            return;
        }

        $serviceModel = $this->getServiceModel();
        $service = $serviceModel->getBySlug($serviceSlug);

        if ($service === false) {
            $this->notFound();
            return;
        }

        $feature = $serviceModel->getFeatureBySlugs($serviceSlug, $featureSlug);

        if ($feature === false) {
            $this->notFound();
            return;
        }

        $featureList = $serviceModel->getFeaturesByServiceId($service['id']);
        $topicList = array_map(static function (array $row): array {
            return [
                'slug' => (string) ($row['slug'] ?? ''),
                'title' => (string) ($row['title'] ?? ''),
            ];
        }, $featureList);

        $selectedTopic = [
            'slug' => $featureSlug,
            'title' => (string) ($feature['title'] ?? $featureSlug),
            'kicker' => (string) ($service['title'] ?? ''),
            'image' => (string) ($feature['image'] ?? $service['image'] ?? ''),
            'summary' => (string) ($feature['summary'] ?? ''),

            // ส่ง raw HTML content ไปให้หน้า View ตรงๆ ในชื่อคีย์ 'content'
            'content' => (string) ($feature['content'] ?? ''),

            'body' => [], // ปล่อยว่างไว้เผื่อป้องกัน Error จากหน้า View ตัวอื่นที่อาจยังพึ่งพาคีย์นี้
            'highlights' => [],
        ];

        $this->view('pages/service-detail.php', array_merge($this->sharedData('services', $selectedTopic['title'] ?: 'Service Feature'), [
            'service' => $service,
            'topic' => $selectedTopic,
            'topicList' => $topicList,
        ]));
    }

    /**
     * @deprecated Unused by current views — kept for backward compatibility.
     * @return array<int, string>
     */
    private function splitContentToParagraphs(string $content): array
    {
        $clean = trim($content);

        if ($clean === '') {
            return [];
        }

        $paragraphs = preg_split('/\r\n|\r|\n{2,}/', $clean);

        return array_values(array_filter(
            array_map(static fn($item): string => trim($item), $paragraphs),
            static fn(string $text): bool => $text !== ''
        ));
    }

    public function article(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $articleModel = new Article();

        if ($id > 0) {
            $row = $articleModel->getById($id);
            $status = strtolower(trim((string) ($row['status'] ?? '')));

            if ($row === false || $status === 'draft') {
                $this->notFound();
                return;
            }

            $descText = trim((string) ($row['meta_description'] ?? ''));
            if ($descText === '') {
                $lang = getCurrentLang();
                $descText = get_article_summary_text((string) ($row['content'] ?? ''), $lang);
                if ($descText !== '') {
                    $descText = mb_strimwidth($descText, 0, 180, '...');
                }
            }
            $article = [
                'id' => (int) ($row['id'] ?? 0),
                'title' => (string) ($row['title'] ?? ''),
                'meta_title' => (string) ($row['meta_title'] ?? ''),
                'meta_description' => $descText,
                'summary' => $descText !== '' ? mb_strimwidth($descText, 0, 140, '...') : '',
                'meta_keywords' => (string) ($row['meta_keywords'] ?? ''),
                'category' => (string) ($row['category'] ?? 'General'),
                'image_path' => (string) ($row['image_path'] ?? ''),
                'cover_image' => (string) ($row['image_path'] ?? ''),
                'cover_image_alt' => (string) ($row['cover_image_alt'] ?? ''),
                'content' => (string) ($row['content'] ?? ''),
                'author' => (string) ($row['author'] ?? ''),
                'created_at' => (string) ($row['created_at'] ?? ''),
            ];

            $relatedArticles = [];
            try {
                $relatedArticles = $articleModel->getRelatedByCategory(
                    (int) ($row['category_id'] ?? 0),
                    (int) ($row['id'] ?? 0),
                    3
                );
            } catch (Throwable $e) {
                $relatedArticles = [];
            }

            $popularCategories = [];
            try {
                $popularCategories = $articleModel->getTopCategories(6);
            } catch (Throwable $e) {
                $popularCategories = [];
            }

            $this->view('pages/article-detail.php', array_merge($this->sharedData('article', $article['title'] ?: 'Article'), [
                'article' => $article,
                'relatedArticles' => $relatedArticles,
                'popularCategories' => $popularCategories,
            ]));

            return;
        }

        $articles = [];
        $articleCategorySlugs = [];

        try {
            $rows = $articleModel->getPublished();
            $lang = getCurrentLang();
            $articles = array_map(static function (array $row) use ($lang): array {
                $description = trim((string) ($row['description'] ?? $row['meta_description'] ?? ''));
                $content = trim((string) ($row['content'] ?? ''));
                if ($description !== '') {
                    $summary = mb_strimwidth(strip_tags($description), 0, 140, '...');
                } else {
                    $summary = mb_strimwidth(get_article_summary_text($content, $lang), 0, 140, '...');
                }

                return [
                    'id' => (int) ($row['id'] ?? 0),
                    'title' => (string) ($row['title'] ?? ''),
                    'category_name' => (string) ($row['category'] ?? 'General'),
                    'category_slug' => (string) ($row['category_slug'] ?? ''),
                    'image_path' => (string) ($row['image_path'] ?? ''),
                    'summary' => $summary,
                    'content' => $content,
                    'author' => (string) ($row['author'] ?? ''),
                    'created_at' => (string) ($row['created_at'] ?? ''),
                ];
            }, $rows);

            $articleCategorySlugs = array_values(array_filter(array_unique(array_map(
                static fn(array $article): string => trim((string) ($article['category_slug'] ?? '')),
                $articles
            )), static fn(string $slug): bool => $slug !== ''));
        } catch (Throwable $e) {
            $articles = [];
            $articleCategorySlugs = [];
        }

        $categoryNameBySlug = [];
        try {
            $categoryRows = $articleModel->getCategoryList();
            foreach ($categoryRows as $row) {
                $slug = trim((string) ($row['slug'] ?? ''));
                $name = trim((string) ($row['name'] ?? ''));
                if ($slug === '' || $name === '') {
                    continue;
                }
                $categoryNameBySlug[$slug] = $name;
            }
        } catch (Throwable $e) {
            $categoryNameBySlug = [];
        }

        if ($articleCategorySlugs !== []) {
            $filterKeys = array_fill_keys($articleCategorySlugs, true);
            $categoryNameBySlug = array_filter(
                $categoryNameBySlug,
                static fn($_, string $slug): bool => isset($filterKeys[$slug]),
                ARRAY_FILTER_USE_BOTH
            );
        } else {
            $categoryNameBySlug = [];
        }

        $missingSlugs = array_diff($articleCategorySlugs, array_keys($categoryNameBySlug));
        foreach ($missingSlugs as $slug) {
            $matches = array_values(array_filter(
                $articles,
                static fn(array $article) => ($article['category_slug'] ?? '') === $slug
            ));
            $name = trim((string) ($matches[0]['category_name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $categoryNameBySlug[$slug] = $name;
        }

        uasort($categoryNameBySlug, static fn(string $a, string $b): int => strcasecmp($a, $b));

        $categories = [];
        foreach ($categoryNameBySlug as $slug => $name) {
            $categories[] = [
                'slug' => $slug,
                'name' => $name,
            ];
        }

        $validSlugs = array_values(array_unique(array_filter(array_merge(
            $articleCategorySlugs,
            array_map(static fn(array $cat): string => $cat['slug'], $categories)
        ), static fn(string $slug): bool => $slug !== '')));

        $rawCategory = trim((string) ($_GET['category'] ?? $_GET['tab'] ?? 'all'));
        $activeCategorySlug = 'all';

        if (strcasecmp($rawCategory, 'all') === 0) {
            $activeCategorySlug = 'all';
        } elseif (in_array($rawCategory, $validSlugs, true)) {
            $activeCategorySlug = $rawCategory;
        }

        $this->view('pages/article.php', array_merge($this->sharedData('articles', 'Article'), [
            'categories' => $categories,
            'activeCategorySlug' => $activeCategorySlug,
            'articles' => $articles,
        ]));
    }

    public function articleDetailMockup(): void
    {
        $this->view('pages/article-detail-mockup.php', array_merge($this->sharedData('article', 'Article Detail Mockup'), [
            'currentPage' => 'article'
        ]));
    }

    public function portfolio(): void
    {
        // If `id` query param exists, show single portfolio detail
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        $portfolioModel = new Portfolio();

        if ($id > 0) {
            $row = $portfolioModel->getById($id);

            $status = strtolower(trim((string) ($row['status'] ?? '')));

            if ($row === false || $status === 'draft') {
                $this->notFound();
                return;
            }

            $project = [
                'id' => (int) ($row['id'] ?? 0),
                'title' => (string) ($row['title'] ?? ''),
                'meta_title' => (string) ($row['meta_title'] ?? ''),
                'meta_description' => (string) ($row['meta_description'] ?? ''),
                'meta_keywords' => (string) ($row['meta_keywords'] ?? ''),
                'industry' => (string) ($row['client_name'] ?? ''),
                'client_name' => (string) ($row['client_name'] ?? ''),
                'category_id' => (int) ($row['category_id'] ?? 0),
                'category' => (string) ($row['category'] ?? 'Other'),
                'summary' => (string) ($row['summary'] ?? ''),
                'description' => (string) ($row['description'] ?? ''),
                'metric' => (string) ($row['tech_stack'] ?? ''),
                'tech_stack' => (string) ($row['tech_stack'] ?? ''),
                'author_id' => isset($row['author_id']) ? (int) $row['author_id'] : null,
                'author' => (string) ($row['author'] ?? ''),
                'status' => (string) ($row['status'] ?? 'draft'),
                'slug' => (string) ($row['slug'] ?? ''),
                'web_path' => (string) ($row['slug'] ?? ''),
                'image_path' => (string) ($row['image_path'] ?? ''),
                'cover_image' => (string) ($row['image_path'] ?? ''),
                'cover_image_alt' => (string) ($row['cover_image_alt'] ?? ''),
                'created_at' => (string) ($row['created_at'] ?? ''),
                'updated_at' => (string) ($row['updated_at'] ?? ''),
            ];

            $relatedPortfolio = [];

            try {
                $allRows = $portfolioModel->getAll();
                $relatedRows = array_values(array_filter($allRows, static function (array $item) use ($id): bool {
                    $itemStatus = strtolower(trim((string) ($item['status'] ?? '')));
                    return (int) ($item['id'] ?? 0) !== $id && $itemStatus !== 'draft';
                }));

                $relatedPortfolio = array_map(static function (array $item): array {
                    return [
                        'id' => (int) ($item['id'] ?? 0),
                        'title' => (string) ($item['title'] ?? ''),
                        'image_path' => (string) ($item['image_path'] ?? ''),
                        'created_at' => (string) ($item['created_at'] ?? ''),
                    ];
                }, array_slice($relatedRows, 0, 4));
            } catch (Throwable $e) {
                $relatedPortfolio = [];
            }

            $this->view('pages/portfolio-detail.php', array_merge($this->sharedData('portfolio', $project['title'] ?: 'Portfolio'), [
                'project' => $project,
                'relatedPortfolio' => $relatedPortfolio,
            ]));

            return;
        }

        $filters = ['All'];
        $portfolioRows = [];

        try {
            $rows = $portfolioModel->getPublished();

            $portfolioRows = array_map(static function (array $row): array {
                return [
                    'id' => (int) ($row['id'] ?? 0),
                    'title' => (string) ($row['title'] ?? ''),
                    'industry' => (string) ($row['client_name'] ?? ''),
                    'category' => (string) ($row['category'] ?? 'Other'),
                    'summary' => (string) ($row['summary'] ?? ''),
                    'metric' => (string) ($row['tech_stack'] ?? ''),
                    'web_path' => (string) ($row['slug'] ?? ''),
                    'image_path' => (string) ($row['image_path'] ?? ''),
                ];
            }, $rows);

            $categoryValues = array_values(array_unique(array_filter(
                array_map(static fn(array $project): string => (string) $project['category'], $portfolioRows),
                static fn(string $category): bool => $category !== ''
            )));

            sort($categoryValues);

            if ($categoryValues !== []) {
                $filters = array_merge(['All'], $categoryValues);
            }
        } catch (Throwable $e) {
            $portfolioRows = [];
        }

        $rawFilter = $_GET['filter'] ?? 'All';
        $activeFilter = in_array($rawFilter, $filters, true) ? $rawFilter : 'All';

        $filteredProjects = array_values(array_filter($portfolioRows, static function (array $project) use ($activeFilter): bool {
            return $activeFilter === 'All' || $project['category'] === $activeFilter;
        }));

        $this->view('pages/portfolio.php', array_merge($this->sharedData('portfolio', 'Portfolio'), [
            'filters' => $filters,
            'activeFilter' => $activeFilter,
            'portfolioRows' => $portfolioRows,
        ]));
    }

    public function erp(): void
    {
        $modules = [
            [
                'id' => 'production',
                'label' => 'การผลิต',
                'title' => 'ระบบบริหารจัดการการผลิต (Production Management)',
                'description' => 'ควบคุมและวางแผนกระบวนการผลิตตั้งแต่ต้นน้ำถึงปลายน้ำ เพื่อลดต้นทุน ลดของเสีย และมั่นใจได้ว่าสามารถส่งมอบสินค้าได้ตรงตามกำหนดเวลา',
                'items' => [
                    'วางแผนความต้องการวัตถุดิบและทรัพยากร (MRP)',
                    'จัดการสูตรการผลิตและควบคุมต้นทุน (BOM & Costing)',
                    'ติดตามสถานะการผลิตแบบเรียลไทม์ผ่านระบบบาร์โค้ด',
                    'ระบบควบคุมคุณภาพ (QC) และประเมินของเสีย (Scrap Management)',
                ],
            ],
            [
                'id' => 'inventory',
                'label' => 'จัดซื้อ & คลังสินค้า',
                'title' => 'ระบบจัดการการจัดซื้อและคลังสินค้า (Purchase & Inventory)',
                'description' => 'บริหารจัดการซัพพลายเชนอย่างมีประสิทธิภาพ เชื่อมโยงข้อมูลตั้งแต่การสั่งซื้อวัตถุดิบไปจนถึงการรับเข้าและควบคุมสต็อกสินค้าอย่างแม่นยำ',
                'items' => [
                    'ขออนุมัติสั่งซื้อและจัดการข้อมูลผู้จัดจำหน่าย (Vendor Management)',
                    'ติดตามสต็อกแบบเรียลไทม์และแจ้งเตือนเมื่อสินค้าถึงจุดสั่งซื้อ (Reorder Point)',
                    'รองรับการบริหารจัดการคลังสินค้าหลายสาขา (Multi-Warehouse)',
                    'ตรวจสอบการรับสินค้าและเชื่อมโยงเอกสารสั่งซื้อ-รับของ-แจ้งหนี้ (3-Way Matching)',
                ],
            ],
            [
                'id' => 'sales',
                'label' => 'การขาย & CRM',
                'title' => 'ระบบจัดการการขายและลูกค้าสัมพันธ์ (Sales & CRM)',
                'description' => 'ขับเคลื่อนการเติบโตของธุรกิจด้วยระบบบริหารทีมขายที่ครอบคลุม พร้อมเก็บข้อมูลพฤติกรรมลูกค้าเพื่อสร้างประสบการณ์และการบริการที่ดีที่สุด',
                'items' => [
                    'จัดการไปป์ไลน์การขาย (Sales Pipeline) และติดตาม Lead',
                    'ออกใบเสนอราคาและแปลงเป็นใบสั่งขาย (Sales Order) แบบอัตโนมัติ',
                    'เก็บประวัติการติดต่อและข้อมูลลูกค้าแบบรอบด้าน (Customer 360°)',
                    'แดชบอร์ดติดตามยอดขายเปรียบเทียบกับเป้าหมายของทีม',
                ],
            ],
            [
                'id' => 'accounting',
                'label' => 'บัญชี & การเงิน',
                'title' => 'ระบบบริหารจัดการบัญชีและการเงิน (Accounting & Finance)',
                'description' => 'จัดการธุรกรรมทางการเงินอย่างแม่นยำและโปร่งใส เชื่อมโยงข้อมูลรายได้และค่าใช้จ่ายจากทุกแผนกเพื่อปิดงบได้รวดเร็วและตรวจสอบได้',
                'items' => [
                    'จัดการบัญชีลูกหนี้ (AR), เจ้าหนี้ (AP) และการวางบิลรับเช็ค',
                    'ระบบบันทึกบัญชีแยกประเภท (GL) และการปิดงบการเงิน',
                    'บริหารจัดการกระแสเงินสดและงบประมาณ (Cash Flow & Budgeting)',
                    'รองรับการคำนวณภาษีและการออกใบกำกับภาษีอิเล็กทรอนิกส์ (e-Tax)',
                ],
            ],
            [
                'id' => 'hr',
                'label' => 'ทรัพยากรบุคคล',
                'title' => 'ระบบบริหารจัดการทรัพยากรบุคคล (Human Resources)',
                'description' => 'ยกระดับการดูแลพนักงานด้วยระบบที่จัดการตั้งแต่งานเอกสาร การลงเวลา เงินเดือน ไปจนถึงการพัฒนาศักยภาพของบุคลากรในองค์กร',
                'items' => [
                    'คำนวณเงินเดือน ภาษี และประกันสังคมอัตโนมัติ (Payroll)',
                    'ระบบบันทึกเวลาทำงานและขออนุมัติลางานออนไลน์ (Time & Leave)',
                    'บริหารจัดการข้อมูลพนักงาน (Employee Self-Service)',
                    'ประเมินผลการปฏิบัติงานตามเป้าหมาย (KPI & Performance)',
                ],
            ],
            [
                'id' => 'analytics',
                'label' => 'BI & Analytics',
                'title' => 'ระบบวิเคราะห์ข้อมูลอัจฉริยะ (BI & Analytics)',
                'description' => 'เปลี่ยนข้อมูลที่ซับซ้อนให้เป็นข้อมูลเชิงลึก (Insights) ด้วยแดชบอร์ดที่สวยงาม เข้าใจง่าย ช่วยให้ผู้บริหารตัดสินใจทิศทางธุรกิจได้อย่างแม่นยำ',
                'items' => [
                    'แดชบอร์ดสรุปภาพรวมธุรกิจแบบเรียลไทม์ (Executive Dashboard)',
                    'สร้างและปรับแต่งรายงาน (Custom Reports) ด้วยตัวเองง่ายๆ',
                    'วิเคราะห์แนวโน้มและพยากรณ์ยอดขาย (Data Forecasting)',
                    'รวมศูนย์ข้อมูลจากทุกแผนกเพื่อวิเคราะห์ความสามารถในการทำกำไร',
                ],
            ],
        ];

        $moduleMap = [];

        foreach ($modules as $module) {
            if (isset($module['id'])) {
                $moduleMap[(string) $module['id']] = $module;
            }
        }

        $architecture = [
            ['t' => 'Frontend', 'd' => 'Web · Mobile · Admin Portal'],
            ['t' => 'API Gateway', 'd' => 'REST · GraphQL · Webhook'],
            ['t' => 'Core Services', 'd' => 'ERP Modules · AI Engine'],
            ['t' => 'Data Layer', 'd' => 'SQL · Warehouse · Cache'],
        ];

        $benefits = [
            [
                'title' => 'Scalability',
                'description' => 'ออกแบบให้รองรับการเติบโตขององค์กรตั้งแต่หลักสิบจนถึงหลักหมื่นผู้ใช้งาน',
            ],
            [
                'title' => 'Automation',
                'description' => 'ระบบอัตโนมัติทั่วทั้งองค์กร ลดงาน Manual และข้อผิดพลาดของมนุษย์',
            ],
            [
                'title' => 'Integration',
                'description' => 'เชื่อมต่อกับระบบเดิมและบริการภายนอกผ่าน API มาตรฐานอุตสาหกรรม',
            ],
        ];

        $activeModule = $modules[0]['id'];

        if (isset($_GET['mod'])) {
            $requestedModule = (string) $_GET['mod'];

            if (isset($moduleMap[$requestedModule])) {
                $activeModule = $requestedModule;
            }
        }

        $currentModule = $moduleMap[$activeModule] ?? $modules[0];

        $erpPortfolios = [];
        try {
            $portfolioModel = new Portfolio();
            $rows = array_values(array_filter($portfolioModel->getByCategoryName('ERP'), static function (array $row): bool {
                $status = strtolower(trim((string) ($row['status'] ?? '')));
                return $status !== 'draft';
            }));

            $erpPortfolios = array_map(static function (array $row): array {
                return [
                    'id' => (int) ($row['id'] ?? 0),
                    'title' => (string) ($row['title'] ?? ''),
                    'description' => (string) ($row['description'] ?? ''),
                    'slug' => (string) ($row['slug'] ?? ''),
                    'image_path' => (string) ($row['image_path'] ?? $row['cover_image'] ?? ''),
                ];
            }, $rows);
        } catch (Throwable $e) {
            $erpPortfolios = [];
        }

        $this->view('pages/erp.php', array_merge($this->sharedData('erp', 'ERP System'), [
            'benefits' => $benefits,
            'modules' => $modules,
            'activeModule' => $activeModule,
            'currentModule' => $currentModule,
            'integrations' => [
                'REST API',
                'GraphQL',
                'Webhooks',
                'Microsoft 365',
                'Line OA',
            ],
            'architecture' => $architecture,
            'erpPortfolios' => $erpPortfolios,
        ]));
    }

    public function about(): void
    {
        $this->view('pages/about.php', array_merge($this->sharedData('about', 'About'), [
            'values' => [
                ['title' => 'Engineering Excellence', 'description' => 'เราเชื่อในมาตรฐานทางวิศวกรรมที่สูงและการส่งมอบที่วัดผลได้'],
                ['title' => 'Business First', 'description' => 'เทคโนโลยีต้องตอบโจทย์ธุรกิจ ไม่ใช่เพียงเครื่องมือ'],
                ['title' => 'Long-term Partnership', 'description' => 'เราดูแลลูกค้าตลอดวงจรชีวิตของระบบ'],
                ['title' => 'Continuous Innovation', 'description' => 'เราเรียนรู้และปรับตัวเร็วกว่าใคร เพื่อนำคุณไปสู่ขั้นถัดไป'],
            ],
            'timeline' => [
                ['year' => '2010', 'title' => 'เริ่มต้นบริษัท', 'description' => 'ก่อตั้งจากทีมวิศวกรซอฟต์แวร์ 5 คนในกรุงเทพฯ'],
                ['year' => '2015', 'title' => 'ขยายสู่ระดับองค์กร', 'description' => 'ส่งมอบระบบ ERP แรกให้ลูกค้า Manufacturing'],
                ['year' => '2019', 'title' => 'เปิดตัวบริการ AI', 'description' => 'เริ่มให้บริการ AI Automation และ Predictive Analytics'],
                ['year' => '2024', 'title' => '60+ Engineers', 'description' => 'ทีมวิศวกรกว่า 60 คน ดูแลโปรเจกต์กว่า 200 องค์กร'],
            ],
            'team' => [
                ['name' => 'Surapong K.', 'role' => 'Co-founder & CEO'],
                ['name' => 'Patcharee S.', 'role' => 'Chief Technology Officer'],
                ['name' => 'Niran T.', 'role' => 'Head of Engineering'],
                ['name' => 'Anong P.', 'role' => 'Head of Product'],
            ],
            'partners' => ['AURORA', 'NEXUS', 'ORBIT', 'VERTEX', 'QUANTUM', 'STRATUM'],
            'trustLogos' => [
                asset_url('images/logo1.png'),
                asset_url('images/logo2.png'),
                asset_url('images/logo3.png'),
                asset_url('images/logo4.png'),
                asset_url('images/logo5.png'),
            ],
        ]));
    }

    public function contact(): void
    {
        $settings = (new Setting())->getByKeys([
            'company_name',
            'contact_address',
            'contact_phone',
            'contact_email',
            'contact_hours',
        ]);

        $company = [
            'name' => $settings['company_name'] ?? '',
            'contact' => [
                'address' => $settings['contact_address'] ?? '',
                'phone' => $settings['contact_phone'] ?? '',
                'email' => $settings['contact_email'] ?? '',
            ],
            'hours' => $settings['contact_hours'] ?? '',
        ];

        $form = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'company' => trim((string) ($_POST['company'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'inquiry' => trim((string) ($_POST['inquiry'] ?? 'Sales')),
            'message' => trim((string) ($_POST['message'] ?? '')),
        ];

        $submitted = false;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($form['name'] === '') {
                $errors[] = 'กรุณากรอกชื่อ';
            }

            if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'อีเมลไม่ถูกต้อง';
            }

            $nameLength = function_exists('mb_strlen') ? mb_strlen($form['name']) : strlen($form['name']);
            $emailLength = function_exists('mb_strlen') ? mb_strlen($form['email']) : strlen($form['email']);

            if ($nameLength > 100) {
                $errors[] = 'ชื่อยาวเกินไป';
            }

            if ($emailLength > 255) {
                $errors[] = 'อีเมลยาวเกินไป';
            }

            $submitted = $errors === [];
        }

        $this->view('pages/contact.php', array_merge($this->sharedData('contact', 'Contact'), [
            'company' => $company,
            'form' => $form,
            'submitted' => $submitted,
            'errors' => $errors,
        ]));
    }

    public function notFound(): void
    {
        http_response_code(404);

        $this->view('pages/not-found.php', array_merge($this->sharedData('not-found', 'Page not found'), [
            'currentPage' => '',
        ]));
    }

    /**
     * Layout variables shared across every page.
     *
     * @return array<string, mixed>
     */
    private function sharedData(string $currentPage, string $title): array
    {
        return [
            'currentPage' => $currentPage,
            'title' => $title,
            'siteName' => config('app.name', 'WEBPARK'),
            'company' => config('company', []),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function view(string $path, array $data = []): void
    {
        $this->renderer->view($path, $data);
    }
}
