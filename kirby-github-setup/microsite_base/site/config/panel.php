<?php

return [
    'menu' => [

        'site'  => [
            'icon'  => 'home',
            'label' => 'Site',
            'link'  => 'site'
        ],
        'homepage'  => [
            'icon'  => 'page',
            'label' => 'Homepage',
            'link'  => 'pages/home'
        ],
        'locations' => [
            'icon'  => 'pin',
            'label' => 'Locations',
            'link'  => 'site?tab=locations',
        ],
        'services' => [
            'icon'  => 'store',
            'label' => 'Services',
            'link'  => 'site?tab=services',
        ],
        '-',
        'service'  => [
            'icon'  => 'add',
            'label' => 'Add service',
            'dialog'=> 'pages/create?parent=/site&view=site&section=services'
        ],
        '-',
        'setup'  => [
            'icon'  => 'sparkling',
            'label' => 'Site setup',
            'link'=> 'site?tab=setup'
        ],
        'settings'  => [
            'icon'  => 'folder-structure',
            'label' => 'Site-wide settings',
            'link'=> 'site?tab=site-settings'
        ],
        'prompting'  => [
            'icon'  => 'wand',
            'label' => 'Prompting',
            'link'=> 'site?tab=prompting'
        ],
        'SEO'  => [
            'icon'  => 'search',
            'label' => 'SEO',
            'link'=> 'site?tab=seo'
        ],
        'menu'  => [
            'icon'  => 'menu',
            'label' => 'Menus',
            'link'=> 'site?tab=menus'
        ],
        'offers'  => [
            'icon'  => 'money',
            'label' => 'Offers',
            'link'=> 'site?tab=offers'
        ],
        '-',
        'languages' => [
            'label' => 'Language',
            'link'  => 'languages/en'
        ],
        'users',
        'system',
    ],
];