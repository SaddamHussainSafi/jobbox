const mix = require('laravel-mix');
const path = require('path');

const directory = path.basename(path.resolve(__dirname));
const source = `platform/plugins/${directory}`;
const dist = `public/vendor/core/plugins/${directory}`;

mix
    .sass(`${source}/resources/sass/avatar.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/currencies.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/invoice.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/review.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/dashboard/style.scss`, `${dist}/css/dashboard`)
    .sass(`${source}/resources/sass/dashboard/style-rtl.scss`, `${dist}/css/dashboard`)
    .sass(`${source}/resources/sass/front-auth.scss`, `${dist}/css`)
    .js(`${source}/resources/js/job.js`, `${dist}/js`)
    .js(`${source}/resources/js/avatar.js`, `${dist}/js`)
    .js(`${source}/resources/js/currencies.js`, `${dist}/js`)
    .js(`${source}/resources/js/app.js`, `${dist}/js`)
    .js(`${source}/resources/js/account-admin.js`, `${dist}/js`)
    .js(`${source}/resources/js/employer-colleagues.js`, `${dist}/js`)
    .js(`${source}/resources/js/global-custom-fields.js`, `${dist}/js`)
    .js(`${source}/resources/js/custom-fields.js`, `${dist}/js`)
    .js(`${source}/resources/js/bulk-import.js`, `${dist}/js`)
    .js(`${source}/resources/js/export.js`, `${dist}/js`)
    .js(`${source}/resources/js/coupon.js`, `${dist}/js`)
    .js(`${source}/resources/js/components.js`, `${dist}/js`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/css/avatar.css`, `${source}/public/css`)
        .copy(`${dist}/css/currencies.css`, `${source}/public/css`)
        .copy(`${dist}/css/invoice.css`, `${source}/public/css`)
        .copy(`${dist}/css/review.css`, `${source}/public/css`)
        .copy(`${dist}/css/dashboard/style.css`, `${source}/public/css/dashboard`)
        .copy(`${dist}/css/dashboard/style-rtl.css`, `${source}/public/css/dashboard`)
        .copy(`${dist}/css/front-auth.css`, `${source}/public/css`)
        .copy(`${dist}/js/job.js`, `${source}/public/js`)
        .copy(`${dist}/js/avatar.js`, `${source}/public/js`)
        .copy(`${dist}/js/currencies.js`, `${source}/public/js`)
        .copy(`${dist}/js/app.js`, `${source}/public/js`)
        .copy(`${dist}/js/account-admin.js`, `${source}/public/js`)
        .copy(`${dist}/js/employer-colleagues.js`, `${source}/public/js`)
        .copy(`${dist}/js/global-custom-fields.js`, `${source}/public/js`)
        .copy(`${dist}/js/custom-fields.js`, `${source}/public/js`)
        .copy(`${dist}/js/bulk-import.js`, `${source}/public/js`)
        .copy(`${dist}/js/export.js`, `${source}/public/js`)
        .copy(`${dist}/js/coupon.js`, `${source}/public/js`)
        .copy(`${dist}/js/components.js`, `${source}/public/js`)
}
