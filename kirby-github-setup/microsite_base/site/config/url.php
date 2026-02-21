<?php

/**
 * The filter makes sure no url is set when SITE_URL is empty. In this case kirby
 * should fall back on auto detection.
 */
return array_filter(
    [
        getenv('SITE_URL'),
    ]
);
