import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';

const read = path => readFileSync(new URL(path, import.meta.url), 'utf8');
const partial = read('../resources/views/home/partials/clients.blade.php');
const home = read('../resources/views/home/index.blade.php');
const css = read('../public/css/app.css');
const clientStyles = css.slice(css.indexOf('.clients-section {'), css.indexOf('/* Our Client CMS */'));

test('clients reuse the section, header, typography and button styles of the existing site', () => {
    assert.match(partial, /class="section clients-section"/);
    assert.match(partial, /class="services-header clients-header"/);
    assert.match(partial, /<span class="label">Clients & Partners<\/span>/);
    assert.match(partial, /<h2 id="clients-title">Our<br><em>Client<\/em><\/h2>/);
    assert.match(clientStyles, /\.clients-heading em\s*\{[^}]*font-family: var\(--font-serif\);/);
    assert.match(partial, /class="btn">\s*<span>Work With Us<\/span>/);
    assert.doesNotMatch(clientStyles, /\.clients-heading h2\s*\{|\.clients-heading \.label\s*\{|border-left|radial-gradient/i);
    assert.doesNotMatch(clientStyles, /#ed1428|#ff4557|#0b0b0c|#f4f5f6|#d7cbcd/i);
});

test('logo rows use the shared container and warm neutral palette without recoloring uploads', () => {
    assert.match(partial, /class="container clients-logos"/);
    assert.match(clientStyles, /\.clients-viewport\s*\{[^}]*background: var\(--bg-card\);[^}]*border: 1px solid var\(--border\);/);
    assert.match(clientStyles, /\.client-logo img\s*\{[^}]*object-fit: contain;[^}]*background: var\(--off-white\);/);
    assert.doesNotMatch(clientStyles, /\bfilter\s*:/);
    assert.match(clientStyles, /\.client-logo-fallback\s*\{[^}]*color: var\(--white\);/);
});

test('section remains before services, with shared mobile layout and accessible motion behavior', () => {
    assert.ok(home.indexOf("@include('home.partials.clients')") < home.indexOf('id="services"'));
    assert.match(css, /@media \(max-width: 768px\)\s*\{[\s\S]*?\.services-header\s*\{\s*grid-template-columns: 1fr;/);
    assert.match(clientStyles, /padding-bottom: 0;/);
    assert.match(clientStyles, /@media \(prefers-reduced-motion: no-preference\)/);
    assert.match(clientStyles, /animation-direction: reverse/);
    assert.match(clientStyles, /\.clients-viewport:hover/);
    assert.match(clientStyles, /\.clients-viewport:focus-within/);
    assert.match(clientStyles, /animation-play-state: paused/);
});
