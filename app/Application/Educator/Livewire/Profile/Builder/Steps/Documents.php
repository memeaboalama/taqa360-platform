<?php
namespace Application\Educator\Livewire\Profile\Builder\Steps;

use Domain\Educator\Models\Educator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Documents extends Component
{
    use WithFileUploads, LivewireAlert;

    public Educator $educator;
    public array $documents = [];
    public $uploads = [];

    public array $documentTypes = [
        'passport' => 'جواز سفر',
        'professional_license' => 'رخصة مهنية',
        'other' => 'وثيقة أخرى',
    ];

    protected function rules()
    {
        return [
            'documents.*.type' => 'required|string',
            'documents.*.file_name' => 'required|string|max:255',
            'uploads.*' => 'required|file|max:5120',
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->documents = $this->educator->files()
            ->where('type', 'general_document')
            ->get()
            ->map(function ($file) {
                return [
                    'id' => $file->id,
                    'type' => $file->collection_name,
                    'file_name' => $file->file_name,
                    'original_name' => $file->original_name,
                    'mime_type' => $file->mime_type,
                    'url' => $file->url,
                ];
            })
            ->toArray();
    }

    public function addDocument()
    {
        $this->documents[] = [
            'id' => null,
            'type' => '',
            'file_name' => '',
        ];
    }

    public function removeDocument($index)
    {
        $documentData = $this->documents[$index] ?? null;
        if ($documentData && !empty($documentData['id'])) {
            $this->educator->files()->find($documentData['id'])?->delete();
        }
        unset($this->documents[$index]);
        $this->documents = array_values($this->documents);
        if (isset($this->uploads[$index])) {
            unset($this->uploads[$index]);
        }
    }

    public function save()
    {
        $this->validate();

        foreach ($this->uploads as $index => $file) {
            $documentData = $this->documents[$index];

            if (!empty($documentData['id'])) {
                $this->educator->files()->find($documentData['id'])?->delete();
            }

            $path = $file->store("educators/{$this->educator->id}/documents", 'public');

            $this->educator->files()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'type' => 'general_document',
                'collection_name' => $documentData['type'],
                'file_name' => $documentData['file_name'],
                'storage' => 'public',
            ]);
        }

        $this->emitUp('stepCompleted', 8);
        $this->alert('success', __('educator::builder.messages.document_saved'));
    }

    public function render()
    {
        return view('educator::livewire.profile.builder.steps.documents');
    }
}
