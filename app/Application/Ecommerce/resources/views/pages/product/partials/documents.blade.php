@forelse($presentation['documents'] as $document)
    <article class="pp-document-card">
        <i class="fa fa-file-pdf-o pp-document-icon" aria-hidden="true"></i>
        <h3>{{ $document['title'] }}</h3>
        <p>{{ $document['name'] }}</p>
        <a href="{{ $document['url'] }}" target="_blank" rel="noopener noreferrer"><i class="fa fa-download" aria-hidden="true"></i> @lang('ecommerce::product_page.view_document')</a>
    </article>
@empty
    <p class="pp-empty">@lang('ecommerce::product_page.no_documents')</p>
@endforelse
