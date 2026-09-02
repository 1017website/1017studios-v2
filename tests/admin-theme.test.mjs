import { test } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const read = file => fs.readFileSync(new URL('../' + file, import.meta.url), 'utf8');
const css = read('public/css/admin.css');
const tokens = Object.fromEntries([...css.matchAll(/(--[\w-]+):\s*(#[\da-f]{6}|var\(--[\w-]+\));/gi)].map(match => [match[1], match[2]]));
function color(value) {
    if (value.startsWith('--')) return color(tokens[value]);
    if (value.startsWith('var(')) return color(value.slice(4, -1));
    return value;
}
function luminance(value) {
    const linear = color(value).slice(1).match(/../g).map(hex => {
        const channel = parseInt(hex, 16) / 255;
        return channel <= .04045 ? channel / 12.92 : ((channel + .055) / 1.055) ** 2.4;
    });
    return linear[0] * .2126 + linear[1] * .7152 + linear[2] * .0722;
}
function contrast(foreground, background) {
    const values = [luminance(foreground), luminance(background)].sort((a, b) => b - a);
    return (values[0] + .05) / (values[1] + .05);
}
function walk(dir) {
    return fs.readdirSync(dir, { withFileTypes: true }).flatMap(entry => {
        const file = path.join(dir, entry.name);
        return entry.isDirectory() ? walk(file) : [file];
    });
}

test('primary, secondary, muted, and status text meet the 4.5:1 palette target', () => {
    for (const foreground of ['--cms-text', '--cms-secondary', '--cms-muted', '--cms-danger', '--cms-success', '--cms-accent']) {
        for (const background of ['--bg', '--bg-2', '--bg-3', '--cms-input']) {
            assert.ok(contrast(foreground, background) >= 4.5, `${foreground} on ${background}`);
        }
    }
});

test('table and control borders meet the 3:1 palette target', () => {
    for (const foreground of ['--cms-border', '--cms-border-strong']) {
        for (const background of ['--bg', '--bg-2', '--bg-3', '--cms-input']) {
            assert.ok(contrast(foreground, background) >= 3, `${foreground} on ${background}`);
        }
    }
});

test('text on filled actions and badges remains legible', () => {
    for (const [foreground, background] of [
        ['--bg', '--cms-text'], ['--cms-text', '#363d48'],
        ['--cms-danger', '#352326'], ['--cms-danger', '#482b2e'], ['--cms-accent', '#302a20'],
    ]) assert.ok(contrast(foreground, background) >= 4.5, `${foreground} on ${background}`);
});

test('all theme rules are scoped to CMS and public views do not load the stylesheet', () => {
    const withoutComments = css.replace(/\/\*[\s\S]*?\*\//g, '');
    for (const [, selectors] of withoutComments.matchAll(/([^{}]+)\{[^{}]*\}/g)) {
        for (const selector of selectors.split(/,(?![^(]*\))/)) {
            assert.match(selector.trim(), /^(?:html)?\.cms-theme\b/);
        }
    }
    assert.doesNotMatch(css, /!important/);
    for (const file of ['resources/views/layouts/app.blade.php', 'resources/views/home/index.blade.php']) {
        assert.doesNotMatch(read(file), /css\/admin\.css|class="cms-theme"/);
    }
});

test('admin and login load the cache-versioned stylesheet after their inline styles', () => {
    for (const file of ['resources/views/layouts/admin.blade.php', 'resources/views/admin/auth/login.blade.php']) {
        const source = read(file);
        assert.match(source, /<html[^>]*class="cms-theme"/);
        assert.ok(source.indexOf("asset('css/admin.css')") > source.indexOf('</style>'));
        assert.match(source, /filemtime\(public_path\('css\/admin.css'\)\)/);
    }
});

test('CMS templates no longer use faint neutral text declarations', () => {
    const files = walk(fileURLToPath(new URL('../resources/views/admin/', import.meta.url))).filter(file => file.endsWith('.blade.php'));
    for (const file of files) {
        const source = fs.readFileSync(file, 'utf8');
        assert.doesNotMatch(source, /(?<![\w-])color\s*:\s*rgba\(\s*(?:255,255,255|240,237,232),\s*0?\.[1-6]\d*\)/, file);
    }
});

test('light-background search previews retain their own dark text colors', () => {
    const source = read('resources/views/admin/seo.blade.php');
    for (const hex of ['#1a0dab', '#006621', '#545454']) assert.ok(source.includes('color:' + hex));
});
