const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/plugins/${directory}`
const dist = `public/vendor/core/plugins/${directory}`

mix
    .sass(`${source}/resources/sass/newsletter.scss`, `${dist}/css`)
    .js(`${source}/resources/js/newsletter.js`, `${dist}/js`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/css/newsletter.css`, `${source}/public/css`)
        .copy(`${dist}/js/newsletter.js`, `${source}/public/js`)
}
