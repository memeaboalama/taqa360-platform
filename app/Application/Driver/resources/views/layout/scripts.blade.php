<script src="{{ asset('assets/vendor/libs/jquery/jquery-3.7.0.min.js') }}"></script>
<!-- Popper JS -->
<script src="{{ asset('assets/vendor') }}/libs/@popperjs/core/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/vendor/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Defaultmenu JS -->
@if(auth('driver')->check())
    <script src="{{ asset('assets/vendor') }}/js/defaultmenu.min.js"></script>
@endif

<!-- Node Waves JS-->
<script src="{{ asset('assets/vendor') }}/libs/node-waves/waves.min.js"></script>

<!-- Sticky JS -->
<script src="{{ asset('assets/vendor') }}/js/sticky.js"></script>

<!-- Simplebar JS -->
<script src="{{ asset('assets/vendor') }}/libs/simplebar/simplebar.min.js"></script>
<script src="{{ asset('assets/vendor') }}/js/simplebar.js"></script>

<!-- Color Picker JS -->
<script src="{{ asset('assets/vendor') }}/libs/@simonwep/pickr/pickr.es5.min.js"></script>

{{--<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>--}}
{{--<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>--}}
{{--<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>--}}
{{--<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>--}}
{{--<script src="{{ asset('assets/vendor') }}/js/datatables.js"></script>--}}


<!-- Custom-Switcher JS -->
{{--<script src="{{ asset('assets/vendor') }}/js/custom-switcher.min.js"></script>--}}

<!-- Custom JS -->
<script src="{{ asset('assets/vendor') }}/js/custom.js"></script>


<script src="{{ asset('assets/vendor/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<!-- Internal Select-2.js -->
<script src="{{ asset('assets/vendor/js/select2.js') }}"></script>

<!-- Main Theme Js -->
<script src="{{ asset('assets/vendor/js/main.js') }}"></script>

<!-- Crop Image Js -->
<script src="{{ asset('assets/vendor/libs/cropperjs/cropper.min.js') }}"></script>



@livewireScripts
<script src="{{ asset('assets/vendor/js/sweet-alerts2.js') }}"></script>
<x-livewire-alert::scripts/>

<script>

    Livewire.on('openModal', id => {
        const truck_modal = document.querySelector(id);
        let myModal = bootstrap.Modal.getOrCreateInstance(truck_modal);
        myModal.show();
    });

    Livewire.on('closeModal', id => {
        const truck_modal = document.querySelector(id);
        const modal = bootstrap.Modal.getOrCreateInstance(truck_modal);
        modal.hide();
    });

    Livewire.on('renderSelect', () => {
        var genericExamples = document.querySelectorAll('[data-trigger]');
        for (let i = 0; i < genericExamples.length; ++i) {
            var element = genericExamples[i];
            new Choices(element, {
                allowHTML: true,
                placeholderValue: 'This is a placeholder set in the config',
                searchPlaceholderValue: 'Search',
            });
        }
    });

    window.livewire.onError(statusCode => {
        if (statusCode === 500 || statusCode === 400) {
            return false
        }
    })

    document.addEventListener('click', function (event) {
        const btn = event.target.closest('.password-toggle');
        if (!btn) return;
        const input = document.getElementById(btn.getAttribute('data-target'));
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.className = 'ri-eye-off-line';
        } else {
            input.type = 'password';
            if (icon) icon.className = 'ri-eye-line';
        }
    });


</script>
<script>
    (function () {
        var resizeObserver = window.ResizeObserver ? new ResizeObserver(scheduleUpdate) : null;
        var updateScheduled = false;
        var overflowTolerance = 2;
        var wrapperSelector = '.table-responsive, .merchant-table-scroll, .overflow-x-auto';

        function getTablesForWrapper(wrapper) {
            return Array.prototype.filter.call(wrapper.querySelectorAll('table'), function (table) {
                return table.closest(wrapperSelector) === wrapper;
            });
        }

        function hasDataRows(table) {
            return Array.prototype.some.call(table.querySelectorAll('tbody tr'), function (row) {
                return !row.querySelector('td[colspan]');
            });
        }

        function updateTableScroll() {
            document.querySelectorAll(wrapperSelector).forEach(function (wrapper) {
                var tables = getTablesForWrapper(wrapper);
                if (!tables.length) return;

                if (resizeObserver && !wrapper.__tableOverflowResizeObserved) {
                    resizeObserver.observe(wrapper);
                    wrapper.__tableOverflowResizeObserved = true;
                }

                var hasData = tables.some(hasDataRows);
                var hasOverflow = wrapper.scrollWidth - wrapper.clientWidth > overflowTolerance;
                wrapper.style.overflowX = hasData && hasOverflow ? 'auto' : 'hidden';
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

        if (window.MutationObserver && document.body) {
            new MutationObserver(scheduleUpdate).observe(document.body, {
                childList: true,
                characterData: true,
                subtree: true
            });
        }
    })();
</script>
<style>
    .driver-row-details-clickable {
        cursor: pointer;
    }
</style>
<script>
    (function () {
        var viewActionPattern = /\b(?:show|view|details?)\b|عرض|تفاصيل|التفاصيل|مشاهدة/i;
        var interactiveSelector = 'a, button, input, select, textarea, label, [role="button"], [contenteditable="true"], [wire\\:click]';

        function getDetailAction(row) {
            var actions = row.querySelectorAll('a, button');

            for (var i = 0; i < actions.length; i++) {
                var action = actions[i];
                var label = (action.getAttribute('title') || '') + ' ' +
                    (action.getAttribute('aria-label') || '') + ' ' +
                    (action.textContent || '');
                var hasEyeIcon = Array.prototype.some.call(action.querySelectorAll('i, span'), function (icon) {
                    var classes = typeof icon.className === 'string' ? icon.className : '';
                    return classes.indexOf('eye') !== -1 && classes.indexOf('eye-off') === -1;
                });
                var href = action.getAttribute('href') || '';
                var hasAction = (href && href !== '#' && !/^javascript:/i.test(href)) ||
                    action.hasAttribute('wire:click') ||
                    action.hasAttribute('onclick');

                if (hasAction && (hasEyeIcon || viewActionPattern.test(label))) {
                    return action;
                }
            }

            return null;
        }

        function updateRow(row) {
            if (!row || row.tagName !== 'TR') return;
            row.classList.toggle('driver-row-details-clickable', !!getDetailAction(row));
        }

        function scanRows(node) {
            if (!node || node.nodeType !== 1) return;

            if (node.tagName === 'TR') updateRow(node);

            var parentRow = node.closest ? node.closest('tbody tr') : null;
            if (parentRow) updateRow(parentRow);

            if (node.querySelectorAll) {
                Array.prototype.forEach.call(node.querySelectorAll('tbody tr'), updateRow);
            }
        }

        document.querySelectorAll('tbody tr').forEach(updateRow);

        document.addEventListener('click', function (event) {
            if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;

            var target = event.target;
            if (!target || !target.closest || target.closest(interactiveSelector)) return;

            var row = target.closest('tbody tr');
            if (!row || row.hasAttribute('wire:click')) return;

            var action = getDetailAction(row);
            if (!action) return;

            var href = action.getAttribute('href') || '';
            if (href && href !== '#' && !/^javascript:/i.test(href)) {
                window.location.assign(action.href);
                return;
            }

            action.click();
        });

        if (window.MutationObserver && document.body) {
            new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    Array.prototype.forEach.call(mutation.addedNodes, scanRows);
                    if (mutation.target && mutation.target.closest) {
                        updateRow(mutation.target.closest('tbody tr'));
                    }
                });
            }).observe(document.body, { childList: true, subtree: true });
        }
    })();
</script>
@stack('js')
