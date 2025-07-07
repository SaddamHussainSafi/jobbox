<?php

return [
    [
        'name' => 'Teams',
        'flag' => 'team.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'team.create',
        'parent_flag' => 'team.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'team.edit',
        'parent_flag' => 'team.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'team.destroy',
        'parent_flag' => 'team.index',
    ],
];
