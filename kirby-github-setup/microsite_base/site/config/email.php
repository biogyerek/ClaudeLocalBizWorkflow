<?php

return [
    'transport' => [
        'type'     => 'smtp',
        'host'     => 'yourMailHost',
        'port'     => 587, // or 465 for SSL
        'security' => 'tls', // or 'ssl' if using port 465
        'auth'     => true,
        'username' => 'yourEmail@yourDomain.com',
        'password' => 'yourEmailPassword'
    ],
];