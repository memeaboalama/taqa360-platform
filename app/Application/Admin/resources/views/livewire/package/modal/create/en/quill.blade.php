<div wire:ignore>
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <label class="form-label text-black">
        @lang('admin::home.Terms & Conditions in English')
        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-{{ $create_quillId_en }}"
            onclick="translateLocalizedField('{{ $create_quillId_en }}','package-terms-create-ar','en','ar',true)"
            title="تحديث الترجمة"
            style="cursor: pointer;">
            <i class="ri-refresh-line"></i>
        </button>
    </label>
    <div id="{{ $create_quillId_en }}" class="bg-gray-500" wire:ignore>
        {!! $create_value_en !!}
    </div>
</div>
<script>
    ClassicEditor
        .create(document.querySelector('#{{ $create_quillId_en }}'), {
            language: 'en',
            placeholder: 'Enter your text here...',
            autoGrow: true,
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                ]
            }
        })
        .then(editor => {
            window.ckEditors = window.ckEditors || {};
            window.ckEditors['{{ $create_quillId_en }}'] = editor;
            editor.model.document.on('change:data', () => {
                @this.set('create_value_en', editor.getData());
            });
            editor.ui.focusTracker.on('change:isFocused', (evt, name, isFocused) => {
                if (!isFocused) {
                    translateLocalizedField('{{ $create_quillId_en }}','package-terms-create-ar','en','ar',false);
                }
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>
