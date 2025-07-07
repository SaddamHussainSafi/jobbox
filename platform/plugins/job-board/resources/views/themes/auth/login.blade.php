<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card login-form">
                <div class="card-body">
                    <h4 class="text-center">{{ trans('plugins/job-board::dashboard.login-title') }}</h4>
                    <br>
                    {!! $form->renderForm() !!}
                </div>
            </div>
        </div>
    </div>
</div>
