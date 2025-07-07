@foreach ($menu_nodes->loadMissing('metadata') as $key => $row)
    <a @class(['font-xs color-text-paragraph', 'xmr-30 ml-30' => (! $loop->first || ! $loop->last)]) href="{{ $row->url }}">{{ $row->title }}</a>
@endforeach
