<?php
return [
    'groups' => [
        'welcome' => [
            'dashboard',
            'login.store',
            'register.store'
        ],
        'dashboard' => [
            'dashboard',
            'uploads.*',
            'logout',
            'export_original_sheet'
        ],
        'sheet' => [
            'dashboard',
            'logout',
            'uploads.*'
        ]
    ]
];
