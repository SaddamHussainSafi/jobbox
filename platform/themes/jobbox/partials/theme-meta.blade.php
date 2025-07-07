{!! BaseHelper::googleFonts('https://fonts.googleapis.com/css2?family=' . urlencode(theme_option('primary_font') ?: 'Plus Jakarta Sans') . ':wght@400;500;600;700;800&display=swap') !!}

<style>
    :root {
        --primary-color: {{ theme_option('primary_color', '#3C65F5') }};
        --primary-color-hover: {{ theme_option('primary_color_hover', '#b4c0e0') }};
        --secondary-color: {{ theme_option('secondary_color', '#05264E') }};
        --border-color-2: {{ theme_option('border_color_2', '#E0E6F7') }};
        --primary-font: '{{ theme_option('primary_font') ?: 'Plus Jakarta Sans' }}', sans-serif;
        --primary-color-rgb: {{ implode(', ', BaseHelper::hexToRgb(theme_option('primary_color', '#3C65F5'))) }};
    }
</style>
