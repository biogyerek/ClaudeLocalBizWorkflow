<?php

Kirby::plugin('shawnbuckles/siteMethods', [
  'siteMethods' => [
      'indexArray' => function () {

          // Get all pages from the site

          // Bootstrapped to exclude servicelocation children
          // want to define excluded in config file like sitemap ingore
          $excluded = page('servicelocaties')->index();

          $pages = $this->pages()->index()->not($excluded);

          // Filter and map the pages to their URLs
          $urls = $pages->filter(function($page) {
              return $page->listed();
          })->map(function($page) {
              return $page->url();
          });

          // Join the URLs with a pipe '|' character
          return implode('|', $urls->data());
      }
  ]
]);