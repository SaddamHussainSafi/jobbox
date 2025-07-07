$(() => {
    const container = $(document).find('#bulk-import')
    const $form = container.find('.form-import-data')
    const $button = container.find('.btn-import')
    let failedRows = []
    let totalRows = 0

    $(document)
        .on('click', '.btn-import' , function (event) {
            event.preventDefault()

            if (dropzone.getQueuedFiles().length > 0) {
                Botble.showButtonLoading($button)

                container.find('.show-errors').hide()
                totalRows = 0
                failedRows = []

                dropzone.processQueue()
            }

            dropzone.on('sending', function () {
                container.find('.bulk-import-message')
                    .removeClass('alert-success')
                    .addClass('alert-info')
                    .text($button.data('uploading-text'))
                    .show()

            })

            dropzone.on('error', function (file, message) {
                Botble.showError(message.message)
            })
        })

    const validateData = (file, offset = 0, limit = 1000) => {

        if (offset === 0) {
            container.find('.bulk-import-message').text($button.data('validating-text'))
        }

        $httpClient
            .make()
            .post($form.data('validate-url'), {
                file,
                offset,
                limit,
                update_existing: $('#update-existing').is(':checked') ? 1 : 0,
            })
            .then(({ data: response }) => {
                const { data, message } = response

                // Check if there are any validation errors in the current batch
                if (data && data.failed && Object.keys(data.failed).length > 0) {
                    // We have validation errors, stop the validation process
                    failedRows = [...failedRows, ...data.failed]
                    totalRows += data.count

                    // Display the errors immediately
                    const $listing = container.find('#imported-listing')
                    const $show = container.find('.show-errors')

                    const failureTemplate = $(document).find('#failure-template').html()

                    let result = ''
                    failedRows.forEach((val, index) => {
                        // If row is undefined, use the index + 1 as the row number
                        const rowNumber = val.row !== undefined ? val.row : index + 1;
                        // Format the error messages
                        let errorMessages = [];

                        // Handle different error formats
                        if (Array.isArray(val.errors)) {
                            // Direct array of error messages
                            errorMessages = val.errors;
                        } else if (typeof val.errors === 'object' && val.errors !== null) {
                            // Object with field names as keys and arrays of error messages as values
                            Object.entries(val.errors).forEach(([field, messages]) => {
                                if (Array.isArray(messages)) {
                                    // Add field name to each message
                                    messages.forEach(message => {
                                        errorMessages.push(`${field}: ${message}`);
                                    });
                                } else if (typeof messages === 'string') {
                                    errorMessages.push(`${field}: ${messages}`);
                                }
                            });
                        }

                        // If errorMessages is still empty, try to extract errors directly from the object
                        if (errorMessages.length === 0 && typeof val === 'object' && val !== null) {
                            Object.entries(val).forEach(([field, value]) => {
                                if (field !== 'row' && Array.isArray(value)) {
                                    value.forEach(message => {
                                        errorMessages.push(`${field}: ${message}`);
                                    });
                                } else if (field !== 'row' && typeof value === 'string') {
                                    errorMessages.push(`${field}: ${value}`);
                                }
                            });
                        }

                        result += failureTemplate
                            .replace('__row__', rowNumber)
                            .replace('__errors__', errorMessages.join(', '))
                    })

                    $show.show()

                    container.find('.main-form-message').show()
                    $listing.show().html(result)

                    failedRows = []
                    totalRows = 0
                    Botble.hideButtonLoading($button)
                    dropzone.removeAllFiles()
                    container.find('.bulk-import-message').hide()
                } else if (data && data.count > 0) {
                    // No errors in this batch, continue validation
                    container.find('.bulk-import-message').show()
                    container.find('.bulk-import-message').text(message)
                    validateData(file, data.offset)
                    failedRows = [...failedRows, ...data.failed]
                    totalRows += data.count
                } else {
                    // No errors and no more data to validate, proceed to import
                    importData(file)
                }
            })
    }

    const importData = (file, offset = 0, limit = 10) => {
        if (offset === 0) {
            container.find('.bulk-import-message').text($button.data('importing-text'))

            Botble.showButtonLoading($button)
        }

        $httpClient
            .make()
            .post($form.data('import-url'), {
                file,
                offset,
                limit,
                update_existing: $('#update-existing').is(':checked') ? 1 : 0,
            })
            .then(({data: response}) => {
                const {data, message} = response
                const processing = container.find('.processing')
                const process = processing.find('.process')

                if (data && data.count > 0) {
                    processing.show()
                    importData(file, data.offset)
                    process.css('width', (data.offset/totalRows)*100 + '%')
                    container.find('.bulk-import-message').html(message)
                } else {
                    Botble.showSuccess(message)

                    if (data.total_message) {
                        container.find('.main-form-message').show()
                        container
                            .find('.bulk-import-message')
                            .removeClass('alert-info')
                            .addClass('alert-success')
                            .text(data.total_message)
                            .show()
                        dropzone.removeAllFiles()

                        processing.hide()
                        totalRows = 0
                        Botble.hideButtonLoading($button)
                    }
                }
            })
    }

    const dropzone = new Dropzone('.import-dropzone', {
        url: $form.data('upload-url'),
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': $form.find('input[name=_token]').val(),
        },
        previewTemplate: $(document).find('#preview-template').html(),
        autoProcessQueue: false,
        chunking: true,
        chunkSize: 1048576,
        acceptedFiles: $('.import-dropzone').data('mimetypes'),
        maxFiles: 1,
        maxfilesexceeded: function (file) {
            this.removeFile(file)
        },
        success: function (file, response) {
            const { data, message } = response

            if (data && data.file_path) {
                validateData(data.file_path)
            }
        },
    })

    let isDownloadingTemplate = false

    $(document).on('click', '.download-template', function (event) {
        event.preventDefault()
        if (isDownloadingTemplate) {
            return
        }
        const $this = $(event.currentTarget)
        const extension = $this.data('extension')
        const $content = $this.html()

        $this.html($this.data('downloading'))
        $this.addClass('text-secondary')
        isDownloadingTemplate = true

        $httpClient
            .make()
            .withResponseType('blob')
            .post($this.data('url'), { extension })
            .then(({ data }) => {
                let a = document.createElement('a')
                let url = window.URL.createObjectURL(data)
                a.href = url
                a.download = $this.data('filename')
                document.body.append(a)
                a.click()
                a.remove()
                window.URL.revokeObjectURL(url)
            })
            .finally(() => {
                setTimeout(() => {
                    $this.html($content)
                    $this.removeClass('text-secondary')
                    isDownloadingTemplate = false
                }, 2000)
            })
    })
})
