(function ($) {
    const loading = $('.loading-ring')

    const $candidatesList = $('.candidates-list');

    let filterTimeout = null
    let filterAjax = null

    loading.hide()

    $candidatesList.find('.box-list-character').on('click', '.keyword', function () {
        if (filterTimeout) {
            clearTimeout(filterTimeout)
        }

        setCurrentPage(1)

        $(this).toggleClass('active')
        $candidatesList.find('.keyword.active').not($(this)).removeClass('active')

        if ($(this).hasClass('active')) {
            $candidatesList.find('input[name="keyword"]').val($(this).data('keyword'))
        } else {
            $candidatesList.find('input[name="keyword"]').val('')
        }

        filterTimeout = setTimeout(function () {
            getCandidates()
        }, 200)
    })

    $candidatesList.on('click', '.per-page', function () {
        setCurrentPage(1)
        getCandidates()
    })

    $('.sort-by').on('click', function () {
        setCurrentPage(1)
        $candidatesList.find('input[name="sort_by"]').val($(this).data('sort-by'))
        getCandidates()
    })

    $candidatesList.on('click', 'a.pagination-button', function(e) {
        e.preventDefault()
        $candidatesList.find('input[name="page"]').val($(this).data('page'))
        getCandidates()
    })

    function setCurrentPage(page) {
        $candidatesList.find('input[name="page"]').val(page)
    }

    function getCandidates() {
        const form = $('form.candidate-filter-form')
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
                $('.candidate-list').html(response.data.list)
                $('.text-showing').text(response.data.total_text)
            },
            complete: () => {
                loading.hide()
            }
        })
    }
})(jQuery)
