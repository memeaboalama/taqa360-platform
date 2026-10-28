@extends('educator::layout.layout')

@section('title', __('educator::home.page_title'))
@push('css')
<style>
    .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; }
    .form-input, .form-input-sm { width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; transition: all 0.2s; }
    .form-input:focus, .form-input-sm:focus { border-color: hsl(var(--primary-hsl)); box-shadow: 0 0 0 2px hsla(var(--primary-hsl), 0.5); }
    .form-input { padding: 0.65rem 1rem; font-size: 0.9rem; }
    .form-input-sm { padding: 0.5rem 0.75rem; font-size: 0.875rem; }
    .form-error { font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem; display: block; }

    .radio-input { width: 1.1rem; height: 1.1rem; color: hsl(var(--primary-hsl)); border-color: #d1d5db; focus:ring-primary; }
    .radio-label { font-size: 0.875rem; color: #374151; {{ app()->getLocale() === 'ar' ? 'margin-right: 0.5rem;' : 'margin-left: 0.5rem;' }} }

    .dropdown-item { width: 100%; padding: 0.6rem 0.8rem; {{ app()->getLocale() === 'ar' ? 'text-align: right;' : 'text-align: left;' }} cursor: pointer; }
    .dropdown-item:hover { background-color: #f9fafb; }

    .upload-box { border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 1rem; text-align: center; cursor: pointer; position: relative; transition: all 0.2s; min-height: 120px; display:flex; align-items:center; justify-content:center; }
    .upload-box:hover { border-color: hsl(var(--primary-hsl)); }
    .upload-input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    .upload-preview { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; }
    .upload-img { width: 4rem; height: auto; border-radius: 0.375rem; object-fit: contain; }
    .upload-prompt { display: flex; flex-direction: column; align-items: center; justify-content: center; color: #6b7280; }
    .upload-icon { width: 2rem; height: 2rem; margin-bottom: 0.5rem; }
    .upload-text { font-size: 0.875rem; }
    .upload-progress-wrapper { position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; }
    .upload-progress-bar { background-color: hsl(var(--primary-hsl)); height: 4px; border-radius: 9999px; }

    .btn-primary { padding: 0.6rem 2rem; background-color: hsl(var(--primary-hsl)); color: hsl(var(--primary-foreground-hsl)); border-radius: 0.5rem; font-weight: 600; border: 1px solid transparent; transition: background-color 0.2s, border-color 0.2s; }
    .btn-primary:hover { background-color: #ffffff; border-color: hsl(var(--primary-hsl)); }
    .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-secondary { padding: 0.6rem 2rem; border: 1px solid #d1d5db; border-radius: 0.5rem; color: #374151; font-weight: 600; transition: background-color 0.2s; }
    .btn-secondary:hover { background-color: #f3f4f6; }
    .btn-secondary:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
@endpush
@section('content')
    <livewire:educator::profile.builder.builder/>
@endsection

@push('js')
<script>
    if (typeof getLevelLabel !== 'function') {
        function getLevelLabel(level) {
            const labels = {
                1: "@lang('educator::builder.skills.levels.beginner')",
                2: "@lang('educator::builder.skills.levels.basic')",
                3: "@lang('educator::builder.skills.levels.intermediate')",
                4: "@lang('educator::builder.skills.levels.advanced')",
                5: "@lang('educator::builder.skills.levels.expert')",
            };
            return labels[level] || '';
        }
    }
</script>
@endpush
