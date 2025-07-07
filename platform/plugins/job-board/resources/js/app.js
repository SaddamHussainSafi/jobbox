/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./form')
require('./avatar')

;('use strict')
$(() => {
    if (window.noticeMessages) {
        window.noticeMessages.forEach((message) => {
            Botble.showNotice(
                message.type,
                message.message,
                message.type === 'error'
                    ? window.trans && window.trans.error
                        ? window.trans.error
                        : 'Error!'
                    : window.trans && window.trans.success
                    ? window.trans.success
                    : 'Success!'
            )
        })
    }

    $(document).on('click', '.button-renew', (event) => {
        event.preventDefault()
        let _self = $(event.currentTarget)

        $('.button-confirm-renew')
            .data('section', _self.data('section'))
            .data('parent-table', _self.closest('.table').prop('id'))
        $('.modal-confirm-renew').modal('show')
    })

    $('.button-confirm-renew').on('click', (event) => {
        event.preventDefault()
        let _self = $(event.currentTarget)

        let url = _self.data('section')

        _self.addClass('button-loading')

        $.ajax({
            url: url,
            type: 'POST',
            success: (data) => {
                if (data.error) {
                    Botble.showError(data.message)
                } else {
                    window.LaravelDataTables[_self.data('parent-table')]
                        .row($('a[data-section="' + url + '"]').closest('tr'))
                        .remove()
                        .draw()
                    Botble.showSuccess(data.message)
                }

                _self.closest('.modal').modal('hide')
                _self.removeClass('button-loading')
            },
            error: (data) => {
                Botble.handleError(data)
                _self.removeClass('button-loading')
            },
        })
    })

    $(document).on('click', '.btn_remove_image', (event) => {
        event.preventDefault()
        $(event.currentTarget).closest('.image-box').find('.preview-image-wrapper').hide()
        $(event.currentTarget).closest('.image-box').find('.image-data').val('')
    })

    function handleToggleDrawer() {
        $('.navbar-toggler').on('click', function() {
            $('.ps-drawer--mobile').addClass('active')
            $('.ps-site-overlay').addClass('active')
        })

        $('.ps-drawer__close').on('click', function() {
            $('.ps-drawer--mobile').removeClass('active')
            $('.ps-site-overlay').removeClass('active')
        })

        $('body').on('click', function(e) {
            if ($(e.target).siblings('.ps-drawer--mobile').hasClass('active')) {
                $('.ps-drawer--mobile').removeClass('active')
                $('.ps-site-overlay').removeClass('active')
            }
        })
    }

    handleToggleDrawer()

    const refreshCoupon = (url) => {
        $httpClient.make()
            .get(url)
            .then(({ data }) => {
                $('.order-detail-box').html(data.data)
            })
    }

    $(document)
        .on('click', '.toggle-coupon-form', () => $(document).find('.coupon-form').toggle('fast'))
        .on('click', '.apply-coupon-code', (e) => {
            e.preventDefault()

            const $button = $(e.currentTarget)

            $httpClient.make()
                .withButtonLoading($button)
                .post($button.data('url'), {
                    coupon_code: $button.closest('form').find('input[name="coupon_code"]').val(),
                })
                .then(({ data }) => {
                    Botble.showSuccess(data.message)

                    const refreshUrl = $('.order-detail-box').data('refresh-url')
                    refreshCoupon(refreshUrl)
                })
        })
        .on('click', '.remove-coupon-code', (e) => {
            e.preventDefault()

            const $button = $(e.currentTarget)

            $httpClient.make()
                .post($button.data('url'))
                .then(({ data }) => {
                    Botble.showSuccess(data.message)

                    const refreshUrl = $('.order-detail-box').data('refresh-url')
                    refreshCoupon(refreshUrl)
                })
        })
})
