@for($i = 1 ; $i <= $star; $i++)
    <img alt="star" class="rating-star" src="{{ Theme::asset()->url('imgs/template/icons/star.svg') }}">
@endfor
@for($i = 1 ; $i <= (5 - $star); $i++)
    <img alt="star" class="rating-star" src="{{ Theme::asset()->url('imgs/template/icons/gray-star.svg') }}">
@endfor
