<div class="mb-3">
    <label for="languages" class="form-label w-100">
        {{ __('Languages') }}
        <button type="button" class="btn btn-link btn-sm float-end" data-bs-target="#addLanguageModal" data-bs-toggle="modal">{{ __('Add New') }}</button>
    </label>

    @if($languages->isNotEmpty())
        <ul class="list-group ps-0">
            @foreach($languages as $language)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span><strong>{{ $language->language_name }}</strong> - {{ $language->languageLevel->name }}</span>
                        @if($language->is_native)
                            <span class="badge bg-primary rounded-pill">{{ __('Native') }}</span>
                        @endif
                    </div>
                    <div>
                        <button
                                type="button"
                                class="btn btn-remove"
                                data-bb-toggle="delete-language"
                                data-url="{{ route('public.account.languages.destroy', $language->getKey()) }}"
                        ></button>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-warning mb-0">
            <small>{{ __('You have not added any language yet!') }}</small>
        </div>
    @endif
</div>
