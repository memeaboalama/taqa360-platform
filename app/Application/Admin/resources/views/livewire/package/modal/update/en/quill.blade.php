<div wire:ignore>
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <label class="form-label text-black">
        @lang('admin::home.Terms & Conditions in English')
        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-{{ $update_quillId_en }}"
            onclick="translateLocalizedField('{{ $update_quillId_en }}','package-terms-update-ar','en','ar',true)"
            title="تحديث الترجمة"
            style="cursor: pointer;">
            <i class="ri-refresh-line"></i>
        </button>
    </label>
    <div id="{{ $update_quillId_en }}" class="bg-gray-500" wire:ignore>
        {!! $update_value_en !!}
    </div>
</div>
<script>
    ClassicEditor
        .create(document.querySelector('#{{ $update_quillId_en }}'))
        .then(editor => {
            window.ckEditors = window.ckEditors || {};
            window.ckEditors['{{ $update_quillId_en }}'] = editor;
            editor.model.document.on('change:data', () => {
                @this.set('update_value_en', editor.getData());
            });
            editor.ui.focusTracker.on('change:isFocused', (evt, name, isFocused) => {
                if (!isFocused) {
                    translateLocalizedField('{{ $update_quillId_en }}','package-terms-update-ar','en','ar',false);
                }
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>