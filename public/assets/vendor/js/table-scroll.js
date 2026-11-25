(function () {
    'use strict';

    if (window.__taqaTableScrollInitialized) return;
    window.__taqaTableScrollInitialized = true;

    var wrapperSelector = '.table-responsive, .merchant-table-scroll, .overflow-x-auto';
    var observedElements = new WeakSet();
    var updateScheduled = false;
    var resizeObserver = window.ResizeObserver ? new ResizeObserver(scheduleUpdate) : null;

    function observe(element) {
        if (resizeObserver && !observedElements.has(element)) {
            resizeObserver.observe(element);
            observedElements.add(element);
        }
    }

    function setOverflow(wrapper, value) {
        if (wrapper.style.getPropertyValue('overflow-x') !== value
            || wrapper.style.getPropertyPriority('overflow-x') !== 'important') {
            wrapper.style.setProperty('overflow-x', value, 'important');
        }
    }

    function updateTableScroll() {
        document.querySelectorAll(wrapperSelector).forEach(function (wrapper) {
            var allTables = Array.prototype.slice.call(wrapper.querySelectorAll('table'));
            if (!allTables.length) return;

            var tables = allTables.filter(function (table) {
                return table.closest(wrapperSelector) === wrapper;
            });

            // A nested table already has its own scroll container.
            if (!tables.length) {
                setOverflow(wrapper, 'visible');
                return;
            }

            observe(wrapper);
            tables.forEach(observe);
            if (!wrapper.clientWidth) return;

            var style = window.getComputedStyle(wrapper);
            var availableWidth = wrapper.clientWidth
                - (parseFloat(style.paddingLeft) || 0)
                - (parseFloat(style.paddingRight) || 0);
            var hasOverflow = tables.some(function (table) {
                // Ignore overflow from toolbars, menus and nested wrappers;
                // only a table wider than its container needs a scrollbar.
                return table.getBoundingClientRect().width - availableWidth > 1;
            });

            setOverflow(wrapper, hasOverflow ? 'auto' : 'visible');
        });
    }

    function scheduleUpdate() {
        if (updateScheduled) return;
        updateScheduled = true;
        window.requestAnimationFrame(function () {
            updateScheduled = false;
            updateTableScroll();
        });
    }

    updateTableScroll();
    window.addEventListener('resize', scheduleUpdate);
    window.addEventListener('load', scheduleUpdate);
    document.addEventListener('load', scheduleUpdate, true);
    document.addEventListener('livewire:load', scheduleUpdate);
    if (window.Livewire && window.Livewire.hook) {
        window.Livewire.hook('message.processed', scheduleUpdate);
    }
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(scheduleUpdate);

    if (window.MutationObserver && document.body) {
        new MutationObserver(scheduleUpdate).observe(document.body, {
            childList: true,
            characterData: true,
            attributes: true,
            attributeFilter: ['class', 'hidden', 'colspan'],
            subtree: true,
        });
    }
})();
