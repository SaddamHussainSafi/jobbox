(function ($) {
    const loading = $('.loading-ring')

    const $jobList = $('.box-list-jobs');
    let filterAjax = null

    loading.hide()

    $jobList.on('click', 'a.pagination-button', function(e) {
        e.preventDefault()
        $('form#job-pagination-form').find('input[name="page"]').val($(this).data('page'))
        getJobs()
    })

    function getJobs() {
        const form = $('form#job-pagination-form')
        const formData = form.serialize()
        const action = form.attr('action')
        const currentUrl = location.origin + location.pathname;

        if (filterAjax) {
            filterAjax.abort()
        }

        filterAjax = $.ajax({
            url: action,
            method: 'GET',
            data: formData,
            beforeSend: () => {
                loading.show()
                window.history.pushState(
                    formData,
                    null,
                    `${currentUrl}?${formData}`
                )
            },
            success: (response) => {
                $('.box-list-jobs').html(response.data)
            },
            complete: () => {
                loading.hide()
            }
        })
    }
})(jQuery)
