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

<!-- Crop Image Js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
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
@stack('js')
