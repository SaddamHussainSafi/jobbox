$(() => {
    const $form = $(document).find('form.coupon-form')

    $form
        .on('click', '[data-bb-toggle="coupon-generate-code"]', (e) => {
            e.preventDefault()

            const $currentTarget = $(e.currentTarget)

            $httpClient.make()
                .withButtonLoading($currentTarget)
                .post($currentTarget.data('url'))
                .then(({ data }) => {
                    $form.find('input[name="code"]').val(data.data)
                })
        })
        .on('change', 'select[name="type"]', (e) => {
            let symbol

            if (e.currentTarget.value === 'percentage') {
                symbol = '%'
            } else {
                symbol = window.coupon.currency || '$'
            }

            $form.find('span.icon-type').text(symbol)
        })
        .on('change', 'input[name="never_expired"]', (e) => {
            if (e.currentTarget.checked) {
                $form.find('input[name="expires_date"]').prop('disabled', true)
                $form.find('input[name="expires_time"]').prop('disabled', true)
            } else {
                $form.find('input[name="expires_date"]').prop('disabled', false)
                $form.find('input[name="expires_time"]').prop('disabled', false)
            }
        })
        .on('change', 'input[name="is_unlimited"]', (e) => {
            const $quantity = $form.find('input[name="quantity"]').closest('.mb-3.position-relative')

            if (e.currentTarget.checked) {
                $quantity.addClass('d-none')
            } else {
                $quantity.removeClass('d-none')
            }
        })

    $form.find('input[name="never_expired"]').trigger('change')
})
