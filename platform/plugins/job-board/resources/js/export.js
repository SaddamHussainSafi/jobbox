$(() => {
    let isExporting = false

    $(document).on('click', '[data-bb-toggle="export-data"]', function (event) {
        event.preventDefault()

        if (isExporting) {
            return
        }

        const $currenTarget = $(event.currentTarget)

        $httpClient.make()
            .withButtonLoading($currenTarget)
            .withResponseType('blob')
            .post($currenTarget.prop('href'))
            .then(({ data }) => {
                const a = document.createElement('a')
                const url = window.URL.createObjectURL(data)
                a.href = url
                a.download = $currenTarget.data('filename')
                document.body.append(a)
                a.click()
                a.remove()
                window.URL.revokeObjectURL(url)
            })
            .finally(() => isExporting = false)
    })
})
