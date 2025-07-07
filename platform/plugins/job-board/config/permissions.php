<?php

return [
    [
        'name' => 'Job Board',
        'flag' => 'plugins.job-board',
    ],
    [
        'name' => 'Jobs',
        'flag' => 'jobs.index',
        'parent_flag' => 'plugins.job-board',
    ],
    [
        'name' => 'Create',
        'flag' => 'jobs.create',
        'parent_flag' => 'jobs.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'jobs.edit',
        'parent_flag' => 'jobs.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'jobs.destroy',
        'parent_flag' => 'jobs.index',
    ],
    [
        'name' => 'Bulk Import Jobs',
        'flag' => 'import-jobs.index',
        'parent_flag' => 'jobs.index',
    ],
    [
        'name' => 'Export Jobs',
        'flag' => 'export-jobs.index',
        'parent_flag' => 'jobs.index',
    ],
    [
        'name' => 'Job Applications',
        'flag' => 'job-applications.index',
        'parent_flag' => 'plugins.job-board',
    ],
    [
        'name' => 'Edit',
        'flag' => 'job-applications.edit',
        'parent_flag' => 'job-applications.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'job-applications.destroy',
        'parent_flag' => 'job-applications.index',
    ],

    [
        'name' => 'Accounts',
        'flag' => 'accounts.index',
        'parent_flag' => 'plugins.job-board',
    ],
    [
        'name' => 'Create',
        'flag' => 'accounts.create',
        'parent_flag' => 'accounts.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'accounts.edit',
        'parent_flag' => 'accounts.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'accounts.destroy',
        'parent_flag' => 'accounts.index',
    ],
    [
        'name' => 'Import Accounts',
        'flag' => 'accounts.import',
        'parent_flag' => 'accounts.index',
    ],
    [
        'name' => 'Export Accounts',
        'flag' => 'accounts.export',
        'parent_flag' => 'accounts.index',
    ],
    [
        'name' => 'Packages',
        'flag' => 'packages.index',
        'parent_flag' => 'plugins.job-board',
    ],
    [
        'name' => 'Create',
        'flag' => 'packages.create',
        'parent_flag' => 'packages.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'packages.edit',
        'parent_flag' => 'packages.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'packages.destroy',
        'parent_flag' => 'packages.index',
    ],

    [
        'name' => 'Companies',
        'flag' => 'companies.index',
        'parent_flag' => 'plugins.job-board',
    ],
    [
        'name' => 'Create',
        'flag' => 'companies.create',
        'parent_flag' => 'companies.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'companies.edit',
        'parent_flag' => 'companies.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'companies.destroy',
        'parent_flag' => 'companies.index',
    ],
    [
        'name' => 'Export Companies',
        'flag' => 'export-companies.index',
        'parent_flag' => 'companies.index',
    ],
    [
        'name' => 'Import Companies',
        'flag' => 'import-companies.index',
        'parent_flag' => 'companies.index',
    ],

    [
        'name' => 'Custom Fields',
        'flag' => 'job-board.custom-fields.index',
        'parent_flag' => 'plugins.job-board',
    ],
    [
        'name' => 'Create',
        'flag' => 'job-board.custom-fields.create',
        'parent_flag' => 'job-board.custom-fields.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'job-board.custom-fields.edit',
        'parent_flag' => 'job-board.custom-fields.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'job-board.custom-fields.destroy',
        'parent_flag' => 'job-board.custom-fields.index',
    ],

    [
        'name' => 'Job Attributes',
        'flag' => 'job-attributes.index',
        'parent_flag' => 'plugins.job-board',
    ],
    [
        'name' => 'Job Categories',
        'flag' => 'job-categories.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'job-categories.create',
        'parent_flag' => 'job-categories.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'job-categories.edit',
        'parent_flag' => 'job-categories.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'job-categories.destroy',
        'parent_flag' => 'job-categories.index',
    ],
    [
        'name' => 'Job types',
        'flag' => 'job-types.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'job-types.create',
        'parent_flag' => 'job-types.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'job-types.edit',
        'parent_flag' => 'job-types.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'job-types.destroy',
        'parent_flag' => 'job-types.index',
    ],

    [
        'name' => 'Job skills',
        'flag' => 'job-skills.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'job-skills.create',
        'parent_flag' => 'job-skills.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'job-skills.edit',
        'parent_flag' => 'job-skills.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'job-skills.destroy',
        'parent_flag' => 'job-skills.index',
    ],

    [
        'name' => 'Job shifts',
        'flag' => 'job-shifts.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'job-shifts.create',
        'parent_flag' => 'job-shifts.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'job-shifts.edit',
        'parent_flag' => 'job-shifts.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'job-shifts.destroy',
        'parent_flag' => 'job-shifts.index',
    ],

    [
        'name' => 'Job experiences',
        'flag' => 'job-experiences.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'job-experiences.create',
        'parent_flag' => 'job-experiences.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'job-experiences.edit',
        'parent_flag' => 'job-experiences.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'job-experiences.destroy',
        'parent_flag' => 'job-experiences.index',
    ],

    [
        'name' => 'Language Levels',
        'flag' => 'language-levels.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'language-levels.create',
        'parent_flag' => 'language-levels.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'language-levels.edit',
        'parent_flag' => 'language-levels.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'language-levels.destroy',
        'parent_flag' => 'language-levels.index',
    ],

    [
        'name' => 'Career Levels',
        'flag' => 'career-levels.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'career-levels.create',
        'parent_flag' => 'career-levels.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'career-levels.edit',
        'parent_flag' => 'career-levels.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'career-levels.destroy',
        'parent_flag' => 'career-levels.index',
    ],

    [
        'name' => 'Functional Areas',
        'flag' => 'functional-areas.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'functional-areas.create',
        'parent_flag' => 'functional-areas.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'functional-areas.edit',
        'parent_flag' => 'functional-areas.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'functional-areas.destroy',
        'parent_flag' => 'functional-areas.index',
    ],

    [
        'name' => 'Degree types',
        'flag' => 'degree-types.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'degree-types.create',
        'parent_flag' => 'degree-types.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'degree-types.edit',
        'parent_flag' => 'degree-types.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'degree-types.destroy',
        'parent_flag' => 'degree-types.index',
    ],

    [
        'name' => 'Degree levels',
        'flag' => 'degree-levels.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'degree-levels.create',
        'parent_flag' => 'degree-levels.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'degree-levels.edit',
        'parent_flag' => 'degree-levels.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'degree-levels.destroy',
        'parent_flag' => 'degree-levels.index',
    ],

    [
        'name' => 'Tags',
        'flag' => 'job-board.tag.index',
        'parent_flag' => 'job-attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'job-board.tag.create',
        'parent_flag' => 'job-board.tag.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'job-board.tag.edit',
        'parent_flag' => 'job-board.tag.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'job-board.tag.destroy',
        'parent_flag' => 'job-board.tag.index',
    ],

    [
        'name' => 'Settings',
        'flag' => 'job-board.settings',
    ],
    [
        'name' => 'Invoices',
        'flag' => 'invoice.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'invoice.edit',
        'parent_flag' => 'invoice.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'invoice.destroy',
        'parent_flag' => 'invoice.index',
    ],
    [
        'name' => 'Reviews',
        'flag' => 'reviews.index',
        'parent_flag' => 'plugins.job-board',
    ],
    [
        'name' => 'Delete',
        'flag' => 'reviews.destroy',
        'parent_flag' => 'reviews.index',
    ],
    [
        'name' => 'Invoice Template',
        'flag' => 'invoice-template.index',
        'parent_flag' => 'plugins.job-board',
    ],
];
