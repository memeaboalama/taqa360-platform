<div wire:ignore>
    <!-- Include stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/quill.snow.css')}}">

    <!-- Quill Editor JS -->
    <script src="{{ asset('assets/vendor/libs/quill/quill.min.js')}}"></script>

    <!-- Create the editor container -->
    <label class="form-label text-black">@lang('admin::home.Terms & Conditions in English') <span class="text-danger">*</span></label>
    <div id="{{ $quillId_en }}" wire:ignore>
        {!! $value_en !!}
    </div>
    <!-- Initialize Quill editor -->
    <script>
        var toolbarOptions_en = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'font': [] }],
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'],

            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction

            [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'align': [] }],

            ['image', 'video'],
            ['clean']                                         // remove formatting button
        ];
        const quill_en = new Quill('#{{ $quillId_en }}', {
            modules: {
                toolbar: toolbarOptions_en
            },
            placeholder : '@lang('admin::home.Terms & Conditions in English')',
            theme: 'snow'
        });
        quill_en.on('text-change', function () {
            let value_en = document.getElementById('{{ $quillId_en }}').querySelector('.ql-editor').innerHTML;
        @this.set('value_en', value_en)
        })
    </script>
</div>
