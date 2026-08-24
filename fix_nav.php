<?php
$content = file_get_contents('frontend/app/views/components/navbar.php');
$content = preg_replace('/$this->navItems .*?;/s', "\$navItems = [\n    ['path' => \$currentLang === 'th' ? '/หน้าแรฆ' : '/home', 'label' => t('common.nav_home'), 'page' => 'home'],\n    ['path' => \$currentLang === 'th' ? '/เกี่็วกับเรา' : '/about', 'label' => t('common.nav_about'), 'page' => 'about'],\n    ['path' => \$currentLang === 'th' ? '/บริการของเรา' : '/services', 'label' => t('common.nav_services'), 'page' => 'services'],\n    ['path' => \$currentLang === 'th' ? '/ระบบ-erp' : '/erp', 'label' => t('common.nav_erp'), 'page' => 'erp'],\n    ['path' => \$currentLang === 'th' ? '/บทควาก' : '/article', 'label' => t('common.nav_articles'), 'page' => 'articles'],\n    ['path' => \$currentLang === 'th' ? '/ติดต่อเรา' : '/contact', 'label' => t('common.nav_contact'), 'page' => 'contact'],\n];", $content);
// Fix logo link
$content = preg_replace('/route_url\(\$currentLang === \'th\' \\ \\'\\/[^\n]*?\\' : \\'\\/home\\'\)/', "route_url(\$currentLang === 'th' ? '/หน้าแรฆ' : '/home')", $content);
file_put_contents('frontend/app/views/components/navbar.php', $content);
