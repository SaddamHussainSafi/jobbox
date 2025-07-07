<span class="text-sort_by">{{ __('Sort by') }}:</span>
<div class="dropdown dropdown-sort">
    @php($orderByParams = JobBoardHelper::getSortByParams())
    <button class="btn dropdown-toggle" id="dropdownSort2" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
        <span>{{ Arr::get($orderByParams, BaseHelper::stringify(request()->query('sort_by', Arr::first(array_keys($orderByParams))))) }}</span>
        <i class="fi-rr-angle-small-down"></i>
    </button>
    <ul class="dropdown-menu js-dropdown-clickable dropdown-menu-light" aria-labelledby="dropdownSort2">
        @foreach($orderByParams as $key => $orderByParam)
            <li>
                <a
                    @class(['dropdown-item dropdown-sort-by', 'active' => BaseHelper::stringify(request()->query('sort_by', Arr::first(array_keys($orderByParams)))) === $key])
                    data-sort-by="{{ $key }}"
                    href="#"
                >
                    {{ $orderByParam }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
