<?php

return [
    'tobimori.seo.lang'     => 'en_US',
    'tobimori.seo.robots'   => [
        'active' => true,
        'followPageStatus' => 'false',
        'content' => [
            '*' => [
                'Disallow' => ['/']
            ]
        ],
    ],
    'tobimori.seo.sitemap' => [
        'excludeTemplates' => ['error'], // Exclude templates from sitemap
    ],
];