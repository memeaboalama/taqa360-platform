<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="text-center mb-6">
        <img src="{{ $educator->profileImage?->url ?? 'https://ui-avatars.com/api/?name=' . urlencode($educator->name) . '&background=F3F4F6&color=1F2937&bold=true' }}" alt="{{ $educator->name }}" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover ring-4 ring-white shadow-md">
        <h2 class="text-xl font-bold text-gray-800">{{ $educator->name }}</h2>
        <p class="text-gray-500">{{ $educator->profile->specialization ?? __('educator::home.sidebar.university_professor') }}</p>
        <div class="flex items-center justify-center mt-2">
            <div class="flex text-[#EA993E]">
                @foreach(range(1,5) as $i) <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg> @endforeach
            </div>
            <span class="text-sm text-gray-500 ltr:ml-2 rtl:mr-2">({{ number_format((float) $educator->rating, 1) }}) · {{ $reviewCount }} @lang('educator::home.sidebar.reviews')</span>
        </div>
    </div>

    <div class="space-y-4 mb-6 text-sm border-t pt-6">
        <div class="flex items-center gap-3 text-gray-700">
            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
            <span>{{ $educator->profile->city_id ?? 'Riyadh, Saudi Arabia' }}</span>
        </div>
        <div class="flex items-center gap-3 text-gray-700">
            <span class="flex h-3 w-3 relative flex-shrink-0"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span></span>
            <span>@lang('educator::home.sidebar.status_available')</span>
        </div>
        <div class="flex items-center gap-3 text-gray-700">
            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>@lang('educator::home.sidebar.experience_years', ['years' => $educator->profile->experience_years ?? 15])</span>
        </div>
    </div>

    <div class="border-t pt-6 space-y-2">
        <a href="{{ route('educator:profile.builder') }}" class="flex items-center gap-3 p-3 rounded-lg font-semibold text-gray-700 hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773 1.339a1.125 1.125 0 01-.217 1.45l-.53.462a1.125 1.125 0 000 1.948l.53.462a1.125 1.125 0 01.217 1.45l-.773-1.339a1.125 1.125 0 01-1.45-.12l-.737.527c-.35.25-.807.272-1.205.108-.396.165-.71.506-.78.93l-.149.894c-.09.542-.56.94-1.11.94h-1.093c-.55 0-1.02-.398-1.11-.94l-.149-.894a1.125 1.125 0 00-.78-.93c-.398-.164-.855-.142-1.205.108l-.737.527a1.125 1.125 0 01-1.45-.12l-.773-1.339a1.125 1.125 0 01.217-1.45l.53-.462a1.125 1.125 0 000-1.948l-.53-.462a1.125 1.125 0 01-.217-1.45l.773-1.339a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.205.108.396-.165.71-.506.78-.93l.149.894zM12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
            <span>@lang('educator::home.sidebar.update_profile')</span>
        </a>
        <a href="#" class="flex items-center gap-3 p-3 rounded-lg font-semibold text-gray-700 hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-4.5 0V6m0 0l3 3m-3-3l-3 3" /></svg>
            <span>@lang('educator::home.sidebar.view_public_profile')</span>
        </a>
    </div>
</div>
