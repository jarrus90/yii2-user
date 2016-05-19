<?php

/*
 * User module config
 */
return [
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/modules/User/views' => [
                        '@style/layouts/user',
                        '@app/modules/User/views', // Override
                    ]
                ]
            ],
        ],
    ],
];
