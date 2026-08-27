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
                    'is_pinned' => !empty($row['is_pinned']),
                    'slug' => (string) ($row['slug'] ?? ''),
                    'slug_en' => (string) ($row['slug_en'] ?? ''),
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
                    'slug' => (string) ($row['slug'] ?? ''),
                    'slug_en' => (string) ($row['slug_en'] ?? ''),
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

    public function article(?string $slug = null): void
    {
        $identifier = $slug !== null && trim($slug) !== '' ? trim($slug) : (isset($_GET['id']) ? trim((string)$_GET['id']) : '');
        $articleModel = new Article();

        if ($identifier !== '') {
            $row = $articleModel->getBySlugOrId($identifier);
            $status = strtolower(trim((string) ($row['status'] ?? '')));

            if ($row === false || $status === 'draft' || $status === 'hidden') {
                $this->notFound();
                return;
            }

            $lang = getCurrentLang();
            
            $metaTitle = (string) ($row['meta_title'] ?? '');
            $metaDesc = (string) ($row['meta_description'] ?? '');
            $metaKeywords = (string) ($row['meta_keywords'] ?? '');

            if ($lang === 'en') {
                $metaTitle = (string) ($row['meta_title_en'] ?? '') ?: $metaTitle;
                $metaDesc = (string) ($row['meta_description_en'] ?? '') ?: $metaDesc;
                $metaKeywords = (string) ($row['meta_keywords_en'] ?? '') ?: $metaKeywords;
            }

            $descText = trim($metaDesc);
            if ($descText === '') {
                $descText = get_article_summary_text((string) ($row['content'] ?? ''), $lang);
                if ($descText !== '') {
                    $descText = mb_strimwidth($descText, 0, 180, '...');
                }
            }

            $slugValue = ($lang === 'en' && !empty($row['slug_en'])) ? $row['slug_en'] : (!empty($row['slug']) ? $row['slug'] : (string)$row['id']);

            $article = [
                'id' => (int) ($row['id'] ?? 0),
                'slug' => (string) ($row['slug'] ?? ''),
                'slug_en' => (string) ($row['slug_en'] ?? ''),
                'title' => $metaTitle,
                'meta_title' => $metaTitle,
                'meta_description' => $descText,
                'summary' => $descText !== '' ? mb_strimwidth($descText, 0, 140, '...') : '',
                'meta_keywords' => $metaKeywords,
                'category' => (string) ($row['category'] ?? 'General'),
                'category_slug' => (string) ($row['category_slug'] ?? ''),
                'image_path' => (string) ($row['image_path'] ?? ''),
                'cover_image' => (string) ($row['image_path'] ?? ''),
                'cover_image_alt' => (string) ($row['cover_image_alt'] ?? ''),
                'content' => (string) ($row['content'] ?? ''),
                'author' => (string) ($row['author'] ?? ''),
                'created_at' => (string) ($row['created_at'] ?? ''),
                'source_url' => (string) ($row['source_url'] ?? ''),
            ];

            $relatedArticles = [];
            try {
                $relatedArticles = $articleModel->getRelatedByCategory(
                    (int) ($row['category_id'] ?? 0),
                    (int) ($row['id'] ?? 0),
                    2
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

            // Track article view with anti-refresh throttle (1 view per article per session/hour)
            $articleId = (int) ($row['id'] ?? 0);
            if ($articleId > 0) {
                if (session_status() === PHP_SESSION_NONE) {
                    @session_start();
                }
                $viewedKey = 'viewed_article_' . $articleId;
                $lastViewed = $_SESSION[$viewedKey] ?? 0;
                if (time() - (int) $lastViewed > 3600) {
                    $_SESSION[$viewedKey] = time();
                    try {
                        $articleModel->incrementViews($articleId);
                    } catch (Throwable $e) {
                        // Ignore view increment errors
                    }
                }
            }

            $articleUrl = route_url('/article/' . $slugValue);
            $articleImageUrl = resolve_article_image_url($article['image_path'] ?? '', asset_url('images/story.png'));
            $publishedIso = !empty($article['created_at']) ? date('c', strtotime($article['created_at'])) : date('c');
            $modifiedIso = !empty($row['updated_at']) ? date('c', strtotime($row['updated_at'])) : $publishedIso;

            $jsonLd = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'BreadcrumbList',
                        'itemListElement' => [
                            [
                                '@type' => 'ListItem',
                                'position' => 1,
                                'name' => t('article_detail.breadcrumb_home', ['default' => 'หน้าแรก']),
                                'item' => route_url('/'),
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 2,
                                'name' => t('article_detail.breadcrumb_articles', ['default' => 'บทความ']),
                                'item' => route_url('/article'),
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 3,
                                'name' => $article['category'],
                                'item' => route_url('/article', ['category' => $article['category_slug'] ?? 'all']),
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 4,
                                'name' => $article['title'],
                                'item' => $articleUrl,
                            ]
                        ]
                    ],
                    [
                        '@type' => 'Article',
                        '@id' => $articleUrl . '#article',
                        'isPartOf' => [
                            '@type' => 'WebPage',
                            '@id' => $articleUrl
                        ],
                        'headline' => $article['title'],
                        'description' => $article['meta_description'] ?: $article['summary'],
                        'image' => [
                            $articleImageUrl
                        ],
                        'datePublished' => $publishedIso,
                        'dateModified' => $modifiedIso,
                        'mainEntityOfPage' => [
                            '@type' => 'WebPage',
                            '@id' => $articleUrl
                        ],
                        'author' => [
                            '@type' => 'Organization',
                            'name' => $article['author'] ?: 'Webpark Team'
                        ],
                        'publisher' => [
                            '@type' => 'Organization',
                            'name' => config('app.name', 'WEBPARK'),
                            'logo' => [
                                '@type' => 'ImageObject',
                                'url' => asset_url('images/logo.png')
                            ]
                        ]
                    ]
                ]
            ];

            $this->view('pages/article-detail.php', array_merge($this->sharedData('article', $article['title'] ?: 'Article'), [
                'article' => $article,
                'relatedArticles' => $relatedArticles,
                'popularCategories' => $popularCategories,
                'metaTitle' => $article['meta_title'] ?: $article['title'],
                'metaDescription' => $article['meta_description'] ?: $article['summary'],
                'imageUrl' => $articleImageUrl,
                'imageAlt' => $article['cover_image_alt'] ?: $article['title'],
                'publishedTime' => $publishedIso,
                'modifiedTime' => $modifiedIso,
                'authorName' => $article['author'] ?: 'Webpark Team',
                'canonicalUrl' => $articleUrl,
                'jsonLd' => $jsonLd,
            ]));

            return;
        }

        $articles = [];
        $articleCategorySlugs = [];

        try {
            $rows = $articleModel->getPublished();
            $lang = getCurrentLang();
            $articles = array_map(static function (array $row) use ($lang): array {
                $metaTitle = (string) ($row['meta_title'] ?? $row['title'] ?? '');
                $metaDesc = (string) ($row['description'] ?? $row['meta_description'] ?? '');

                if ($lang === 'en') {
                    $metaTitle = (string) ($row['meta_title_en'] ?? '') ?: $metaTitle;
                    $metaDesc = (string) ($row['meta_description_en'] ?? '') ?: $metaDesc;
                }

                $description = trim($metaDesc);
                $content = trim((string) ($row['content'] ?? ''));
                if ($description !== '') {
                    $summary = mb_strimwidth(strip_tags($description), 0, 140, '...');
                } else {
                    $summary = mb_strimwidth(get_article_summary_text($content, $lang), 0, 140, '...');
                }

                return [
                    'id' => (int) ($row['id'] ?? 0),
                    'is_pinned' => !empty($row['is_pinned']),
                    'slug' => (string) ($row['slug'] ?? ''),
                    'slug_en' => (string) ($row['slug_en'] ?? ''),
                    'title' => $metaTitle,
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

        // Hardcoded mockup categories as requested by user
        $categories = [
            ['slug' => 'erp-erm', 'name' => 'ERP / ERM'],
            ['slug' => 'digital-platform', 'name' => 'Digital Platform'],
            ['slug' => 'online-marketing', 'name' => 'Online Marketing'],
            ['slug' => 'creative-design', 'name' => 'Creative / Design'],
        ];

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

    public function serviceDigitalPlatform(): void
    {
        $this->view('pages/service-digital-platform.php', array_merge($this->sharedData('services', 'Digital Platform'), [
            'currentPage' => 'services'
        ]));
    }

    public function serviceOnlineMarketing(): void
    {
        $this->view('pages/service-online-marketing.php', array_merge($this->sharedData('services', 'Online Marketing'), [
            'currentPage' => 'services'
        ]));
    }

    public function serviceCreativeDesign(): void
    {
        $this->view('pages/service-creative-design.php', array_merge($this->sharedData('services', 'Creative Design'), [
            'currentPage' => 'services'
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

            // Mockup categories requested by user
            $categoryValues = [
                'ERP / ERM',
                'Digital Platform',
                'Online Marketing',
                'Creative / Design'
            ];

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

    /**
     * Endpoint for contact form submissions (AJAX or standard POST from bottom CTA or forms).
     */
    public function contactSubmit(): void
    {
        send_security_headers();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            exit;
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            || (!empty($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
            || !empty($_POST['is_ajax']);

        // 1. Honeypot check (hidden field to trap spam bots)
        $honeypot = trim((string) ($_POST['website_url'] ?? ''));
        if ($honeypot !== '') {
            error_log('[Contact Submit] Spam bot blocked via honeypot trap from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
            if ($isAjax) {
                http_response_code(422);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => ['ตรวจพบลักษณะของโปรแกรมอัตโนมัติ (Spam Detected)']]);
                exit;
            }
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? route_url('/')));
            exit;
        }

        // 2. CSRF Token Verification
        if (!verify_csrf_token()) {
            if ($isAjax) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'errors' => [getCurrentLang() === 'th' ? 'เซสชันหมดอายุหรือคำขอไม่ถูกต้อง กรุณารีเฟรชหน้าเว็บแล้วลองอีกครั้ง' : 'Invalid or expired CSRF session. Please refresh and try again.']
                ]);
                exit;
            }
            $referer = $_SERVER['HTTP_REFERER'] ?? route_url('/');
            header('Location: ' . $referer);
            exit;
        }

        $settingModel = new Setting();
        $settings = $settingModel->getByKeys([
            'company_name',
            'contact_address',
            'contact_phone',
            'contact_email',
            'contact_hours',
            'recaptcha_site_key',
            'recaptcha_secret_key',
            'mail_to',
            'mail_host',
            'mail_port',
            'mail_user',
            'mail_pass',
            'mail_from_name',
        ]);

        $secretKey = (string) ($settings['recaptcha_secret_key'] ?? getenv('RECAPTCHA_SECRET_KEY') ?: '');

        $form = [
            'company_name' => trim((string) ($_POST['company_name'] ?? '')),
            'first_name'   => trim((string) ($_POST['first_name'] ?? '')),
            'last_name'    => trim((string) ($_POST['last_name'] ?? '')),
            'phone'        => trim((string) ($_POST['phone'] ?? '')),
            'email'        => trim((string) ($_POST['email'] ?? '')),
            'message'      => trim((string) ($_POST['message'] ?? '')),
            'pdpa_agreed'  => !empty($_POST['pdpa_agreed']),
            'source_page'  => trim((string) ($_POST['source_page'] ?? ($_SERVER['HTTP_REFERER'] ?? ''))),
        ];

        $errors = $this->validateContactInput($form, $secretKey);

        if (!empty($errors)) {
            if ($isAjax) {
                http_response_code(422);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }
            $referer = $_SERVER['HTTP_REFERER'] ?? route_url('/');
            header('Location: ' . $referer);
            exit;
        }

        $contactModel = new ContactMessage();
        $messageData = [
            'company_name'    => $form['company_name'] !== '' ? $form['company_name'] : null,
            'first_name'      => $form['first_name'],
            'last_name'       => $form['last_name'],
            'phone'           => $form['phone'],
            'email'           => $form['email'],
            'message'         => $form['message'],
            'pdpa_consent'    => 1,
            'pdpa_consent_at' => date('Y-m-d H:i:s'),
            'status'          => 'new',
            'ip_address'      => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent'      => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'source_page'     => $form['source_page'] !== '' ? $form['source_page'] : ($_SERVER['HTTP_REFERER'] ?? route_url('/')),
            'email_sent'      => 0,
        ];

        try {
            // Save contact message to database first
            $messageId = $contactModel->create($messageData);

            // Fast Non-blocking response for AJAX clients
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => getCurrentLang() === 'th' ? 'ส่งข้อมูลสำเร็จ เรียบร้อยแล้ว' : 'Submission Successful',
                ]);

                // Close connection early to prevent browser from hanging while SMTP delivers
                if (function_exists('fastcgi_finish_request')) {
                    fastcgi_finish_request();
                } else {
                    if (ob_get_level() > 0) {
                        ob_end_flush();
                    }
                    flush();
                }
            }

            // Deliver notification email asynchronously in background
            try {
                $emailSent = Mailer::sendContactNotification($messageData, $settings);
                if ($emailSent) {
                    $contactModel->updateEmailSent($messageId, true);
                }
            } catch (Throwable $mailEx) {
                error_log('[Contact Mailer Async Error] ' . $mailEx->getMessage());
            }

            if (!$isAjax) {
                $referer = $_SERVER['HTTP_REFERER'] ?? route_url('/');
                header('Location: ' . $referer);
            }
            exit;
        } catch (Exception $e) {
            error_log('[Contact Submit Error] DB Insert failed: ' . $e->getMessage());
            if ($isAjax) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => ['เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง']]);
                exit;
            }
            $referer = $_SERVER['HTTP_REFERER'] ?? route_url('/');
            header('Location: ' . $referer);
            exit;
        }
    }

    /**
     * Shared contact validation logic.
     *
     * @param array<string, mixed> $form
     * @return array<int, string> List of error messages.
     */
    private function validateContactInput(array $form, string $secretKey): array
    {
        $errors = [];

        // 0. Rate Limiting / Anti-Flood (Max 5 submissions per 5 minutes per IP)
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip !== '') {
            $contactModel = new ContactMessage();
            $recentCount = $contactModel->countRecentByIp($ip, 5);
            if ($recentCount >= 5) {
                $errors[] = getCurrentLang() === 'th'
                    ? 'คุณส่งข้อความถี่เกินไป กรุณารอประมาณ 5 นาทีแล้วลองใหม่อีกครั้ง'
                    : 'Too many requests. Please wait about 5 minutes before submitting again.';
                return $errors;
            }
        }

        // 1. Validate First Name
        if (($form['first_name'] ?? '') === '') {
            $errors[] = 'กรุณาระบุชื่อจริง';
        } elseif (preg_match('/\s/u', (string)$form['first_name'])) {
            $errors[] = 'ชื่อจริงต้องไม่มีช่องว่าง (Space)';
        } elseif (mb_strlen((string)$form['first_name'], 'UTF-8') > 30) {
            $errors[] = 'ชื่อจริงต้องไม่เกิน 30 ตัวอักษร';
        }

        // 2. Validate Last Name
        if (($form['last_name'] ?? '') === '') {
            $errors[] = 'กรุณาระบุนามสกุล';
        } elseif (preg_match('/\s/u', (string)$form['last_name'])) {
            $errors[] = 'นามสกุลต้องไม่มีช่องว่าง (Space)';
        } elseif (mb_strlen((string)$form['last_name'], 'UTF-8') > 30) {
            $errors[] = 'นามสกุลต้องไม่เกิน 30 ตัวอักษร';
        }

        // 3. Validate Phone Number (numeric only <= 10 digits)
        if (($form['phone'] ?? '') === '') {
            $errors[] = 'กรุณาระบุเบอร์โทรศัพท์';
        } elseif (!preg_match('/^[0-9]+$/', (string)$form['phone'])) {
            $errors[] = 'เบอร์โทรศัพท์ต้องเป็นตัวเลขล้วนเท่านั้น';
        } elseif (strlen((string)$form['phone']) < 9 || strlen((string)$form['phone']) > 10) {
            $errors[] = 'เบอร์โทรศัพท์ต้องมีความยาว 9-10 หลัก';
        }

        // 4. Validate Email
        if (($form['email'] ?? '') === '') {
            $errors[] = 'กรุณาระบุอีเมล';
        } elseif (!filter_var((string)$form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'รูปแบบอีเมลไม่ถูกต้อง';
        } elseif (strlen((string)$form['email']) > 255) {
            $errors[] = 'อีเมลยาวเกินไป (ไม่เกิน 255 ตัวอักษร)';
        } else {
            // Check MX record on non-localhost
            $serverHost = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
            $isLocal = in_array($serverHost, ['localhost', '127.0.0.1'], true)
                || str_starts_with($serverHost, 'localhost:')
                || str_starts_with($serverHost, '127.0.0.1:');

            if (!$isLocal) {
                $domain = substr(strrchr((string)$form['email'], '@'), 1);
                if ($domain && function_exists('checkdnsrr')) {
                    if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
                        $errors[] = 'ไม่พบ Mail Server สำหรับโดเมนของอีเมลนี้';
                    }
                }
            }
        }

        // 5. Validate Message & Character count <= 500 characters
        if (($form['message'] ?? '') === '') {
            $errors[] = 'กรุณาระบุข้อความรายละเอียด';
        } else {
            $charCount = mb_strlen(trim((string)$form['message']), 'UTF-8');
            if ($charCount > 500) {
                $errors[] = "ข้อความมีความยาวเกินกำหนด ({$charCount} ตัวอักษร / สูงสุด 500 ตัวอักษร)";
            }
        }

        // 6. Validate PDPA Consent
        if (empty($form['pdpa_agreed'])) {
            $errors[] = 'กรุณายินยอมตามนโยบายคุ้มครองข้อมูลส่วนบุคคล (PDPA)';
        }

        // 7. Verify Google reCAPTCHA v2
        $recaptchaToken = (string) ($_POST['g-recaptcha-response'] ?? '');
        if ($recaptchaToken === '') {
            $errors[] = 'กรุณายืนยันว่าคุณไม่ใช่โปรแกรมอัตโนมัติ (reCAPTCHA)';
        } else {
            $isRecaptchaValid = $this->verifyRecaptcha($recaptchaToken, $secretKey);
            if (!$isRecaptchaValid) {
                $errors[] = 'การตรวจสอบ reCAPTCHA ไม่สำเร็จ กรุณาลองใหม่อีกครั้ง';
            }
        }

        return $errors;
    }

    /**
     * Verify Google reCAPTCHA v2 token with Google Siteverify API.
     */
    private function verifyRecaptcha(string $token, string $secretKey): bool
    {
        if ($token === '') {
            return false;
        }

        // If secret key is not set, log error
        if ($secretKey === '') {
            error_log('[reCAPTCHA] Secret key is not configured in settings or environment.');
            return false;
        }

        $postData = http_build_query([
            'secret'   => $secretKey,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                             "Content-Length: " . strlen($postData) . "\r\n",
                'content' => $postData,
                'timeout' => 8,
            ],
            'ssl' => [
                'verify_peer'      => true,
                'verify_peer_name' => true,
            ]
        ];

        $context = stream_context_create($opts);
        $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);

        if ($response === false && function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        if ($response !== false) {
            $result = json_decode($response, true);
            return !empty($result['success']);
        }

        return false;
    }

    public function privacyPolicy(): void
    {
        $this->view('pages/privacy-policy.php', array_merge($this->sharedData('privacy-policy', 'นโยบายความเป็นส่วนตัว (Privacy Policy)'), [
            'metaDescription' => 'นโยบายความเป็นส่วนตัว (Privacy Policy) ของ บริษัท เว็บพาร์ค จำกัด (WEBPARK) ตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)',
        ]));
    }

    public function notFound(): void
    {
        http_response_code(404);

        $this->view('pages/not-found.php', array_merge($this->sharedData('not-found', 'Page not found'), [
            'currentPage' => '',
        ]));
    }

    public function serverError(int|string $code = 500, ?string $message = null): void
    {
        $statusCode = is_numeric($code) ? (int)$code : 500;
        if (!in_array($statusCode, [500, 502, 503, 504], true)) {
            $statusCode = 500;
        }

        http_response_code($statusCode);
        $lang = getCurrentLang();
        $isTh = $lang === 'th';

        $configs = [
            500 => [
                'badge' => $isTh ? 'เกิดข้อผิดพลาดของระบบ' : 'Server Error',
                'heading' => $isTh ? 'เกิดข้อผิดพลาดของเซิร์ฟเวอร์' : 'Internal Server Error',
                'description' => $isTh 
                    ? 'ขออภัยในความไม่สะดวก ระบบกำลังประสบปัญหาทางเทคนิคชั่วคราว ทีมงานได้รับทราบและกำลังดำเนินการแก้ไข กรุณาลองใหม่อีกครั้ง' 
                    : 'Sorry for the inconvenience. Our server encountered an internal technical issue. Our team is working to fix it.',
            ],
            502 => [
                'badge' => $isTh ? 'การเชื่อมต่อผิดพลาด' : 'Bad Gateway',
                'heading' => $isTh ? 'การเชื่อมต่อไปยังเซิร์ฟเวอร์ขัดข้อง' : 'Bad Gateway',
                'description' => $isTh 
                    ? 'ขออภัย เซิร์ฟเวอร์ตัวกลางไม่ได้รับสัญญาณตอบรับที่ถูกต้องจากเซิร์ฟเวอร์หลัก กรุณาลองใหม่อีกครั้งในภายหลัง' 
                    : 'The proxy server received an invalid response from the upstream server. Please try again in a few moments.',
            ],
            503 => [
                'badge' => $isTh ? 'ปิดปรับปรุงระบบชั่วคราว' : 'Maintenance Mode',
                'heading' => $isTh ? 'ระบบปิดปรับปรุงชั่วคราว' : 'Service Unavailable',
                'description' => $isTh 
                    ? 'ขออภัยในความไม่สะดวก เว็บไซต์กำลังอยู่ระหว่างการบำรุงรักษาหรือมีปริมาณการใช้งานหนาแน่นชั่วคราว กรุณากลับมาใหม่อีกครั้งในไม่ช้า' 
                    : 'The service is temporarily unavailable due to maintenance downtime or capacity limits. Please check back soon.',
            ],
            504 => [
                'badge' => $isTh ? 'หมดเวลาการเชื่อมต่อ' : 'Gateway Timeout',
                'heading' => $isTh ? 'หมดเวลาการเชื่อมต่อเซิร์ฟเวอร์' : 'Gateway Timeout',
                'description' => $isTh 
                    ? 'ขออภัย การเชื่อมต่อใช้เวลานานเกินกำหนด ทำให้เซิร์ฟเวอร์ไม่สามารถประมวลผลคำขอได้ทัน กรุณาลองใหม่อีกครั้ง' 
                    : 'The server took too long to respond and the request timed out. Please try reloading the page.',
            ],
        ];

        $currentConfig = $configs[$statusCode] ?? $configs[500];

        $this->view('pages/error-500.php', array_merge($this->sharedData('error-500', $currentConfig['heading']), [
            'currentPage' => '',
            'statusCode' => $statusCode,
            'badgeText' => $currentConfig['badge'],
            'errorHeading' => $currentConfig['heading'],
            'errorDescription' => $currentConfig['description'],
            'errorMessage' => $message,
        ]));
    }

    public function serverError502(): void
    {
        $this->serverError(502);
    }

    public function serverError503(): void
    {
        $this->serverError(503);
    }

    public function serverError504(): void
    {
        $this->serverError(504);
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
     * Track daily site pageviews and unique visitors.
     */
    private function trackDailyTraffic(): void
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                @session_start();
            }

            // Exclude common search bots and web crawlers
            $userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
            if ($userAgent !== '' && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $userAgent)) {
                return;
            }

            $today = date('Y-m-d');
            $pdo = Database::getInstance();

            $isUniqueToday = false;
            $uniqueKey = 'visited_date_' . $today;
            if (empty($_SESSION[$uniqueKey])) {
                $_SESSION[$uniqueKey] = 1;
                $isUniqueToday = true;
            }

            $sql = "INSERT INTO daily_traffic (`date`, `pageviews`, `unique_visitors`) 
                    VALUES (:date, 1, :unique)
                    ON DUPLICATE KEY UPDATE 
                        pageviews = pageviews + 1,
                        unique_visitors = unique_visitors + :unique_inc";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':date' => $today,
                ':unique' => $isUniqueToday ? 1 : 0,
                ':unique_inc' => $isUniqueToday ? 1 : 0,
            ]);
        } catch (Throwable $e) {
            // Silently catch errors so page rendering never breaks
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function view(string $path, array $data = []): void
    {
        send_security_headers();
        $this->trackDailyTraffic();
        $this->renderer->view($path, $data);
    }
}
