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
                            <img src="{{ 'hourglass' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                        </td>
                    </tr>
                    </tbody>
                </table>

                <h1 class="bb-text-center bb-m-0 bb-mt-md">New Job Posted</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pb-0">
                <p>Hi Admin,</p>
                <p>We are pleased to inform you that a new job listing has been posted by an employer on our platform.</p>
            </td>
        </tr>

        <tr>
            <td class="bb-content bb-pt-0">
                <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td class="bb-bb-col">
                            <h4>Job Post</h4>
                            <div>Employer: <strong>{{ job_author }}</strong></div>
                            <div>Job Title: <strong>{{ job_name }}</strong></div>
                            <div>Admin Panel Link: <a href="{{ job_url }}">here</a></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
