<ul {!! BaseHelper::clean($options) !!}>
    @foreach ($menu_nodes as $key => $row)
        <li><a href="{{ $row->url }}">{{ $row->title }}</a></li>
    @endforeach
</ul>
