<script src="{{ asset('assets/vendor/libs/jquery/jquery-3.7.0.min.js') }}"></script>
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

<!-- Crop Image Js -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery-3.7.0.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> --}}
<script src="{{ asset('assets/vendor/libs/cropperjs/cropper.min.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script> --}}



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

    function btnSpinnerColor(btn) {
        const rootStyle = getComputedStyle(document.documentElement);
        const cls = btn.className || '';
        const rgb = (name) => 'rgb(' + (rootStyle.getPropertyValue(name).trim() || '73,182,245') + ')';
        const rgba = (name) => 'rgba(' + (rootStyle.getPropertyValue(name).trim() || '73,182,245') + ',0.1)';
        if (cls.includes('btn-info')) return { color: rgb('--info-rgb'), bg: rgba('--info-rgb') };
        if (cls.includes('btn-primary')) return { color: rgb('--primary-rgb'), bg: rgba('--primary-rgb') };
        if (cls.includes('btn-warning')) return { color: rgb('--warning-rgb'), bg: rgba('--warning-rgb') };
        if (cls.includes('btn-danger')) return { color: rgb('--danger-rgb'), bg: rgba('--danger-rgb') };
        if (cls.includes('btn-success')) return { color: rgb('--success-rgb'), bg: rgba('--success-rgb') };
        const icon = btn.querySelector('i');
        return { color: getComputedStyle(icon || btn).color, bg: '' };
    }

    function btnSpinnerHtml(color) {
        return '<span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);line-height:0;"><span class="spinner-border" role="status" aria-hidden="true" style="width:0.8rem;height:0.8rem;border-width:0.15rem;color:' + color + ';"></span></span>';
    }

    document.addEventListener('click', function (event) {
        const btn = event.target.closest('.btn-data-loading');
        if (!btn) return;

        const target = btn.getAttribute('data-loading-target');
        const icon = btn.querySelector('i');
        const originalHtml = icon ? icon.outerHTML : btn.innerHTML;
        const originalPosition = btn.style.position;
        const originalBg = btn.style.backgroundColor;
        const originalBorder = btn.style.borderColor;
        const c = btnSpinnerColor(btn);

        const spinnerHtml = btnSpinnerHtml(c.color);

        btn.style.position = 'relative';
        if (c.bg) {
            btn.style.backgroundColor = c.bg;
            btn.style.borderColor = c.bg;
        }
        btn.style.outline = 'none';
        btn.style.boxShadow = 'none';
        if (icon) {
            icon.outerHTML = spinnerHtml;
        } else {
            btn.innerHTML = spinnerHtml;
        }
        btn.classList.add('pointer-events-none');
        btn.style.pointerEvents = 'none';

        const restore = () => {
            const wrapper = btn.querySelector('span[style*="position:absolute"]');
            if (wrapper) {
                wrapper.outerHTML = originalHtml;
            } else if (icon) {
                btn.innerHTML = originalHtml;
            } else {
                btn.innerHTML = originalHtml;
            }
            btn.style.position = originalPosition;
            btn.style.backgroundColor = originalBg;
            btn.style.borderColor = originalBorder;
            btn.style.outline = '';
            btn.style.boxShadow = '';
            btn.style.pointerEvents = '';
            btn.classList.remove('pointer-events-none');
        };

        const modalEl = document.querySelector(target);
        if (modalEl) {
            modalEl.addEventListener('shown.bs.modal', function handler() {
                restore();
                modalEl.removeEventListener('shown.bs.modal', handler);
            });
        }

        setTimeout(restore, 30000);
    });

    document.addEventListener('click', function (event) {
        const link = event.target.closest('.btn-data-loading-nav');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href) return;

        event.preventDefault();

        const originalPosition = link.style.position;
        const originalBg = link.style.backgroundColor;
        const originalBorder = link.style.borderColor;
        const c = btnSpinnerColor(link);

        const spinnerHtml = btnSpinnerHtml(c.color);
        const icon = link.querySelector('i');
        const originalHtml = icon ? icon.outerHTML : link.innerHTML;

        link.style.position = 'relative';
        if (c.bg) {
            link.style.backgroundColor = c.bg;
            link.style.borderColor = c.bg;
        }
        link.style.outline = 'none';
        link.style.boxShadow = 'none';
        if (icon) {
            icon.outerHTML = spinnerHtml;
        } else {
            link.innerHTML = spinnerHtml;
        }
        link.classList.add('pointer-events-none');
        link.style.pointerEvents = 'none';

        setTimeout(function () {
            window.location.href = href;
        }, 200);
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

    function swalToast(type, message) {
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'
            || document.documentElement.lang === 'ar';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: isRtl ? 'top-start' : 'top-end',
                showConfirmButton: false,
                timer: 3000,
                icon: type === 'error' ? 'error' : 'success',
                title: message,
            });
        }
    }

    Livewire.on('toastr', data => {
        swalToast(data.type, data.message);
    });

    Livewire.hook('message.received', (message, component) => {
        const errors = message.response
            && message.response.serverMemo
            && message.response.serverMemo.errors;
        if (errors && Object.keys(errors).length) {
            swalToast('error', '{{ __('marketplace::home.please_fix_the_errors') }}');
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
@stack('js')
