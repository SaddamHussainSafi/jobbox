
<div>
    <table width="100%">
        <tr>
            <td width="40">
                <div >
                    <img src="${item.company_logo_thumb}" width="40" alt="${item.company_name}">
                </div>
            </td>
            <td>
                <div class="infomarker">
                    <h5 class="${item.company_url ? '' : 'd-none'}">
                        <a href="${item.company_url}" target="_blank">${item.company_name}</a>
                    </h5>
                    <div class="text-info">
                        <strong>${item.job_name}</strong>
                    </div>
                    <div class="text-info">
                        <i class="mdi mdi-account"></i>
                        <span>{{ __(':number Vacancy', ['number' => '${item.number_of_positions}'])}}</span>
                        <span class="${item.job_type ? '' : 'd-none'}">
                            <span>-</span>
                            <span>${item.job_type}</span>
                        </span>
                    </div>
                    <div class="text-muted ${item.full_address ? '' : 'd-none'}">
                        <i class="uil uil-map"></i>
                        <span>${item.full_address}</span>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
