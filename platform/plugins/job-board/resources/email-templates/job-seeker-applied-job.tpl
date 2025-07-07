{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <td class="bb-content bb-pb-0" align="center">
                <table class="bb-icon bb-icon-lg bb-bg-blue" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td valign="middle" align="center">
                            <img src="{{ 'check' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                        </td>
                    </tr>
                    </tbody>
                </table>

                <h1 class="bb-text-center bb-m-0 bb-mt-md">Job Application Confirmation</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pb-0">
                <p>Dear {{ job_application_name }},</p>
                <p>Thank you for your interest in the {{ job_name }} position at {{ company_name }}. We are pleased to confirm that your application has been successfully submitted through our system.</p>
                <p>Our recruitment team is reviewing your qualifications, and we will contact you if your skills and experience match the requirements for this role. Please note that due to the high volume of applications, this process may take some time.</p>
                <p>Thank you again for applying!</p>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0"></table>
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
