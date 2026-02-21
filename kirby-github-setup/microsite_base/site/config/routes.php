<?php

return [
    // Route for sublocations without parent slug
    [
        'pattern' => 'asztalos-(:any)-(:all)',
        'action' => function ($mainLocationSlug, $subLocationSlug) {
            $mainLocationSlug = Str::slug(urldecode($mainLocationSlug));
            $subLocationSlug = Str::slug(urldecode(str_replace([' ', '.', ','], '-', $subLocationSlug)));

            // Find the sublocation page directly
            $subLocationPage = page('szolgaltatasi-teruletek')->index()->filterBy('slug', 'asztalos-' . $mainLocationSlug . '-' . $subLocationSlug)->first();
            if ($subLocationPage) {
                return $subLocationPage;
            }

            return site()->errorPage(); // Return the site's error page if the sublocation is not found
        }
    ],
    // Removes /szolgaltatasi-teruletek/ succesfully for all locations
    [
        'pattern' => '(:any)',
        'action'  => function($uid) {
            $page = page($uid);
            if(!$page) $page = page('szolgaltatasi-teruletek/' . $uid);
            if(!$page) $page = site()->errorPage();
            return site()->visit($page);
        }
    ],
    [
        'pattern' => 'szolgaltatasi-teruletek/(:any)',
        'action'  => function($uid) {
            go($uid);
        }
    ],
    [
        'pattern' => '(:any)/(:any)',
        'action'  => function($parent, $uid) {

            $page = page($parent.'/'.$uid);

            if(!$page) $page = page('szolgaltatasi-teruletek/' .$parent .'/'. $uid);
            if(!$page) $page = site()->errorPage();

            return site()->visit($page);

        }
    ],
    [
        'pattern' => 'szolgaltatasi-teruletek/(:all)',
        'action'  => function($uid) {
            go($uid);
        }
    ],
];