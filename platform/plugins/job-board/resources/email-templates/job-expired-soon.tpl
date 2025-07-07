{{ header }}

<p>Hi {{ job_author }}!</p>
<p>Your job <a href="{{ job_url }}">{{ job_name }}</a> will be expired in {{ job_expired_after }} days.</p>
<p>Please <a href="{{ job_list }}">go here</a> to renew your job.</p>

{{ footer }}
