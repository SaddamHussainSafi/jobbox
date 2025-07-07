let mix = require('laravel-mix')

const path = require('path')
let directory = path.basename(path.resolve(__dirname))

const source = 'platform/themes/' + directory
const dist = 'public/themes/' + directory

mix
    .sass(source + '/assets/sass/style.scss', dist + '/css')
    .sass(source + '/assets/sass/style-rtl.scss', dist + '/css')
    .js(source + '/assets/js/script.js', dist + '/js')
    .js(source + '/assets/js/backend.js', dist + '/js')
    .js(source + '/assets/js/candidates-filter.js', dist + '/js')
    .js(source + '/assets/js/main.js', dist + '/js')
    .js(source + '/assets/js/company.js', dist + '/js')
    .js(source + '/assets/js/company-detail.js', dist + '/js')

if (mix.inProduction()) {
    mix
        .copy(dist + '/css/style.css', source + '/public/css')
        .copy(dist + '/css/style-rtl.css', source + '/public/css')
        .copy(dist + '/js/script.js', source + '/public/js')
        .copy(dist + '/js/backend.js', source + '/public/js')
        .copy(dist + '/js/candidates-filter.js', source + '/public/js')
        .copy(dist + '/js/main.js', source + '/public/js')
        .copy(dist + '/js/company.js', source + '/public/js')
        .copy(dist + '/js/company-detail.js', source + '/public/js')
}
