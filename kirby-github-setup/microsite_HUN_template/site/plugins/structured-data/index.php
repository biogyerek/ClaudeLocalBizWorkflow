<?php

use Kirby\Cms\App;
use Kirby\Cms\Page;

App::plugin('custom/structured-data', [
    'siteMethods' => [
        'structuredData' => function () {
            $site = $this;
            $page = kirby()->site()->page();

            if (!$page) {
                $page = $site->homePage();
            }

            $schemas = [];
            $baseUrl = $site->url();

            // Get company data from site settings
            $companyName = $site->title()->or($site->companyname())->value();
            $phone = $site->phone()->value();
            $email = $site->email()->value();
            $address = $site->address()->value();

            // Try to get from placeholders if main fields are empty
            if (empty($phone) && $site->placeholders()->isNotEmpty()) {
                foreach ($site->placeholders()->toStructure() as $placeholder) {
                    if ($placeholder->key()->value() == 'phone') {
                        $phone = $placeholder->value()->value();
                        break;
                    }
                }
            }

            // Parse address into components (if possible)
            $addressParts = [
                'streetAddress' => '',
                'addressLocality' => 'Budapest',
                'postalCode' => '',
                'addressCountry' => 'HU'
            ];

            if (!empty($address)) {
                // Try to parse address format like "Budapest, Király utca 82, 1068"
                // or "1068 Budapest, Király utca 82"
                if (preg_match('/(\d{4})\s*Budapest[,\s]+(.+)/', $address, $matches)) {
                    $addressParts['postalCode'] = $matches[1];
                    $addressParts['streetAddress'] = trim($matches[2]);
                } elseif (preg_match('/Budapest[,\s]+(.+?)[,\s]+(\d{4})/', $address, $matches)) {
                    $addressParts['streetAddress'] = trim($matches[1]);
                    $addressParts['postalCode'] = $matches[2];
                } else {
                    $addressParts['streetAddress'] = $address;
                }
            }

            // 1. Organization / LocalBusiness - minden oldalon
            $organization = [
                '@context' => 'https://schema.org',
                '@type' => 'LocalBusiness',
                '@id' => $baseUrl . '/#organization',
                'name' => $companyName,
                'image' => $baseUrl . '/assets/images/logo.png',
                'url' => $baseUrl,
                'telephone' => $phone,
                'priceRange' => '$$',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $addressParts['streetAddress'],
                    'addressLocality' => $addressParts['addressLocality'],
                    'postalCode' => $addressParts['postalCode'],
                    'addressCountry' => $addressParts['addressCountry']
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => '47.5055',
                    'longitude' => '19.0632'
                ],
                'openingHoursSpecification' => [
                    [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                        'opens' => '08:00',
                        'closes' => '18:00'
                    ],
                    [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Saturday',
                        'opens' => '08:00',
                        'closes' => '14:00'
                    ]
                ],
                'areaServed' => []
            ];

            // Load locations from CSV
            $csvFilePath = kirby()->root('content') . '/locations.csv';
            if (file_exists($csvFilePath)) {
                $csv = csv($csvFilePath, ';');
                $areaServed = [];

                foreach ($csv as $row) {
                    $mainLocation = $row['MainLocation'] ?? null;
                    $subLocation = $row['SubLocation'] ?? null;

                    if ($subLocation) {
                        $areaServed[] = [
                            '@type' => 'City',
                            'name' => $subLocation
                        ];
                    } elseif ($mainLocation) {
                        $areaServed[] = [
                            '@type' => 'City',
                            'name' => $mainLocation
                        ];
                    }
                }

                $organization['areaServed'] = $areaServed;
            }

            // Service catalog
            $organization['hasOfferCatalog'] = [
                '@type' => 'OfferCatalog',
                'name' => 'Faház építési szolgáltatások',
                'itemListElement' => [
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Egyedi faház építés',
                            'description' => 'Egyedi tervezésű faházak kivitelezése magas minőségű alapanyagokkal és precíz munkával.'
                        ]
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Könnyű szerkezetes faház építés',
                            'description' => 'Modern könnyű szerkezetes faházak építése energiatakarékos megoldásokkal.'
                        ]
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Faház tervezés',
                            'description' => 'Szakszerű faház tervezés egyedi igények szerint, minden részletre odafigyelve.'
                        ]
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Faház felújítás',
                            'description' => 'Meglévő faházak felújítása, korszerűsítése szakszerű kivitelezéssel.'
                        ]
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Faház bővítés',
                            'description' => 'Faházak bővítése az eredeti stílushoz illeszkedő megoldásokkal.'
                        ]
                    ]
                ]
            ];

            // Add email if available
            if (!empty($email)) {
                $organization['email'] = $email;
            }

            $schemas[] = $organization;

            // 2. WebSite schema - csak főoldalon
            if ($page->isHomePage()) {
                $pageDescription = $page->metaDescription()->or('Professzionális faház építés Budapesten és környékén.')->value();

                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    '@id' => $baseUrl . '/#website',
                    'url' => $baseUrl,
                    'name' => $companyName,
                    'description' => $pageDescription,
                    'publisher' => [
                        '@id' => $baseUrl . '/#organization'
                    ],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => $baseUrl . '/kereses?q={search_term_string}'
                        ],
                        'query-input' => 'required name=search_term_string'
                    ],
                    'inLanguage' => 'hu-HU'
                ];
            }

            // 3. Service schema - szolgáltatás oldalakon
            if ($page->intendedTemplate() == 'service') {
                $serviceName = $page->title()->value();
                $serviceDescription = $page->text()->excerpt(200);

                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => $serviceName,
                    'description' => $serviceDescription,
                    'serviceType' => 'House Construction',
                    'provider' => [
                        '@id' => $baseUrl . '/#organization'
                    ],
                    'areaServed' => [
                        [
                            '@type' => 'City',
                            'name' => 'Budapest'
                        ],
                        [
                            '@type' => 'State',
                            'name' => 'Pest megye'
                        ]
                    ],
                    'offers' => [
                        '@type' => 'Offer',
                        'availability' => 'https://schema.org/InStock',
                        'priceCurrency' => 'HUF'
                    ]
                ];
            }

            // 4. Location-specific schema - location oldalakon
            if ($page->intendedTemplate() == 'location') {
                // Get location name from CSV or page title
                $locationName = $page->title()->value();
                $locationName = str_replace('fahaz-kivitelezes-', '', $page->slug());

                // Try to get proper name from CSV
                if (file_exists($csvFilePath)) {
                    $csv = csv($csvFilePath, ';');
                    foreach ($csv as $row) {
                        $mainLocation = $row['MainLocation'];
                        $subLocation = $row['SubLocation'] ?? null;
                        $slug = \Kirby\Toolkit\Str::slug($mainLocation);

                        if ($subLocation) {
                            $subSlug = \Kirby\Toolkit\Str::slug($subLocation);
                            if ('fahaz-kivitelezes-' . $slug . '-' . $subSlug == $page->slug()) {
                                $locationName = $subLocation;
                                break;
                            }
                        } else {
                            if ('fahaz-kivitelezes-' . $slug == $page->slug()) {
                                $locationName = $mainLocation;
                                break;
                            }
                        }
                    }
                }

                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => 'Faház kivitelezés ' . $locationName,
                    'description' => 'Professzionális faház építési szolgáltatások ' . $locationName . ' területén.',
                    'serviceType' => 'House Construction',
                    'provider' => [
                        '@id' => $baseUrl . '/#organization'
                    ],
                    'areaServed' => [
                        '@type' => 'City',
                        'name' => $locationName
                    ]
                ];
            }

            // 5. BreadcrumbList - minden oldalon (kivéve főoldal)
            if (!$page->isHomePage()) {
                $items = [];
                $position = 1;

                // Home
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => 'Kezdőlap',
                    'item' => $baseUrl
                ];

                // Parents
                $parents = $page->parents()->flip();
                foreach ($parents as $parent) {
                    $items[] = [
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => $parent->title()->value(),
                        'item' => str_replace($site->url(), $baseUrl, $parent->url())
                    ];
                }

                // Current page
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $page->title()->value(),
                    'item' => str_replace($site->url(), $baseUrl, $page->url())
                ];

                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => $items
                ];
            }

            // Output all schemas
            $output = '';
            foreach ($schemas as $schema) {
                $output .= '<script type="application/ld+json">' . "\n";
                $output .= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                $output .= "\n" . '</script>' . "\n";
            }

            return $output;
        }
    ]
]);
