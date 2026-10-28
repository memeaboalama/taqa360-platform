<div wire:ignore>
    <!-- Include stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/quill.snow.css')}}">

    <!-- Quill Editor JS -->
    <script src="{{ asset('assets/vendor/libs/quill/quill.min.js')}}"></script>

    <!-- Create the editor container -->
    <label class="form-label text-black">
        @lang('admin::home.User Content ar')
        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-{{ $quillUserId_ar }}"
            onclick="translateLocalizedField('{{ $quillUserId_ar }}','settings-user-content-en','ar','en',true)"
            title="تحديث الترجمة"
            style="cursor: pointer;">
            <i class="ri-refresh-line"></i>
        </button>
    </label>
    <div id="{{ $quillUserId_ar }}" style="background-color:#fff; border:1px solid #adb5bd; border-radius:6px;" wire:ignore>
        {!! $value_user_ar !!}
    </div>

    <!-- Initialize Quill editor -->
    <script>
        var toolbarOptionsUserAr = [
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
        const quill_user_ar = new Quill('#{{ $quillUserId_ar }}', {
            modules: {
                toolbar: toolbarOptionsUserAr
            },
            placeholder: '@lang('admin::home.User Content ar')',
            theme: 'snow'
        });
        window.quillEditors = window.quillEditors || {};
        window.quillEditors['{{ $quillUserId_ar }}'] = quill_user_ar;
        quill_user_ar.on('text-change', function () {
            let value_user_ar = quill_user_ar.root.innerHTML;
        @this.set('value_user_ar', value_user_ar)
        })
        quill_user_ar.on('selection-change', function (range) {
            if (!range) {
                translateLocalizedField('{{ $quillUserId_ar }}','settings-user-content-en','ar','en',false);
            }
        });
    </script>
</div>
