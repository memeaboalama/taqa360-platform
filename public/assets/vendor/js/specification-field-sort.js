(function () {
    'use strict';
    var activeList = null;
    var drake = null;

    function fieldKeys(list) {
        return Array.from(list.children).filter(function (row) {
            return row.hasAttribute('data-field-key');
        }).map(function (row) { return row.getAttribute('data-field-key'); });
    }

    function initialize() {
        var list = document.querySelector('[data-specification-sortable]');
        if (list === activeList) return;
        if (drake) drake.destroy();
        activeList = list;
        drake = null;
        if (!list || !window.dragula) return;

        var previousKeys = [];
        drake = window.dragula([list], {
            direction: 'vertical',
            revertOnSpill: true,
            moves: function (row, source, handle) {
                return row.hasAttribute('data-field-key')
                    && !source.classList.contains('field-list-saving')
                    && Boolean(handle.closest('.field-drag-handle'));
            },
        });
        drake.on('drag', function () { previousKeys = fieldKeys(list); });
        drake.on('drop', function () {
            var keys = fieldKeys(list);
            if (JSON.stringify(keys) === JSON.stringify(previousKeys)) return;
            var root = list.closest('[wire\\:id]');
            var component = root && window.Livewire.find(root.getAttribute('wire:id'));
            if (!component) return;
            list.classList.add('field-list-saving');
            Promise.resolve(component.call('reorderProperties', keys)).catch(function () {
                previousKeys.forEach(function (key) {
                    var row = Array.from(list.children).find(function (item) {
                        return item.getAttribute('data-field-key') === key;
                    });
                    if (row) list.appendChild(row);
                });
            }).finally(function () { list.classList.remove('field-list-saving'); });
        });
    }

    initialize();
    document.addEventListener('livewire:load', initialize);
    if (window.Livewire) window.Livewire.hook('message.processed', initialize);
})();
