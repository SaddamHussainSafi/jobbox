<?php

return [
    'name' => 'Job Applications',
    'edit' => 'View job application',
    'tables' => [
        'email' => 'Email',
        'phone' => 'Phone',
        'name' => 'Name',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'time' => 'Time',
        'message' => 'Summary',
        'resume' => 'Resume',
        'cover_letter' => 'Cover Letter',
        'position' => 'Position',
        'download_resume' => 'Download Resume',
    ],
    'information' => 'Information',
    'email' => [
        'header' => 'Email',
        'title' => 'We received a new job application from the website!',
        'success' => 'Applied successfully!',
        'failed' => 'Can\'t apply on this time, please try again later!',
    ],
    'sender' => 'Sender',
    'sender_email' => 'Email',
    'statuses' => [
        'pending' => 'Pending',
        'checked' => 'Checked',
    ],
    'notifications' => [
        'title' => 'New job application',
        'description' => 'You have a new job application from :name',
        'view' => 'View',
    ],
];
