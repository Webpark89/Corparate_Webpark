const fs = require('fs');
let content = fs.readFileSync('frontend/app/views/components/navbar.php', 'utf8');
content = content.replace(/หน้าแรฆ/g, "หน้าแรก");
content = content.replace(/เกี่็วกับเรา/g, "เกี่ยวกับเรา");
fs.writeFileSync('frontend/app/views/components/navbar.php', content, 'utf8');
