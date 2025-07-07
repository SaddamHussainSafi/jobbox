'use strict'

$(document).ready(() => {
    $(document)
        .on('click', '#confirm-add-credit-button', (event) => {
            event.preventDefault()

            const button = $(event.currentTarget)
            const modal = button.closest('.modal')
            const form = modal.find('form')

            $httpClient
                .make()
                .withButtonLoading(button)
                .post(form.prop('action'), form.serialize())
                .then(({ data }) => {
                    Botble.showNotice('success', data.message)
                    modal.modal('hide')
                    form.get(0).reset()

                    $('#credit-histories').load(`${$('.page form').prop('action')} #credit-histories > *`)
                })
        })
        .on('click', '.step-item', (event) => {
            $(event.currentTarget).find('fieldset').slideToggle()
        })
        .on('show.bs.modal', '#edit-account-entity-modal', (e) => {
            const currentTarget = $(e.relatedTarget)
            const modal = $(e.currentTarget)
            const table = currentTarget.data('table')
            const modalTitle = currentTarget.data('modal-title')

            modal.find('.modal-title').text(modalTitle)
            modal.find('[data-bb-toggle="confirm-edit-entity-button"]').data('table', table)

            $httpClient
                .make()
                .get(currentTarget.prop('href'))
                .then(({ data }) => {
                    modal.find('.modal-body').html(data)
                })
        })
        .on('click', '[data-bb-toggle="confirm-edit-entity-button"]', (event) => {
            event.preventDefault()

            const button = $(event.currentTarget)
            const modal = button.closest('.modal')
            const form = modal.find('form')
            const table = button.data('table')

            $httpClient
                .make()
                .withButtonLoading(button)
                .post(form.prop('action'), form.serialize())
                .then(({ data }) => {
                    Botble.showNotice('success', data.message)
                    modal.modal('hide')
                    form.get(0).reset()

                    $(table).load(`${$('.page-body form').prop('action')} ${table} > *`)
                })
        })
        .on('click', '#confirm-add-entity-button', (event) => {
            event.preventDefault()

            const button = $(event.currentTarget)
            const modal = button.closest('.modal')
            const form = modal.find('form')
            let table = null

            switch (modal.prop('id')) {
                case 'add-language-modal':
                    table = '#languages-table'
                    break
                case 'add-experience-modal':
                    table = '#experiences-table'
                    break
                case 'add-education-modal':
                    table = '#educations-table'
                    break
            }

            $httpClient
                .make()
                .withButtonLoading(button)
                .post(form.prop('action'), form.serialize())
                .then(({ data }) => {
                    Botble.showNotice('success', data.message)
                    modal.modal('hide')
                    form.get(0).reset()

                    $(table).load(`${$('.page-body form').prop('action')} ${table} > *`)
                })
        })
        .on('show.bs.modal', '#modal-confirm-delete', (e) => {
            const button = $(e.relatedTarget)
            const modal = $(e.currentTarget)

            modal.find('[data-bb-toggle="confirm-delete"]').data('table', button.data('table'))
            modal.find('[data-bb-toggle="confirm-delete"]').data('url', button.prop('href'))
        })
        .on('click', '[data-bb-toggle="confirm-delete"]', (event) => {
            event.preventDefault()

            const button = $(event.currentTarget)
            const table = button.data('table')

            $httpClient
                .make()
                .withButtonLoading(button)
                .delete(button.data('url'))
                .then(({ data }) => {
                    Botble.showNotice('success', data.message)
                    button.closest('.modal').modal('hide')

                    $(table).load(`${$('.page-body form').prop('action')} ${table} > *`)
                })
        })
})
