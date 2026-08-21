const fs = require('fs');
const lines = fs.readFileSync('C:/Users/User/.gemini/antigravity/brain/c004a355-cd4d-4e3f-a38a-14aecd644bed/.system_generated/logs/transcript.jsonl', 'utf8').split('\n');
for(const line of lines) {
    if (line.includes('PD9waHAKZGVjbGFyZShzdHJpY3RfdHlwZXM9MSk7')) {
        const obj = JSON.parse(line);
        const args = obj.tool_calls[0].args.CommandLine;
        const b64 = args.match(/PD9waHAKZGVjbGFyZShzdHJpY3RfdHlwZXM9MSk7[^\\"]+/)[0];
        fs.writeFileSync('nav_b64.txt', b64);
        break;
    }
}
