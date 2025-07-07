(function ($) {
    const loading = $('.loading-ring')

    const $companyList = $('.companies-list');

    let filterTimeout = null
    let filterAjax = null

    loading.hide()

    $companyList.find('.box-list-character').on('click', '.filter-by-word', function (e) {
        e.preventDefault();

        if (filterTimeout) {
            clearTimeout(filterTimeout);
        }

        setCurrentPage(1)

        $(this).toggleClass('active')
        $companyList.find('.filter-by-word.active').not($(this)).removeClass('active');

        if ($(this).hasClass('active')) {
            $companyList.find('input[name="keyword"]').val($(this).data('keyword'));
        } else {
            $companyList.find('input[name="keyword"]').val('');
        }

        filterTimeout = setTimeout(function () {
            getCompanies();
        }, 200)
    })

    $companyList.on('click', '.per-page-item', function (e) {
        e.preventDefault();
        setCurrentPage(1)
        $companyList.find('input[name="per_page"]').val($(this).data('per-page'));
        getCompanies();
    })

    $companyList.on('click', '.dropdown-sort-by', function (e) {
        e.preventDefault();

        $companyList.find('input[name="sort_by"]').val($(this).data('sortBy'));
        getCompanies();
    })

    $companyList.on('click', 'a.pagination-button', function(e) {
        e.preventDefault();
        setCurrentPage($(this).data('page'))
        getCompanies();
    })

    $companyList.on('click', '.layout-company', function(e) {
        e.preventDefault();

        $companyList.find('input[name="layout"]').val($(this).data('layout'));
        getCompanies();
    })

    function setCurrentPage(page) {
        $companyList.find('input[name="page"]').val(page);
    }

    function getCompanies() {
        const form = $('form#company-filter-form');
        const formData = form.serialize();
        const action = form.attr('action');
        const currentUrl = location.origin + location.pathname;

        if (filterAjax) {
            filterAjax.abort();
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
                $('.company-listing').html(response.data);
            },
            complete: () => {
                loading.hide();
            }
        })
    }
})(jQuery)
