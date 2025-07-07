$(() => {
    'use strict'

    $('.location-custom-fields').find('.select2').select2({
        minimumInputLength: 0,
    });

    $('.job-category').select2({
        minimumInputLength: 0,
        ajax: {
            url: $(this).data('url') || (window.siteUrl + '/ajax/categories'),
            dataType: 'json',
            delay: 250,
            type: 'GET',
            data: function (params) {
                return {
                    k: params.term, // search term
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data.data[0], function (item) {
                        return {
                            text: item.name,
                            id: item.id,
                            data: item
                        };
                    }),
                    pagination: {
                        more: (params.page * 10) < data.data.total
                    }
                };
            }
        }
    });

    const debounce = (cb, interval, immediate) => {
        let timeout

        return function () {
            const context = this, args = arguments
            const later = function() {
                timeout = null
                if (!immediate) cb.apply(context, args)
            };

            const callNow = immediate && !timeout

            clearTimeout(timeout);
            timeout = setTimeout(later, interval)

            if (callNow) {
                cb.apply(context, args)
            }
        }
    }

    $(document)
        .on('keyup', 'input.input-keysearch', debounce(function (e) {
            const form = $(this).closest('form')
            const url = form.data('quick-search-url')

            const job_categories = form.find('select[name="job_categories[]"]').val()
            const location = form.find('select[name="location"]').val()
            const keyword = e.target.value

            $.ajax({
                url,
                type: 'GET',
                data: {
                    job_categories,
                    location,
                    keyword
                },
                success: (data) => {
                    if (data.error) {
                        return
                    }

                    form.closest('.form-find').append(data.data.html)
                },
                error: (error) => {
                    handleError(error)
                }
            })
        }, 500))
        .on('keydown', 'input.input-keysearch', function () {
            $(this).closest('.form-find').find('.quick-search-result').remove()
        })

    $(document).on('click', function (e) {
        if (!$(e.target).is('input.input-keysearch')) {
            $('.form-find').find('.quick-search-result').remove()
        }
    })
})
