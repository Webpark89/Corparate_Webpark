<?php
require_once __DIR__ . '/../frontend/app/bootstrap.php';
$db = Database::getInstance();

$services = [
    [
        'slug' => 'erp-erm',
        'features' => [
            'ERP System', 'Accounting & Finance', 'Sales / Purchase', 'Inventory / Warehouse', 
            'Customer Management', 'Lead Management', 'Customer Service', 
            'Partner / Supplier Management', 'HRM System', 'Attendance / Leave', 
            'Payroll', 'Workflow Approval'
        ]
    ],
    [
        'slug' => 'digital-platform',
        'features' => [
            'Website / Responsive / CMS', 'Mobile App / Mobile Site', 'E-commerce', 
            'Custom Web Application', 'Membership / Portal System', 'SMS Service', 
            'Email Marketing', 'Chatbot / Live Chat', 'Game / Interactive Campaign', 
            'Big Data', 'E-learning', 'Dashboard', 'Data Management'
        ]
    ],
    [
        'slug' => 'online-marketing',
        'features' => [
            'Digital Marketing Consultant', 'Media Planner / PR & Media Strategy', 'SEO', 
            'Social Network', 'Online Campaign', 'Monitoring & Analysis', 
            'Campaign Performance Report', 'Return on Investment (ROI)', 
            'Productivity Analysis', 'Content Strategy', 'Ads Management', 
            'Social Media Content', 'Search Engine Marketing'
        ]
    ],
    [
        'slug' => 'creative-design',
        'features' => [
            'Web Design', 'UX/UI Design', 'Cartoon & Character Design', 'Infographic', 
            'Animation TV & YouTube Online', 'Motion VDO', 'Video Editing', 
            'Presentation Video', 'E-Magazine', 'Print Ads', 'Online Banner', 
            'Key Visual Design'
        ]
    ]
];

$stmt = $db->prepare('UPDATE service SET details_json = ? WHERE slug = ?');

foreach ($services as $s) {
    $detailsJson = json_encode(['features' => $s['features']]);
    $stmt->execute([$detailsJson, $s['slug']]);
}

echo "Database features successfully updated for all 4 services!\n";
