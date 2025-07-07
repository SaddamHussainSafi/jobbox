@if (($clientId = setting('social_login_google_app_id')) && setting('social_login_google_use_google_button', false))
    <script src="https://accounts.google.com/gsi/client" async></script>
    <div id="g_id_onload"
         data-client_id="{{ str_replace('.apps.googleusercontent.com', '', $clientId) }}"
         data-login_uri="{{ route('auth.social.callback', ['provider' => 'google']) }}"
         data-auto_prompt="false">
    </div>
    <div class="g_id_signin mb-3"
         data-type="standard"
         data-size="large"
         data-theme="outline"
         data-text="sign_in_with"
         data-shape="rectangular"
         data-logo_alignment="left"
    >
    </div>
@endif
