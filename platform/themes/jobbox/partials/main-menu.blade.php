<ul {!! BaseHelper::clean($options) !!}>
    @foreach ($menu_nodes as $key => $row)
        <li class="@if ($row->has_child) has-children @endif {{ $row->css_class }}">
            <a href="{{ $row->url }}" target="{{ $row->target }}">
                @if ($row->icon_font) <i class="{{ trim($row->icon_font) }}"></i> @endif {{ $row->title }}
                @if ($row->has_child) <div class="arrow-down"></div> @endif
            </a>
            @if ($row->has_child)
                {!!
                    Menu::generateMenu([
                        'menu'       => $menu,
                        'menu_nodes' => $row->child,
                        'view'       => 'main-menu',
                        'options'    => ['class' => 'sub-menu'],
                    ])
                !!}
            @endif
        </li>
    @endforeach
</ul>
