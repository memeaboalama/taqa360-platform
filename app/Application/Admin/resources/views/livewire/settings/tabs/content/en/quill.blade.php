<div wire:ignore>
    <!-- Include stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/quill.snow.css')}}">

    <!-- Quill Editor JS -->
    <script src="{{ asset('assets/vendor/libs/quill/quill.min.js')}}"></script>

    <!-- Create the editor container -->
    <label class="form-label text-black">
        @lang('admin::home.Login/Register Content en')
        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-{{ $quillId_en }}"
            onclick="translateLocalizedField('{{ $quillId_en }}','settings-auth-content-ar','en','ar',true)"
            title="تحديث الترجمة"
            style="cursor: pointer;">
            <i class="ri-refresh-line"></i>
        </button>
    </label>
    <div id="{{ $quillId_en }}" style="background-color:#fff; border:1px solid #adb5bd; border-radius:6px;" wire:ignore>
        {!! $value_en !!}
    </div>

    <!-- Initialize Quill editor -->
    <script>
        var toolbarOptionsEn = [
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
                toolbar: toolbarOptionsEn
            },
            placeholder: '@lang('admin::home.Login/Register Content en')',
            theme: 'snow'
        });
        window.quillEditors = window.quillEditors || {};
        window.quillEditors['{{ $quillId_en }}'] = quill_en;
        quill_en.on('text-change', function () {
            let value_en = quill_en.root.innerHTML;
        @this.set('value_en', value_en)
        })
        quill_en.on('selection-change', function (range) {
            if (!range) {
                translateLocalizedField('{{ $quillId_en }}','settings-auth-content-ar','en','ar',false);
            }
        });
    </script>
</div>
