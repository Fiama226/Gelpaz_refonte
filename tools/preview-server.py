#!/usr/bin/env python3
"""Serve the repository for reviewing the static snapshot (docs/preview/).

`/` redirects to the snapshot home so a reviewer lands straight on the site;
`../assets/…` references keep working because the served root is the repository.

    python3 tools/preview-server.py [port]
"""
import http.server
import os
import socketserver
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SNAPSHOT_HOME = '/docs/preview/index.html'


class Handler(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=ROOT, **kwargs)

    def do_GET(self):
        if self.path in ('/', '/index.html'):
            self.send_response(302)
            self.send_header('Location', SNAPSHOT_HOME)
            self.end_headers()
            return
        super().do_GET()

    def end_headers(self):
        if self.path.endswith(('.html', '.css', '.js')):
            self.send_header('Cache-Control', 'no-store')
        super().end_headers()

    def log_message(self, fmt, *args):
        sys.stderr.write('%s - %s\n' % (self.address_string(), fmt % args))


class Server(socketserver.ThreadingTCPServer):
    allow_reuse_address = True
    daemon_threads = True


if __name__ == '__main__':
    port = int(sys.argv[1]) if len(sys.argv) > 1 else 8000
    with Server(('0.0.0.0', port), Handler) as httpd:
        print(f'Aperçu statique : http://localhost:{port}{SNAPSHOT_HOME}', flush=True)
        httpd.serve_forever()
