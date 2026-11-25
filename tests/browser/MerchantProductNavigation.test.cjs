const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');

const template = fs.readFileSync(path.join(__dirname, '../../app/Application/Marketplace/resources/views/livewire/product/edit.blade.php'), 'utf8');
const script = template.match(/<script>([\s\S]*?)<\/script>/)[1]
    .replace(/@json\(__\('([^']+)'\)\)/g, (_, key) => JSON.stringify(key));

function setup() {
    const documentEvents = {}, windowEvents = {};
    const document = { activeElement: null };
    const node = (kind) => ({
        kind, isConnected: true, textContent: '',
        focus() { document.activeElement = this; },
        closest(selector) {
            if (kind === 'input' && selector === '#merchant-product-editor form') return editor;
            if (kind === 'stay' && selector === '[data-editor-stay]') return this;
            if (kind === 'confirm' && selector === '[data-editor-confirm-leave]') return this;
            if (kind === 'anchor' && selector === 'a[href]') return this;
            return null;
        },
    });
    const editor = node('editor'), stay = node('stay'), confirm = node('confirm'), status = node('status');
    const dialog = { hidden: true,
        querySelector: selector => selector === '[data-editor-confirm-leave]' ? confirm : stay,
        querySelectorAll: () => [stay, confirm],
    };
    document.getElementById = id => id === 'merchant-product-editor' ? editor : id === 'editor-leave-dialog' ? dialog : null;
    document.querySelector = selector => selector === '[data-editor-save-state]' ? status : (!dialog.hidden ? dialog : null);
    document.querySelectorAll = () => [];
    document.addEventListener = (type, handler, capture = false) => (documentEvents[type] ??= []).push({ handler, capture });
    let reloads = 0, destination = null;
    const location = Object.assign(new URL('https://example.test/merchant/products/10/edit'), {
        reload() { reloads++; }, assign(url) { destination = url; },
    });
    const window = { location, addEventListener(type, handler) { (windowEvents[type] ??= []).push(handler); } };
    vm.runInNewContext(script, { window, document, URL });
    const dispatch = (type, options = {}) => {
        const event = { target: node('other'), button: 0, defaultPrevented: false,
            preventDefault() { this.defaultPrevented = true; }, ...options };
        [...(documentEvents[type] ?? [])].sort((a, b) => Number(b.capture) - Number(a.capture)).forEach(({ handler }) => handler(event));
        return event;
    };
    const anchor = (target = '') => Object.assign(node('anchor'), {
        href: 'https://example.test/merchant/products', target, hasAttribute: () => false,
    });
    return { dialog, stay, confirm, windowEvents, anchor,
        dispatch, dirty: () => dispatch('input', { target: node('input') }),
        get reloads() { return reloads; }, get destination() { return destination; },
    };
}

test('native browser unload warnings are no longer registered', () => {
    assert.equal(setup().windowEvents.beforeunload, undefined);
});

test('dirty navigation opens the modal and cancel keeps the editor open', () => {
    const editor = setup();
    editor.dirty();
    assert.equal(editor.dispatch('click', { target: editor.anchor() }).defaultPrevented, true);
    assert.equal(editor.dialog.hidden, false);
    editor.dispatch('click', { target: editor.stay });
    assert.equal(editor.dialog.hidden, true);
    assert.equal(editor.destination, null);
});

test('confirming navigation follows the selected link', () => {
    const editor = setup();
    editor.dirty();
    editor.dispatch('click', { target: editor.anchor() });
    editor.dispatch('click', { target: editor.confirm });
    assert.equal(editor.destination, 'https://example.test/merchant/products');
});

test('refresh shortcuts wait for confirmation', () => {
    for (const shortcut of [{ key: 'F5' }, { key: 'r', ctrlKey: true }, { key: 'r', metaKey: true }]) {
        const editor = setup();
        editor.dirty();
        assert.equal(editor.dispatch('keydown', shortcut).defaultPrevented, true);
        assert.equal(editor.reloads, 0);
        editor.dispatch('click', { target: editor.confirm });
        assert.equal(editor.reloads, 1);
    }
});

test('escape cancels the pending navigation', () => {
    const editor = setup();
    editor.dirty();
    editor.dispatch('keydown', { key: 'F5' });
    editor.dispatch('keydown', { key: 'Escape' });
    assert.equal(editor.dialog.hidden, true);
    assert.equal(editor.reloads, 0);
});

test('saved changes and new-tab links do not open a confirmation', () => {
    const editor = setup();
    editor.dirty();
    assert.equal(editor.dispatch('click', { target: editor.anchor('_blank') }).defaultPrevented, false);
    editor.windowEvents['product-editor-saved'].forEach(handler => handler());
    assert.equal(editor.dispatch('click', { target: editor.anchor() }).defaultPrevented, false);
    assert.equal(editor.dialog.hidden, true);
});
