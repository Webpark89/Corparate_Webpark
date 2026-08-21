const fs = require('fs');
let content = fs.readFileSync('frontend/app/views/components/footer.php', 'utf8');
content = content.replace(/<\/div>\s+<\/div>\s+<div style="background-color: #022862;/g, '</div>\n        <div style="background-color: #022862;');
fs.writeFileSync('frontend/app/views/components/footer.php', content, 'utf8');
