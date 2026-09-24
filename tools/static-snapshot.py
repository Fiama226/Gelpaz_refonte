#!/usr/bin/env python3
"""
Static snapshot generator for the GELPAZ IMMO refonte.

The environment used to build this refonte has no PHP runtime, so this script
renders the *real* PHP templates (pages/*.php, includes/layout.php,
includes/functions.php) through a small template interpreter and writes a
clickable, multi-page preview into docs/preview/.

It is a verification tool, never part of the site: /tools/ is denied by
.htaccess and nothing here runs in production. Any construct the interpreter
cannot evaluate raises loudly instead of emitting silently wrong markup.

Usage:  python3 tools/static-snapshot.py
"""

from __future__ import annotations

import html
import pathlib
import json
import re
import sys
import urllib.parse

ROOT = pathlib.Path(__file__).resolve().parent.parent
OUT = ROOT / 'docs' / 'preview'
WA_NUMBER = '22667308185'
THIS_YEAR = '2026'


class SnapshotError(RuntimeError):
    pass


# --------------------------------------------------------------------------- #
# 1. Tokenizer + expression evaluator (the PHP subset used by the templates)
# --------------------------------------------------------------------------- #

TOKEN_RE = re.compile(r"""
    (?P<ws>\s+)
  | (?P<var>\$[A-Za-z_][A-Za-z0-9_]*)
  | (?P<num>\d+(?:\.\d+)?)
  | (?P<str>'(?:\\.|[^'\\])*'|"(?:\\.|[^"\\])*")
  | (?P<name>[A-Za-z_][A-Za-z0-9_]*)
  | (?P<op>\?\?|===|!==|==|!=|=>|\?\:|&&|\|\||[\[\](){},.:;?!+\-*])
""", re.X)


def tokenize(code: str) -> list[tuple[str, str]]:
    tokens, pos = [], 0
    while pos < len(code):
        m = TOKEN_RE.match(code, pos)
        if not m:
            raise SnapshotError(f'cannot tokenize: {code[pos:pos + 40]!r}')
        pos = m.end()
        if m.lastgroup != 'ws':
            tokens.append((m.lastgroup, m.group()))
    return tokens


def unescape(raw: str) -> str:
    return (raw.replace("\\'", "'").replace('\\"', '"').replace('\\\\', '\\')
                .replace('\\n', '\n').replace('\\t', '\t'))


def truthy(value) -> bool:
    return len(value) > 0 if isinstance(value, (dict, list)) else bool(value)


def to_str(value) -> str:
    if value is None:
        return ''
    if value is True:
        return '1'
    if value is False:
        return ''
    if isinstance(value, float) and value.is_integer():
        return str(int(value))
    return str(value)


class Parser:
    def __init__(self, code: str, ctx: dict):
        self.tokens = tokenize(code)
        self.i = 0
        self.ctx = ctx

    def peek(self, offset: int = 0):
        return self.tokens[self.i + offset] if self.i + offset < len(self.tokens) else (None, None)

    def next(self):
        tok = self.peek()
        self.i += 1
        return tok

    def accept(self, value: str) -> bool:
        if self.peek()[1] == value:
            self.i += 1
            return True
        return False

    def expect(self, value: str):
        if not self.accept(value):
            raise SnapshotError(f'expected {value!r} near {self.tokens[self.i:self.i + 4]}')

    def parse(self):
        value = self.ternary()
        if self.i != len(self.tokens):
            raise SnapshotError(f'unparsed tokens: {self.tokens[self.i:]}')
        return value

    def ternary(self):
        cond = self.null_coalesce()
        if self.accept('?'):
            if self.accept(':'):
                return cond if truthy(cond) else self.ternary()
            then = self.ternary()
            self.expect(':')
            other = self.ternary()
            return then if truthy(cond) else other
        return cond

    def null_coalesce(self):
        left = self.or_expr()
        while self.accept('??'):
            right = self.or_expr()
            if left is None:
                left = right
        return left

    def or_expr(self):
        left = self.and_expr()
        while self.accept('||'):
            right = self.and_expr()          # always parsed: PHP evaluates both sides
            left = truthy(left) or truthy(right)
        return left

    def and_expr(self):
        left = self.equality()
        while self.accept('&&'):
            right = self.equality()          # always parsed, even when left is falsy
            left = truthy(left) and truthy(right)
        return left

    def equality(self):
        left = self.additive()
        while True:
            if self.accept('==='):
                left = left == self.additive()
            elif self.accept('!=='):
                left = left != self.additive()
            elif self.accept('=='):
                left = to_str(left) == to_str(self.additive())
            elif self.accept('!='):
                left = to_str(left) != to_str(self.additive())
            else:
                return left

    def additive(self):
        left = self.unary()
        while True:
            if self.accept('.'):
                left = to_str(left) + to_str(self.unary())
            elif self.accept('+'):
                left = self.numeric(left) + self.numeric(self.unary())
            else:
                return left

    def numeric(self, value):
        return value if isinstance(value, (int, float)) else 0

    def unary(self):
        if self.accept('!'):
            return not truthy(self.unary())
        if self.accept('-'):
            return -self.numeric(self.unary())
        return self.postfix()

    def postfix(self):
        value = self.primary()
        while self.accept('['):
            key = self.ternary()
            self.expect(']')
            if isinstance(value, dict):
                value = value.get(key)
            elif isinstance(value, list):
                try:
                    value = value[int(key)]
                except (IndexError, ValueError, TypeError):
                    value = None
            else:
                raise SnapshotError(f'cannot index {type(value).__name__}')
        return value

    def primary(self):
        kind, value = self.next()
        if kind == 'num':
            return float(value) if '.' in value else int(value)
        if kind == 'str':
            return unescape(value[1:-1])
        if kind == 'var':
            return self.ctx.get(value[1:])
        if kind == 'op' and value == '(':
            kind2, value2 = self.peek()
            if kind2 == 'name' and value2.lower() in CASTS and self.peek(1)[1] == ')':
                self.next(); self.next()                       # (string) / (int) / …
                return CASTS[value2.lower()](self.unary())
            inner = self.ternary()
            self.expect(')')
            return inner
        if kind == 'op' and value == '[':
            return self.array_literal()
        if kind == 'name' and value.lower() in ('true', 'false', 'null'):   # PHP literals
            return {'true': True, 'false': False, 'null': None}[value.lower()]
        if kind == 'name':
            if self.peek()[1] == '(':
                self.expect('(')
                args = self.arguments()
                return self.call(value, args)
            return value                              # PHP constant such as FILTER_VALIDATE_EMAIL
        raise SnapshotError(f'unexpected token {kind}:{value!r}')

    def arguments(self) -> list:
        args = []
        if self.accept(')'):
            return args
        while True:
            args.append(self.ternary())
            if self.accept(','):
                if self.accept(')'):
                    return args
                continue
            self.expect(')')
            return args

    def array_literal(self) -> dict:
        items, auto = {}, 0
        if self.accept(']'):
            return items
        while True:
            first = self.ternary()
            if self.accept('=>'):
                items[first] = self.ternary()
            else:
                items[auto] = first
                auto += 1
            if self.accept(','):
                if self.accept(']'):
                    return items
                continue
            self.expect(']')
            return items

    def call(self, name: str, args: list):
        fn = FUNCTIONS.get(name)
        if fn is not None:
            return fn(*args)
        if name in REAL_FUNCTIONS:             # a real repo function with a `return`
            return call_real_function(name, args)
        raise SnapshotError(f'unsupported function: {name}()')


def evaluate(code: str, ctx: dict):
    return Parser(code, ctx).parse()


# --------------------------------------------------------------------------- #
# 2. Statement execution
# --------------------------------------------------------------------------- #

def split_statements(code: str) -> list[str]:
    parts, buf, depth, quote, i = [], '', 0, None, 0
    while i < len(code):
        ch = code[i]
        if quote:
            buf += ch
            if ch == '\\':
                buf += code[i + 1:i + 2]
                i += 2
                continue
            if ch == quote:
                quote = None
        elif ch in "'\"":
            quote = ch
            buf += ch
        elif ch in '([{':
            depth += 1
            buf += ch
        elif ch in ')]}':
            depth -= 1
            buf += ch
            if ch == '}' and depth == 0 and not re.match(
                    r'\s*(else|elseif|while|catch|finally)\b', code[i + 1:]):
                parts.append(buf)          # `if (a) { … }  if (b) { … }` = two statements
                buf = ''
        elif ch == ';' and depth == 0:
            parts.append(buf)
            buf = ''
        else:
            buf += ch
        i += 1
    if buf.strip():
        parts.append(buf)
    return [p.strip() for p in parts if p.strip()]


def strip_comments(code: str) -> str:
    """Remove PHP comments while respecting string literals (URLs contain //)."""
    out, i, quote = [], 0, None
    while i < len(code):
        ch = code[i]
        if quote:
            out.append(ch)
            if ch == '\\' and i + 1 < len(code):
                out.append(code[i + 1])
                i += 2
                continue
            if ch == quote:
                quote = None
            i += 1
            continue
        if ch in "'\"":
            quote = ch
            out.append(ch)
            i += 1
            continue
        if code.startswith('/*', i):
            end = code.find('*/', i + 2)
            i = len(code) if end < 0 else end + 2
            continue
        if code.startswith('//', i):
            end = code.find('\n', i)
            i = len(code) if end < 0 else end
            continue
        out.append(ch)
        i += 1
    return ''.join(out)


ASSIGN_RE = re.compile(r'^\$([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.+)$', re.S)


def execute(code: str, ctx: dict) -> str:
    """Run PHP statements; return any markup the calls produced."""
    output = []
    for stmt in split_statements(strip_comments(code)):
        if not stmt or stmt in ('}', '?>'):
            continue
        if stmt.startswith(('global ', 'require', 'include')):
            continue
        if stmt.startswith('return'):
            raise ReturnSignal(evaluate(stmt[6:].rstrip(';').strip(), ctx) if stmt[6:].strip(' ;') else None)
        if re.match(r'^(if|foreach|while)\s*\(', stmt):
            output.append(run_inline_statement(stmt, ctx))
            continue
        if stmt.startswith(('if ', 'foreach ', 'while ', 'switch ')):
            raise SnapshotError(f'unsupported block statement: {stmt[:110]!r}')
        if stmt.startswith(('else', 'endif', 'endforeach', '}', '?>', 'case ', 'break')):
            continue
        if stmt.startswith('static '):
            stmt = stmt[7:].strip()
        if stmt.startswith('unset('):
            target = stmt[6:].rstrip(');').strip()
            if '[' in target:
                name, key = target.split('[', 1)
                container = ctx.get(name.lstrip('$'))
                if isinstance(container, dict):
                    container.pop(unescape(key.rstrip(']').strip('\'"')), None)
            else:
                ctx.pop(target.lstrip('$'), None)
            continue
        m = APPEND_ASSIGN_RE.match(stmt)
        if m:
            name, op, expr = m.group(1), m.group(2), m.group(3)
            previous = ctx.get(name)
            value = evaluate(expr, ctx)
            if op == '.=':
                ctx[name] = ('' if previous is None else str(previous)) + ('' if value is None else str(value))
            elif op == '+=':
                ctx[name] = (previous or 0) + (value or 0)
            elif op == '-=':
                ctx[name] = (previous or 0) - (value or 0)
            continue
        m = ASSIGN_RE.match(stmt)
        if m:
            ctx[m.group(1)] = evaluate(m.group(2), ctx)
            continue
        if stmt.startswith(('echo ', 'print ')):          # echo a, b;  /  echo $x;
            start = 4 if stmt.startswith('echo') else 5
            for chunk in split_top_level(stmt[start:].rstrip(';'), ','):
                if chunk.strip():
                    output.append(php_string(evaluate(chunk, ctx)))
            continue
        if stmt.endswith('++'):
            name = stmt[:-2].strip().lstrip('$')
            ctx[name] = (ctx.get(name) or 0) + 1
            continue
        value = evaluate(stmt if stmt.endswith(')') else stmt.rstrip(';'), ctx)
        if isinstance(value, str) and value:
            output.append(value)
    return ''.join(output)


APPEND_ASSIGN_RE = re.compile(r'^\$([A-Za-z_][A-Za-z0-9_]*)\s*(\.=|\+=|-=)\s*(.+)$', re.S)


def match_bracket(code: str, start: int, open_char: str = '(', close_char: str = ')') -> int:
    """Index just past the bracket that closes the one at `start`."""
    depth, quote = 0, ''
    for index in range(start, len(code)):
        char = code[index]
        if quote:
            if char == quote and code[index - 1] != '\\':
                quote = ''
            continue
        if char in '"\'':
            quote = char
        elif char == open_char:
            depth += 1
        elif char == close_char:
            depth -= 1
            if depth == 0:
                return index + 1
    raise SnapshotError(f'unmatched {open_char!r} in {code[:80]!r}')


def run_inline_statement(stmt: str, ctx: dict) -> str:
    """Execute `if (…) { … } else { … }` / `foreach (…) { … }` written in one segment."""
    match = re.match(r'^(if|foreach|while)\s*\(', stmt)
    if not match:
        raise SnapshotError(f'unsupported block statement: {stmt[:100]!r}')
    keyword = match.group(1)
    condition_end = match_bracket(stmt, stmt.index('(', match.end() - 1))
    condition = stmt[match.end():condition_end - 1]
    rest = stmt[condition_end:].strip()
    if keyword == 'while':                       # `do { … } while (…);` reuse
        while truthy(evaluate(condition, ctx)):
            pass
        return ''
    if not rest.startswith('{'):
        raise SnapshotError(f'missing block after {keyword}: {stmt[:100]!r}')
    body_end = match_bracket(rest, 0, '{', '}')
    body = rest[1:body_end - 1]
    tail = rest[body_end:].strip().lstrip(';').strip()
    if keyword == 'foreach':
        return render_loop(f'foreach ({condition}) {{ {body} }}', [], ctx)
    if truthy(evaluate(condition, ctx)):
        return execute(body, ctx)
    if tail.startswith('else'):
        tail = tail[4:].strip()
        if tail.startswith('if'):
            return run_inline_statement(tail, ctx)
        if tail.startswith('{'):
            else_end = match_bracket(tail, 0, '{', '}')
            return execute(tail[1:else_end - 1], ctx)
    return ''


class ReturnSignal(Exception):
    """Carries a PHP `return` value out of execute()."""

    def __init__(self, value):
        super().__init__('return')
        self.value = value


def php_string(value) -> str:
    if value is None or value is False:
        return ''
    if value is True:
        return '1'
    if isinstance(value, float) and value == int(value):
        return str(int(value))
    return str(value)


def split_top_level(code: str, separator: str) -> list[str]:
    """Split on `separator` only outside brackets, quotes and nested calls."""
    parts, depth, quote, current = [], 0, '', ''
    for char in code:
        if quote:
            current += char
            if char == quote and not current.endswith('\\' + quote):
                quote = ''
            continue
        if char in '"\'':
            quote = char
        elif char in '([{':
            depth += 1
        elif char in ')]}':
            depth -= 1
        elif char == separator and depth == 0:
            parts.append(current)
            current = ''
            continue
        current += char
    parts.append(current)
    return parts


# --------------------------------------------------------------------------- #
# 3. Template walker
# --------------------------------------------------------------------------- #

SEGMENT_RE = re.compile(r'<\?php(.*?)\?>|<\?=(.*?)\?>', re.S)


def segments(text: str) -> list[tuple[str, str]]:
    out, pos = [], 0
    for m in SEGMENT_RE.finditer(text):
        if m.start() > pos:
            out.append(('text', text[pos:m.start()]))
        out.append(('php', m.group(1)) if m.group(1) is not None else ('echo', m.group(2)))
        pos = m.end()
    if pos < len(text):
        out.append(('text', text[pos:]))
    return normalize_segments(out)


def normalize_segments(segs: list[tuple[str, str]]) -> list[tuple[str, str]]:
    """Rewrite brace-delimited template blocks into colon style.

    Templates mix `<?php foreach ($x as $y): ?> … <?php endforeach; ?>` with
    `<?php foreach ($x as $y) { ?> … <?php } ?>`; the walker only needs one form.
    """
    out = list(segs)
    stack: list[str] = []
    for index, (kind, content) in enumerate(out):
        if kind != 'php':
            continue
        code = strip_comments(content).strip()
        if not code:
            continue
        opens = re.match(r'^(foreach|if|elseif|else)\b(.*?)\{\s*$', code, re.S)
        if opens and '}' not in code:
            keyword, rest = opens.group(1), opens.group(2).strip()
            tail = '' if keyword == 'else' else rest.lstrip()
            out[index] = ('php', f' {keyword} {tail}: '.replace('  ', ' '))
            stack.append('endforeach' if keyword == 'foreach' else 'endif')
            continue
        closes = re.match(r'^\}\s*(elseif|else)\b(.*?)\{\s*$', code, re.S)
        if closes:
            keyword, rest = closes.group(1), closes.group(2).strip()
            out[index] = ('php', f' {keyword} {rest}: '.replace('  ', ' '))
            continue
        if code in ('}', '};'):
            if stack:
                out[index] = ('php', f' {stack.pop()}; ')
            continue
    if stack:
        raise SnapshotError(f'unbalanced brace-style block(s): {stack}')
    return out


def head_of(code: str) -> str:
    return strip_comments(code).strip().rstrip(';').strip()


def keyword_of(head: str) -> str:
    """'else:' / 'foreach (' / 'endif;' -> 'else' / 'foreach' / 'endif'"""
    return re.split(r'[\s(:]', head.strip(), 1)[0] if head.strip() else ''


def render(segs: list[tuple[str, str]], ctx: dict) -> str:
    text, _ = _render(segs, ctx, 0)
    return text


def _render(segs, ctx, i: int):
    out = []
    while i < len(segs):
        kind, content = segs[i]
        if kind == 'text':
            out.append(content)
            i += 1
            continue
        if kind == 'echo':
            out.append(to_str(evaluate(content, ctx)))
            i += 1
            continue

        head = head_of(content)
        keyword = keyword_of(head)
        if keyword in ('endforeach', 'endif', 'else', 'elseif'):
            return ''.join(out), i

        if head.startswith('foreach'):
            if '{' in content:                       # inline: foreach (...) { ... }
                out.append(render_loop(head, [], ctx))
                i += 1
                continue
            body, i = collect_until(segs, i, 'foreach', 'endforeach')
            out.append(render_loop(head, body, ctx))
            continue

        if head.startswith('if') and re.search(r'\)\s*\{', content):
            out.append(execute_inline_if(head, ctx))  # inline: if (...) { ... }
            i += 1
            continue

        if head.startswith('if'):
            branches, i = collect_if(segs, i)
            for cond, body in branches:
                if cond == 'true' or truthy(evaluate(cond, ctx)):
                    out.append(render(body, ctx))
                    break
            continue

        out.append(execute(content, ctx))
        i += 1
    return ''.join(out), i


def collect_until(segs, start: int, opener: str, closer: str):
    depth, j, body = 1, start + 1, []
    while j < len(segs):
        if segs[j][0] == 'php':
            head = head_of(segs[j][1])
            if head.startswith(opener):
                depth += 1
            elif head.startswith(closer):
                depth -= 1
                if depth == 0:
                    return body, j + 1
        body.append(segs[j])
        j += 1
    raise SnapshotError(f'unclosed {opener}')


def collect_if(segs, start: int):
    branches, current, body, depth, j = [], None, [], 1, start
    while j < len(segs):
        kind, content = segs[j]
        if kind == 'php':
            head = head_of(content)
            keyword = keyword_of(head)
            if keyword == 'if':
                if j != start:
                    depth += 1
                elif j == start:
                    m = re.match(r'if\s*\((.*)\)\s*:?\s*$', head, re.S)
                    if not m:
                        raise SnapshotError(f'unsupported if: {head[:80]!r}')
                    current = m.group(1)
                    j += 1
                    continue
            elif depth == 1 and keyword == 'elseif':
                branches.append((current, body))
                current, body = re.match(r'elseif\s*\((.*)\)\s*:?\s*$', head, re.S).group(1), []
                j += 1
                continue
            elif depth == 1 and keyword == 'else':
                branches.append((current, body))
                current, body = 'true', []
                j += 1
                continue
            elif keyword == 'endif':
                depth -= 1
                if depth == 0:
                    branches.append((current, body))
                    return branches, j + 1
        body.append(segs[j])
        j += 1
    raise SnapshotError('unclosed if')


def execute_inline_if(header: str, ctx: dict) -> str:
    m = re.match(r'if\s*\((.*?)\)\s*\{(.*)\}\s*$', header, re.S)
    if not m:
        raise SnapshotError(f'unsupported inline if: {header[:80]!r}')
    return execute(m.group(2), ctx) if truthy(evaluate(m.group(1), ctx)) else ''


def render_loop(header: str, body: list, ctx: dict) -> str:
    m = re.match(r'foreach\s*\((.*?)\s+as\s+(.*?)\)\s*(?:\{(.*)\}|:)?$', header, re.S)
    if not m:
        raise SnapshotError(f'unsupported foreach: {header[:90]!r}')
    collection = evaluate(m.group(1), ctx)
    if collection is None:
        raise SnapshotError(f'foreach over missing value: {m.group(1)!r}')
    items = list(collection.values()) if isinstance(collection, dict) else list(collection)

    target = m.group(2).strip()
    key_var = value_var = None
    if '=>' in target:
        key_var, value_var = [t.strip().lstrip('$') for t in target.split('=>')]
    else:
        value_var = target.lstrip('$')

    inline = m.group(3)
    rendered = []
    for key, item in enumerate(items):
        local = dict(ctx)
        local[value_var] = item
        if key_var:
            local[key_var] = key
        rendered.append(execute(inline, local) if inline is not None else render(body, local))
    return ''.join(rendered)


# --------------------------------------------------------------------------- #
# 4. Helpers mirrored from includes/functions.php
# --------------------------------------------------------------------------- #

#: (page key, label shown in the review bar)
SNAPSHOT_PAGES = [
    ('home', 'Accueil'), ('properties', 'Logements'), ('blog', 'Actualités'),
    ('about', 'À propos'), ('services', 'Activités'), ('team', 'Équipe'),
    ('pricing', 'Tarifs'), ('faq', 'FAQ'), ('contact', 'Contact'), ('legal', 'Légal'),
    ('404', 'Page 404'),
]

PAGE_FILES = {
    'home': 'index.html', 'about': 'about.html', 'services': 'services.html',
    'properties': 'properties.html', 'blog': 'blog.html', 'contact': 'contact.html',
    'team': 'team.html', 'faq': 'faq.html', 'pricing': 'pricing.html',
    'legal': 'legal.html', '404': '404.html',
}
PROPERTY_FILES = {
    'modele-f4c': 'property-f4c.html', 'modele-f3a': 'property-f3a.html',
    'modele-f4b': 'property-f4b.html', 'modele-f4a': 'property-f4a.html',
    'f5-haut-standing': 'property-f5.html', 'villa-bassinko': 'property-bassinko.html',
}
POST_FILES = {
    'restitution-plans-developpement-urbain': 'article-plans-urbains.html',
    'signature-pv-developpement-urbain': 'article-pv-urbain.html',
    'promotion-immobiliere-40-societes-agreees': 'article-40-societes.html',
    'tendances-marche-immobilier-ouagadougou': 'article-tendances.html',
    'construire-patrimoine-immobilier-durable': 'article-patrimoine.html',
    'nouvelle-victoire-gelpaz-immo': 'article-victoire.html',
}

RUNTIME: dict = {'ctx': {}}


def normalize(value):
    """PHP list arrays are parsed as int-keyed dicts; turn them into lists."""
    if isinstance(value, dict):
        if value and all(isinstance(k, int) for k in value):
            return [normalize(value[k]) for k in sorted(value)]
        return {k: normalize(v) for k, v in value.items()}
    if isinstance(value, list):
        return [normalize(v) for v in value]
    return value


def p_empty(value) -> bool:
    return not truthy(value)


def p_e(value) -> str:
    return html.escape(to_str(value), quote=True)


def p_icon(name, extra='') -> str:
    safe = re.sub(r'[^a-z0-9\-]', '', to_str(name).lower())
    cls = f' icon--{safe}' + (f' {extra}' if extra else '')
    return (f'<svg class="icon{cls}" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" '
            f'focusable="false"><use href="#i-{safe}" xlink:href="#i-{safe}"></use></svg>')


def p_page_url(page='home', params=None) -> str:
    params = params or {}
    if page == 'property':
        return PROPERTY_FILES.get(to_str(params.get('id')), 'property-f4c.html')
    if page == 'post':
        return POST_FILES.get(to_str(params.get('slug')), 'article.html')
    return PAGE_FILES.get(page, 'index.html')


def p_img_url(url) -> str:
    return to_str(url).replace('/assets/', '../assets/')


def p_whatsapp_link(text) -> str:
    return f'https://wa.me/{WA_NUMBER}?text=' + urllib.parse.quote(to_str(text), safe='')


def p_initials(name) -> str:
    parts = to_str(name).split()
    return ''.join(p[0].upper() for p in parts[:2]) or 'G'


def p_image_small(url) -> str:
    url = to_str(url)
    return url.replace('-835x467', '-525x328') if '-835x467' in url else ''


def p_image_attrs(url, sizes='100vw') -> str:
    url, small = to_str(url), p_image_small(url)
    attrs = f' sizes="{sizes}"'
    if small:
        attrs += f' srcset="{url} 835w, {small} 525w" data-fallback="{small}"'
    return attrs


def p_image_preload_attrs(url, sizes='100vw') -> str:
    url, small = to_str(url), p_image_small(url)
    return f' imagesrcset="{url} 835w, {small} 525w" imagesizes="{sizes}"' if small else ''


CASTS = {
    'string': lambda value: '' if value is None else str(value),
    'int': lambda value: int(value or 0),
    'integer': lambda value: int(value or 0),
    'float': lambda value: float(value or 0),
    'bool': lambda value: truthy(value),
    'boolean': lambda value: truthy(value),
    'array': lambda value: normalize(value),
}


def p_isset(value=None) -> bool:
    return value is not None


def p_http_build_query(params) -> str:
    params = params or {}
    return '&'.join(f'{urllib.parse.quote(str(k))}={urllib.parse.quote(str(v))}'
                    for k, v in normalize(params).items())


def p_mb_substr(value, start, length=None) -> str:
    text = to_str(value)
    return text[start:] if length is None else text[start:start + length]


def p_json_encode(value, flags=None) -> str:
    return json.dumps(value, ensure_ascii=False)


def p_asset_version(relative) -> str:
    file = ROOT / to_str(relative).lstrip('/')
    return str(int(file.stat().st_mtime)) if file.is_file() else '1'


def p_map_embed_url(label='GELPAZ IMMO', lat=12.3686, lon=-1.5275, span=0.012) -> str:
    bbox = '%2C'.join(str(round(v, 4)) for v in (lon - span, lat - span / 2, lon + span, lat + span / 2))
    return f'https://www.openstreetmap.org/export/embed.html?bbox={bbox}&layer=mapnik&marker={lat}%2C{lon}'


def p_map_link_url(lat=12.3686, lon=-1.5275) -> str:
    return f'https://www.openstreetmap.org/?mlat={lat}&mlon={lon}#map=16/{lat}/{lon}'


def p_slice(value, start, length=None):
    items = list(value.values()) if isinstance(value, dict) else list(value)
    return items[start:] if length is None else items[start:start + length]


def p_in_array(needle, haystack, strict=False) -> bool:
    items = list(haystack.values()) if isinstance(haystack, dict) else list(haystack)
    return needle in items


def p_filter_var(value, flag=None) -> bool:
    return bool(re.fullmatch(r'[^@\s]+@[^@\s]+\.[A-Za-z]{2,}', to_str(value)))


def p_count(value) -> int:
    return len(value)


def p_ucfirst(value) -> str:
    text = to_str(value)
    return text[:1].upper() + text[1:]


def p_rawurlencode(value) -> str:
    return urllib.parse.quote(to_str(value), safe='')


def p_date(fmt='Y', ts=None) -> str:
    return THIS_YEAR


def p_render_image(url, alt, sizes='100vw', cls='', lazy=True, width=835, height=467) -> str:
    return (f'<img src="{p_img_url(url)}" alt="{p_e(alt)}"'
            + (f' class="{p_e(cls)}"' if cls else '')
            + f' width="{width}" height="{height}"' + p_image_attrs(url, sizes)
            + f' loading="{"lazy" if lazy else "eager"}" decoding="async">')


# template-backed renderers (bodies extracted from the real PHP sources)
def p_render_header(hero=False) -> str:
    return call_template('render_header', {'hero': truthy(hero)})


def p_render_footer() -> str:
    return call_template('render_footer', {})


def p_render_icon_sprite() -> str:
    return call_template('render_icon_sprite', {})


def p_render_interior_hero(title, crumb, image) -> str:
    return call_template('render_interior_hero', {'title': title, 'crumb': crumb, 'image': image})


def p_render_cta_band(title='Prêt à concrétiser votre projet immobilier ?',
                      copy='Notre équipe vous accompagne avec écoute, transparence et expertise.') -> str:
    return call_template('render_cta_band', {'title': title, 'copy': copy})


def p_render_section_heading(eyebrow, title, copy='', align='left') -> str:
    return call_template('render_section_heading',
                         {'eyebrow': eyebrow, 'title': title, 'copy': copy, 'align': align})


def p_render_faqs(faqs, light=False) -> str:
    return call_template('render_faqs', {'faqs': faqs, 'light': truthy(light)})


def p_render_property_card(property_, featured=False) -> str:
    return call_template('render_property_card', {'property': property_, 'featured': truthy(featured)})


def p_render_blog_card(post, compact=False, featured=False) -> str:
    return call_template('render_blog_card',
                         {'post': post, 'compact': truthy(compact), 'featured': truthy(featured)})


def p_render_team_card(member) -> str:
    return call_template('render_team_card', {'member': member})


FUNCTIONS = {
    'e': p_e, 'icon': p_icon, 'page_url': p_page_url, 'img_url': p_img_url,
    'whatsapp_link': p_whatsapp_link, 'initials': p_initials, 'image_small': p_image_small,
    'image_attrs': p_image_attrs, 'image_preload_attrs': p_image_preload_attrs,
    'json_encode': p_json_encode,
    'asset_version': p_asset_version, 'map_embed_url': p_map_embed_url,
    'map_link_url': p_map_link_url, 'array_slice': p_slice, 'in_array': p_in_array,
    'filter_var': p_filter_var, 'count': p_count, 'ucfirst': p_ucfirst,
    'rawurlencode': p_rawurlencode, 'empty': p_empty, 'date': p_date, 'render_image': p_render_image,
    'http_build_query': p_http_build_query, 'mb_substr': p_mb_substr, 'isset': p_isset,
    'render_header': p_render_header, 'render_footer': p_render_footer,
    'render_icon_sprite': p_render_icon_sprite, 'render_interior_hero': p_render_interior_hero,
    'render_cta_band': p_render_cta_band, 'render_section_heading': p_render_section_heading,
    'render_faqs': p_render_faqs, 'render_property_card': p_render_property_card,
    'render_blog_card': p_render_blog_card, 'render_team_card': p_render_team_card,
}


# --------------------------------------------------------------------------- #
# 5. Sources
# --------------------------------------------------------------------------- #

def php_source(relative: str) -> str:
    return re.sub(r'^<\?php', '', (ROOT / relative).read_text(encoding='utf-8')).rstrip()


def function_body(source: str, name: str) -> str:
    m = re.search(r'\nfunction\s+' + re.escape(name) + r'\s*\([^)]*\)[^{]*\{', source)
    if not m:
        raise SnapshotError(f'function {name}() not found')
    depth, i, start = 1, m.end(), m.end()
    while i < len(source):
        if source[i] == '{':
            depth += 1
        elif source[i] == '}':
            depth -= 1
            if depth == 0:
                return source[start:i]
        i += 1
    raise SnapshotError(f'unclosed function {name}()')


FUNCTION_BODIES: dict[str, list] = {}

#: repository functions whose body the interpreter can execute directly
REAL_FUNCTIONS = {'page_title', 'meta_description', 'page_is', 'post_by_slug',
                  'property_by_id', 'page_url'}


def template_segments(name: str) -> list[tuple[str, str]]:
    if name not in FUNCTION_BODIES:
        if name in ('render_header', 'render_footer', 'render_interior_hero', 'render_cta_band'):
            source = 'includes/layout.php'
        else:
            source = next((f for f in PHP_SOURCES
                           if re.search(r'\nfunction\s+' + re.escape(name) + r'\s*\(', php_source(f))),
                          'includes/functions.php')
        body = function_body(php_source(source), name)
        body = re.sub(r'^\s*\?>', '', body)          # leaving PHP mode: the '?>' is a transition
        if not body.lstrip().startswith('<'):
            body = '<?php' + body                       # body still in PHP mode
        body = re.sub(r'<\?php\s*$', '', body)
        if '<?php' in body and '?>' not in body:     # pure-PHP body: close the block
            body += '\n?>'
        FUNCTION_BODIES[name] = segments(body)
    return FUNCTION_BODIES[name]


PHP_SOURCES = ('includes/functions.php', 'includes/layout.php', 'includes/bootstrap.php')


def function_params(name: str) -> list[str]:
    for source in PHP_SOURCES:
        text = php_source(source)
        m = re.search(r'\nfunction\s+' + re.escape(name) + r'\s*\(([^)]*)\)', text)
        if m:
            names = []
            for part in [p for p in m.group(1).split(',') if p.strip()]:
                names.append(part.split('=')[0].strip().split(' ')[-1].lstrip('$'))
            return names
    raise SnapshotError(f'function {name}() not found')


def call_real_function(name: str, args: list):
    """Run a real repository function (page_title, meta_description, …) and return its value."""
    params = function_params(name)
    local = dict(RUNTIME['ctx'])
    local.update(dict(zip(params, args)))
    local['GLOBALS'] = RUNTIME['ctx'].get('GLOBALS', {})
    try:
        markup = render(template_segments(name), local)
    except ReturnSignal as signal:
        return signal.value
    except SnapshotError as error:
        raise SnapshotError(f'in {name}(): {error}') from error
    return markup


def call_template(name: str, kwargs: dict) -> str:
    local = dict(RUNTIME['ctx'])
    local.update(kwargs)
    local['GLOBALS'] = RUNTIME['ctx'].get('GLOBALS', {})
    try:
        return render(template_segments(name), local)
    except SnapshotError as error:
        raise SnapshotError(f'in {name}(): {error}') from error


def build_context() -> dict:
    ctx: dict = {}
    execute(php_source('includes/data.php'), ctx)
    ctx = {k: normalize(v) for k, v in ctx.items()}
    for key in ('site', 'images', 'hero_slides', 'properties', 'services',
                'testimonials', 'posts', 'team', 'faqs'):
        if key not in ctx:
            raise SnapshotError(f'data.php did not define ${key}')
    ctx['GLOBALS'] = ctx
    return ctx


# --------------------------------------------------------------------------- #
# 6. Page rendering
# --------------------------------------------------------------------------- #

# post.php derives $post/$related with an arrow function; the same values come
# from the page state below, so those two lines are dropped before rendering.
POST_INIT_LINES = ('$post = $selected_post', '$related = array_values(array_filter')


def page_source(page: str) -> str:
    source = (ROOT / f'pages/{page}.php').read_text(encoding='utf-8')
    if page == 'post':
        source = '\n'.join(l for l in source.splitlines()
                           if not l.strip().startswith(POST_INIT_LINES))
    return source


def build_page_state(page: str, ctx: dict, override_property=None, override_post=None) -> dict:
    properties = ctx['properties']
    posts = ctx['posts']
    selected_property = override_property or properties[0]
    selected_post = override_post or posts[0]
    properties = list(properties)
    posts = list(posts)
    return {
        'site': ctx['site'], 'images': ctx['images'], 'properties': properties, 'posts': posts,
        'team': ctx['team'], 'faqs': ctx['faqs'], 'testimonials': ctx['testimonials'],
        'services': ctx['services'], 'hero_slides': ctx['hero_slides'],
        'contact_state': None,
        'contact_values': {'first_name': '', 'last_name': '', 'email': '', 'phone': '', 'message': ''},
        'visible_properties': properties, 'has_property_search': False,
        'property_filter': 'all', 'property_location': 'all', 'property_beds': 'all',
        'property_sort': 'recent',
        'selected_property': selected_property, 'selected_post': selected_post,
        'post': selected_post,
        'related': [p for p in posts if p['slug'] != selected_post['slug']],
    }


def render_page(page: str, ctx: dict, filename: str, override_property=None, override_post=None,
                state_overrides: dict | None = None) -> str:
    local = dict(ctx)
    local.update(build_page_state(page, ctx, override_property, override_post))
    local.update(state_overrides or {})
    local['current_page'] = page
    local['GLOBALS'] = {'current_page': page, 'site': ctx['site'],
                        'properties': ctx['properties'], 'posts': ctx['posts'],
                        'selected_property': local['selected_property'],
                        'selected_post': local['selected_post']}

    previous = RUNTIME['ctx']
    RUNTIME['ctx'] = local                       # render_* helpers read the page context
    try:
        body = render(segments(page_source(page)), local)
        lightbox = render(segments(page_source('gallery-lightbox')), local)
        footer = call_template('render_footer', {})
        head = render(segments(head_source()), local)
    except SnapshotError as error:
        raise SnapshotError(f'in pages/{page}.php: {error}') from error
    finally:
        RUNTIME['ctx'] = previous

    return assemble(page, ctx, body, lightbox, footer, head, filename, dict(local))


SNAPSHOT_HEAD_ADDITIONS = """
<meta name="robots" content="noindex">
<style>
  /* aperçu statique uniquement — ne fait pas partie du site livré */
  .snap-bar{position:sticky;top:0;z-index:80;display:flex;flex-wrap:wrap;gap:6px;padding:9px 14px;background:#0b1220;color:#cfe3f7;font:600 12px/1.5 'DM Sans',sans-serif}
  .snap-bar b{color:#7dc3f5;margin-right:6px}
  .snap-bar a{color:#cfe3f7;padding:5px 9px;border-radius:6px;text-decoration:none;background:rgba(255,255,255,.07)}
  .snap-bar a:hover,.snap-bar a[aria-current="page"]{background:#0876d1;color:#fff}
</style>
"""


def head_source() -> str:
    """index.php up to <body>, so the real <title>, metas and JSON-LD are reviewable."""
    source = (ROOT / 'index.php').read_text(encoding='utf-8')
    return source[:source.index('<body')]


def production_url(page: str, state: dict) -> str:
    """The real (deployed) URL of this page, straight from page_url() in the repo."""
    if page == 'property':
        return call_real_function('page_url', [page, {'id': state['selected_property']['id']}])
    if page == 'post':
        return call_real_function('page_url', [page, {'slug': state['selected_post']['slug']}])
    return call_real_function('page_url', [page, {}])


def assemble(page: str, ctx: dict, body: str, lightbox: str, footer: str, head: str,
             filename: str, state_dict: dict | None = None) -> str:
    """Rebuild the document as index.php would, with snapshot-only chrome added."""
    if body.count('<header class="site-header') != 1:
        raise SnapshotError(f'{page}: expected exactly one <header class="site-header">')
    if body.count('<footer class="site-footer"') > 1:
        raise SnapshotError(f'{page}: duplicated footer')
    if '<title' not in head or '</head>' not in head:
        raise SnapshotError(f'{page}: the real <head> did not render')

    head = head.replace('"/assets/', '"../assets/')          # favicons, CSS
    head = head.replace('https://gelpaz.comhttps://', 'https://')   # og:image is already absolute
    real_url = 'https://gelpaz.com' + production_url(page, state_dict)
    head = re.sub(r'(<link rel="canonical" href=")[^"]*(")', rf'\g<1>{real_url}\g<2>', head)
    head = re.sub(r'(<meta property="og:url" content=")[^"]*(")', rf'\g<1>{real_url}\g<2>', head)
    head = head.replace('</head>', SNAPSHOT_HEAD_ADDITIONS + '</head>')
    sprite = '' if 'icon-sprite' in body else call_template('render_icon_sprite', {})
    return f'''{head}
<body class="page-{page}">
{sprite}
<a class="skip-link" href="#main">Aller au contenu principal</a>
{snapshot_bar(page)}
{body}
{lightbox}
{footer}
<a class="whatsapp-float" href="https://wa.me/{WA_NUMBER}?text=Bonjour" target="_blank" rel="noopener" aria-label="Discuter sur WhatsApp">{p_icon('whatsapp')}<span class="whatsapp-float__label">Discuter sur WhatsApp</span></a>
<button class="back-to-top" type="button" aria-label="Retour en haut">{p_icon('arrow-up')}</button>
<script src="../assets/js/app.js"></script>
</body>
</html>
'''



def snapshot_bar(page: str) -> str:
    links = []
    for key, label in SNAPSHOT_PAGES:
        current = ' aria-current="page"' if key == page else ''
        links.append(f'<a href="{PAGE_FILES[key]}"{current}>{label}</a>')
    links.append('<a href="properties-location.html">Logements en location</a>')
    links.append('<a href="property-f4c.html">Fiche logement</a>')
    links.append(f'<a href="article-tendances.html">Article</a>')
    links.append('<a href="ui-preview.html">Composants</a>')
    return ('<nav class="snap-bar" aria-label="Aperçu statique"><b>'
            'Aperçu statique généré depuis les sources PHP :</b>' + ''.join(links) + '</nav>')


def main() -> int:
    OUT.mkdir(parents=True, exist_ok=True)
    ctx = build_context()
    RUNTIME['ctx'] = ctx
    written = []

    variants = [(page, PAGE_FILES[page], None, None, None) for page, _ in SNAPSHOT_PAGES]
    rentals = [item for item in ctx['properties'] if item['category'].lower() == 'location']
    variants.append(('properties', 'properties-location.html', None, None,
                     {'property_filter': 'location', 'visible_properties': rentals,
                      'has_property_search': True}))
    for pid, filename in PROPERTY_FILES.items():
        variants.append(('property', filename, next(p for p in ctx['properties'] if p['id'] == pid), None, None))
    for slug, filename in POST_FILES.items():
        variants.append(('post', filename, None, next(p for p in ctx['posts'] if p['slug'] == slug), None))

    for page, filename, property_, post, overrides in variants:
        document = render_page(page, ctx, filename, property_, post, overrides)
        leftovers = re.findall(r'<\?(?!xml)', document)
        if leftovers:
            raise SnapshotError(f'{filename}: {len(leftovers)} unresolved PHP tag(s)')
        (OUT / filename).write_text(document, encoding='utf-8')
        written.append(filename)

    print(f'wrote {len(written)} page(s) to {OUT.relative_to(ROOT)}')
    for name in written:
        print('  ', name)
    return 0


if __name__ == '__main__':
    sys.exit(main())
