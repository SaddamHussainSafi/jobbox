<div class="footer-col-2 col-md-2 col-xs-6">
    <div class="h6 mb-20">{!! BaseHelper::clean($config['name']) !!}</div>
    {!!
        Menu::generateMenu([
            'slug'    => $config['menu_id'],
            'view'    => 'footer-menu',
            'options' => ['class' => 'menu-footer']
        ])
    !!}
</div>
