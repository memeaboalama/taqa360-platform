<script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"
        integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>
<!-- Popper JS -->
<script src="{{ asset('assets/vendor') }}/libs/@popperjs/core/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/vendor/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Defaultmenu JS -->
<script src="{{ asset('assets/vendor') }}/js/defaultmenu.min.js"></script>

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

@livewireScripts
<script src="{{ asset('assets/vendor/js/sweet-alerts2.js') }}"></script>

<script>
    if (!window.Swal && window.Sweetalert2) {
        window.Swal = window.Sweetalert2;
    }
</script>


<!-- Crop Image Js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
{{--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<x-livewire-alert::scripts/>

<script>
    window.addEventListener('redirect-after-alert', event => {
        const url = event.detail.url;
        if (!url) return;
        setTimeout(() => {
            window.location.href = url;
        }, 3500);
    });
</script>

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

    function translateLocalizedField(sourceId, targetId, from, to, force) {
        if (typeof window.__translationInProgress === 'undefined') {
            window.__translationInProgress = {};
        }
        var sourceEl = document.getElementById(sourceId);
        var targetEl = document.getElementById(targetId);

        var getVal = function(id, el) {
            if (window.ckEditors && window.ckEditors[id]) {
                var data = window.ckEditors[id].getData();
                var tmp = document.createElement('div');
                tmp.innerHTML = data;
                return (tmp.textContent || tmp.innerText || '').trim();
            }
            if (window.quillEditors && window.quillEditors[id]) {
                var quill = window.quillEditors[id];
                var tmp = document.createElement('div');
                tmp.innerHTML = quill.root.innerHTML;
                return (tmp.textContent || tmp.innerText || '').trim();
            }
            return el ? (el.value || '').trim() : '';
        };

        var setVal = function(id, el, text) {
            if (window.ckEditors && window.ckEditors[id]) {
                var html = text.includes('<p>') ? text : '<p>' + text + '</p>';
                window.ckEditors[id].setData(html);
            } else if (window.quillEditors && window.quillEditors[id]) {
                var quill = window.quillEditors[id];
                var html = text.includes('<p>') ? text : '<p>' + text + '</p>';
                quill.clipboard.dangerouslyPasteHTML(html);
                quill.update();
            } else if (el) {
                el.value = text;
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        };

        if (!sourceEl && !(window.ckEditors && window.ckEditors[sourceId]) && !(window.quillEditors && window.quillEditors[sourceId])) return;
        if (!targetEl && !(window.ckEditors && window.ckEditors[targetId]) && !(window.quillEditors && window.quillEditors[targetId])) return;

        var text = getVal(sourceId, sourceEl);
        if (!text) return;

        var currentTargetVal = getVal(targetId, targetEl);
        if (!force && currentTargetVal !== '') return;

        var key = sourceId;
        if (window.__translationInProgress[key]) return;
        window.__translationInProgress[key] = true;

        var btn = document.getElementById('translate-' + sourceId);
        var originalHtml = btn ? btn.innerHTML : '';

        if (btn) {
            btn.disabled = true;
            btn.classList.add('disabled');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        }

        fetch('https://api.mymemory.translated.net/get?q=' + encodeURIComponent(text) + '&langpair=' + from + '|' + to)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                var translated = data && data.responseData && data.responseData.translatedText;
                if (translated) {
                    setVal(targetId, targetEl, translated);
                }
            })
            .catch(function(error) {
                console.error('Translation failed:', error);
            })
            .finally(function() {
                window.__translationInProgress[key] = false;
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('disabled');
                    btn.innerHTML = originalHtml;
                }
            });
    }
</script>

<script>
    function adminSetActiveMenuItem() {
        var currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
        var items = document.querySelectorAll('.main-menu .side-menu__item[href]');
        var best = null;
        var bestLen = -1;

        items.forEach(function (el) {
            var href = el.getAttribute('href');
            if (!href || href === '#' || href === 'javascript:void(0);') return;

            var linkPath;
            try {
                linkPath = new URL(href, window.location.origin).pathname.replace(/\/+$/, '') || '/';
            } catch (e) {
                return;
            }

            var matched = (currentPath === linkPath) || (currentPath.indexOf(linkPath + '/') === 0);
            if (matched && linkPath.length > bestLen) {
                bestLen = linkPath.length;
                best = el;
            }
        });

        if (!best) return;

        best.classList.add('active');
        best.parentElement.classList.add('active');

        var holder = best.closest('.has-sub');
        while (holder) {
            holder.classList.add('open', 'active');
            var parentItem = holder.querySelector(':scope > .side-menu__item');
            if (parentItem) parentItem.classList.add('active');

            var subMenu = holder.querySelector(':scope > .slide-menu');
            if (subMenu && window.getComputedStyle(subMenu).display === 'none') {
                subMenu.style.display = 'block';
            }

            holder = holder.parentElement.closest('.has-sub');
        }
    }

    document.addEventListener('DOMContentLoaded', adminSetActiveMenuItem);
    document.addEventListener('livewire:load', adminSetActiveMenuItem);
    window.addEventListener('pageLoaded', adminSetActiveMenuItem);
</script>

<style>
    [data-edit-loading][disabled],
    [data-edit-loading].disabled {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    [data-edit-loading][disabled] .spinner-border,
    [data-edit-loading].disabled .spinner-border {
        margin: 0;
        vertical-align: middle;
    }

    .btn-spin .spinner-border,
    [data-edit-loading] .spinner-border {
        display: inline-block;
        border: 2px solid currentColor;
        border-left-color: transparent;
        border-radius: 50%;
    }

    .btn-icon.btn-info-transparent:has(.btn-spin:not(.d-none)),
    .btn-icon.btn-info-transparent:has(.btn-spin:not(.d-none)):hover,
    .btn-icon.btn-info-transparent:has(.btn-spin:not(.d-none)):focus,
    .btn-icon.btn-info-transparent:has(.btn-spin:not(.d-none)):active {
        background-color: rgba(var(--info-rgb), .1);
        border-color: rgba(var(--info-rgb), .1);
        color: rgb(var(--info-rgb));
    }
</style>

<script>
    function initEditLoading() {
        if (window.__editLoadingBound) return;
        window.__editLoadingBound = true;

        document.addEventListener('click', function (e) {
            var btn = e.target && e.target.closest ? e.target.closest('[data-edit-loading]') : null;
            if (!btn || btn.disabled) return;

            btn.dataset.originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';

            var label = btn.getAttribute('data-edit-loading-label') || '';
            btn.innerHTML = '<span class="spinner-border spinner-border-sm' + (label ? ' me-1' : '') + '" role="status" aria-hidden="true"></span>' + label;
        });

        function resetEditLoadingButtons() {
            document.querySelectorAll('[data-edit-loading]').forEach(function (btn) {
                if (btn.dataset.originalHtml) {
                    btn.innerHTML = btn.dataset.originalHtml;
                    delete btn.dataset.originalHtml;
                }
                btn.disabled = false;
                btn.classList.remove('disabled');
                btn.style.pointerEvents = '';
            });
        }

        document.addEventListener('shown.bs.modal', resetEditLoadingButtons);
        document.addEventListener('hidden.bs.modal', resetEditLoadingButtons);

        if (window.Livewire && Livewire.hook) {
            Livewire.hook('message.processed', function () {
                setTimeout(resetEditLoadingButtons, 50);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initEditLoading);
    if (window.Livewire) {
        document.addEventListener('livewire:load', initEditLoading);
    }

    function initBtnSpinMouseDown() {
        if (window.__btnSpinBound) return;
        window.__btnSpinBound = true;

        document.addEventListener('mousedown', function (e) {
            var host = e.target && e.target.closest ? e.target.closest('.btn') : null;
            if (!host) return;
            var spin = host.querySelector('.btn-spin');
            if (!spin || !spin.classList.contains('d-none')) return;
            var lbl = host.querySelector('.btn-lbl');
            if (lbl) lbl.classList.add('invisible');
            spin.classList.remove('d-none');
            setTimeout(function () {
                if (lbl) lbl.classList.remove('invisible');
                spin.classList.add('d-none');
            }, 2000);
        });
    }

    document.addEventListener('DOMContentLoaded', initBtnSpinMouseDown);
    if (window.Livewire) {
        document.addEventListener('livewire:load', initBtnSpinMouseDown);
    }
</script>
@stack('js')
