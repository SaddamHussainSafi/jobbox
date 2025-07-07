<ul class="menu">
    @foreach (DashboardMenu::getAll('account') as $item)
        @continue(! $item['name'])
        <li>
            <a
                href="{{ $item['url']  }}"
                @class(['active' => $item['active']])
            >
                <x-core::icon :name="$item['icon']" />
                {{ __($item['name']) }}
            </a>
        </li>
    @endforeach
</ul>
