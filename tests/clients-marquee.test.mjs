import { test } from 'node:test';
import assert from 'node:assert/strict';
import { initClientMarquee } from '../public/js/clients.js';

function fixture(reduced = false) {
    const classes = new Set();
    const button = {
        attributes: {}, hidden: true,
        addEventListener(type, fn) { this[type] = fn; },
        setAttribute(key, value) { this.attributes[key] = value; },
    };
    const logo = {
        complete: true, naturalWidth: 180, hidden: false, nextElementSibling: { hidden: true },
        addEventListener(type, fn) { this[type] = fn; },
    };
    const section = {
        dataset: {},
        querySelector: () => button,
        querySelectorAll: () => [logo],
        classList: {
            toggle(name, force) {
                const enabled = force ?? !classes.has(name);
                if (enabled) classes.add(name); else classes.delete(name);
                return enabled;
            },
        },
    };
    const motion = { matches: reduced, addEventListener(type, fn) { this[type] = fn; } };
    return { classes, button, logo, section, motion };
}

test('enables animation and exposes a working pause/resume button', () => {
    const f = fixture();
    initClientMarquee(f.section, f.motion);
    assert.ok(f.classes.has('clients-animated'));
    assert.equal(f.button.hidden, false);
    f.button.click();
    assert.ok(f.classes.has('is-paused'));
    assert.equal(f.button.attributes['aria-pressed'], 'true');
    assert.equal(f.button.textContent, 'Lanjutkan animasi');
    f.button.click();
    assert.equal(f.classes.has('is-paused'), false);
    assert.equal(f.button.attributes['aria-pressed'], 'false');
});

test('reduced motion uses the static grid and reacts to preference changes', () => {
    const f = fixture(true);
    initClientMarquee(f.section, f.motion);
    assert.equal(f.classes.has('clients-animated'), false);
    assert.equal(f.button.hidden, true);
    f.motion.matches = false;
    f.motion.change();
    assert.ok(f.classes.has('clients-animated'));
    f.motion.matches = true;
    f.motion.change();
    assert.equal(f.classes.has('clients-animated'), false);
});

test('a missing logo falls back to the client name', () => {
    const f = fixture();
    initClientMarquee(f.section, f.motion);
    f.logo.error();
    assert.equal(f.logo.hidden, true);
    assert.equal(f.logo.nextElementSibling.hidden, false);
});

test('already failed images also show the name fallback', () => {
    const f = fixture();
    f.logo.naturalWidth = 0;
    initClientMarquee(f.section, f.motion);
    assert.equal(f.logo.hidden, true);
});

test('initialization is idempotent and empty sections are safe', () => {
    const f = fixture();
    initClientMarquee(f.section, f.motion);
    const handler = f.button.click;
    initClientMarquee(f.section, f.motion);
    assert.equal(f.button.click, handler);
    assert.doesNotThrow(() => initClientMarquee({ querySelector: () => null }, f.motion));
});
