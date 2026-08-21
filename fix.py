import re
with open('frontend/app/views/components/footer.php', 'r', encoding='utf-8') as f:
    content = f.read()
content = re.sub(r'route_url\(' + r"\'/\" \. \'([a-zA-Z0-9\-_/]+)\' \. \"\'" + r'\)', r"route_url('/\1')", content)
content = re.sub(r'route_url\(' + r"\'/\" \. \'([a-zA-Z0-9\-_/]+)\' \. \"\'" + r'\)', r"route_url('/\1')", content)
with open('frontend/app/views/components/footer.php', 'w', encoding='utf-8') as f:
    f.write(content)
