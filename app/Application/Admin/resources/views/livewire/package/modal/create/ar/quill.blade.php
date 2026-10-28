

<div wire:ignore>
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <label class="form-label text-black">
        @lang('admin::home.Terms & Conditions in Arabic')
        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-{{ $create_quillId_ar }}"
            onclick="translateLocalizedField('{{ $create_quillId_ar }}','package-terms-create-en','ar','en',true)"
            title="تحديث الترجمة"
            style="cursor: pointer;">
            <i class="ri-refresh-line"></i>
        </button>
    </label>
    <div id="{{ $create_quillId_ar }}" class="bg-gray-500"  wire:ignore>
        {!! $create_value_ar !!}
    </div>
</div>
<script>
    ClassicEditor
        .create(document.querySelector('#{{ $create_quillId_ar }}'), {
            language: 'ar',
            placeholder: 'اكتب النص هنا ...',
            autoGrow: true,
            heading: {
                options: [
                    { model: 'paragraph', title: 'فقرة', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'عنوان 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'عنوان 2', class: 'ck-heading_heading2' }
                ]
            }
        })
        .then(editor => {
            window.ckEditors = window.ckEditors || {};
            window.ckEditors['{{ $create_quillId_ar }}'] = editor;
            editor.model.document.on('change:data', () => {
                @this.set('create_value_ar', editor.getData());
            });
            editor.ui.focusTracker.on('change:isFocused', (evt, name, isFocused) => {
                if (!isFocused) {
                    translateLocalizedField('{{ $create_quillId_ar }}','package-terms-create-en','ar','en',false);
                }
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>
