@if (is_plugin_active('newsletter'))
    <section class="section-box mt-50 mb-20">
        <div class="container">
            <div class="box-newsletter" style="background-image: url({{ RvMedia::getImageUrl($config['background_image']) }})">
                <div class="row">
                    <div class="col-xl-3 col-12 text-center d-none d-xl-block">
                        <img src="{{ RvMedia::getImageUrl($config['image_left']) }}" alt="{{ theme_option('site_title') }}">
                    </div>
                    <div class="col-lg-12 col-xl-6 col-12">
                        <h2 class="text-md-newsletter text-center">
                            {!! BaseHelper::clean($config['title']) !!}
                        </h2>

                            <div class="box-form-newsletter mt-40">
                                {!! $form->renderForm() !!}
                            </div>
                    </div>
                    <div class="col-xl-3 col-12 text-center d-none d-xl-block">
                        <img src="{{ RvMedia::getImageUrl($config['image_right']) }}" alt="{{ theme_option('site_title') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
