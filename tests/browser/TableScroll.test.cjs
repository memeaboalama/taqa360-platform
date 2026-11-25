const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const source = fs.readFileSync(path.join(__dirname, '../../public/assets/vendor/js/table-scroll.js'), 'utf8');

function wrapper(width, tableWidths, padding = 0) {
    const values = new Map();
    const element = {
        clientWidth: width, scrollWidth: width, padding,
        style: {
            getPropertyValue: key => values.get(key)?.value ?? '',
            getPropertyPriority: key => values.get(key)?.priority ?? '',
            setProperty: (key, value, priority) => values.set(key, { value, priority }),
        },
        querySelectorAll: () => element.tables,
    };
    element.tables = tableWidths.map(width => ({
        width, closest: () => element,
        getBoundingClientRect() { return { width: this.width }; },
    }));
    return element;
}

function setup(wrappers) {
    const events = {}, frames = [], observed = [];
    let mutationCallback, resizeCallback, livewireCallback;
    class ResizeObserver {
        constructor(callback) { resizeCallback = callback; }
        observe(element) { observed.push(element); }
    }
    class MutationObserver {
        constructor(callback) { mutationCallback = callback; }
        observe() {}
    }
    const document = { body: {}, querySelectorAll: () => wrappers, addEventListener() {} };
    const window = {
        ResizeObserver, MutationObserver,
        getComputedStyle: element => ({ paddingLeft: String(element.padding), paddingRight: String(element.padding) }),
        requestAnimationFrame: callback => frames.push(callback),
        addEventListener: (name, callback) => { events[name] = callback; },
        Livewire: { hook: (name, callback) => { livewireCallback = callback; } },
    };
    const context = vm.createContext({ window, document, ResizeObserver, MutationObserver, WeakSet });
    vm.runInContext(source, context);
    return {
        observed,
        resize: () => resizeCallback(),
        mutate: () => mutationCallback(),
        livewire: () => livewireCallback(),
        flush: () => { while (frames.length) frames.shift()(); },
        repeat: () => vm.runInContext(source, context),
    };
}

test('fitting tables have no scrollbar even when menus extend the wrapper scroll width', () => {
    const element = wrapper(800, [800]);
    element.scrollWidth = 1200;
    setup([element]);
    assert.equal(element.style.getPropertyValue('overflow-x'), 'visible');
    assert.equal(element.style.getPropertyPriority('overflow-x'), 'important');
});

test('tables wider than their available padded area can scroll', () => {
    const element = wrapper(800, [780], 20);
    setup([element]);
    assert.equal(element.style.getPropertyValue('overflow-x'), 'auto');
});

test('scrollbars appear and disappear when the available width changes', () => {
    const element = wrapper(900, [800]);
    const page = setup([element]);
    element.clientWidth = 600;
    page.resize(); page.flush();
    assert.equal(element.style.getPropertyValue('overflow-x'), 'auto');
    element.clientWidth = 1000;
    page.resize(); page.flush();
    assert.equal(element.style.getPropertyValue('overflow-x'), 'visible');
});

test('nested wrappers only scroll at the container nearest the table', () => {
    const inner = wrapper(500, [900]);
    const outer = wrapper(500, []);
    outer.tables = inner.tables;
    setup([outer, inner]);
    assert.equal(outer.style.getPropertyValue('overflow-x'), 'visible');
    assert.equal(inner.style.getPropertyValue('overflow-x'), 'auto');
});

test('Livewire content updates and hidden tabs recalculate table overflow', () => {
    const element = wrapper(0, [700]);
    const page = setup([element]);
    assert.equal(element.style.getPropertyValue('overflow-x'), '');
    element.clientWidth = 800;
    page.mutate(); page.flush();
    assert.equal(element.style.getPropertyValue('overflow-x'), 'visible');
    element.tables[0].width = 950;
    page.livewire(); page.flush();
    assert.equal(element.style.getPropertyValue('overflow-x'), 'auto');
});

test('rounding differences do not create unnecessary scrollbars and unrelated containers stay unchanged', () => {
    const fitting = wrapper(800, [800.5]);
    const unrelated = wrapper(500, []);
    const page = setup([fitting, unrelated]);
    page.repeat();
    assert.equal(fitting.style.getPropertyValue('overflow-x'), 'visible');
    assert.equal(unrelated.style.getPropertyValue('overflow-x'), '');
    assert.equal(page.observed.length, 2);
});
