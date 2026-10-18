const fs = require('fs');
const path = require('path');

const dist = path.join(__dirname, '..', 'dist');
const pub = path.join(__dirname, '..', 'public');

if (fs.existsSync(dist)) {
    fs.rmSync(dist, { recursive: true, force: true });
}
fs.mkdirSync(dist, { recursive: true });

// Copy static subdirectories and assets only (never index.php, no symlinks)
const items = ['build', 'images', 'favicon.svg', 'favicon.ico', 'robots.txt'];

for (const item of items) {
    const src = path.join(pub, item);
    const dest = path.join(dist, item);
    if (fs.existsSync(src)) {
        fs.cpSync(src, dest, { recursive: true, dereference: true });
    }
}

console.log('Vercel static dist prepared (excluding index.php).');
