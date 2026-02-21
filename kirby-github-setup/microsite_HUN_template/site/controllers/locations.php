<?php

return function($kirby, $site, $pages, $page) {

  $form = $kirby->controller('form' , compact('kirby'));

  $perpage  = $page->perpage()->int();

  $locations = $page->children()->filterBy('template', 'location');
  $services   = $site->index()->filterBy('template', 'in', ['service']);

  // Read locations.csv and create a mapping of slug to location name
  $csvPath = $kirby->root('content') . '/locations.csv';
  $locationNames = [];

  if (file_exists($csvPath)) {
    $csvData = array_map(function($line) {
      return str_getcsv($line, ';');
    }, file($csvPath, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES));

    // Skip header row
    array_shift($csvData);

    // Create mapping
    foreach ($csvData as $row) {
      if (!empty($row[0])) {
        $locationName = trim($row[0]);
        // Create slug from location name for matching
        $slug = Str::slug($locationName);
        $locationNames[$slug] = $locationName;
      }
    }
  }

  $locations = $locations
                   ->sortBy('title')
                   ->paginate(($perpage >= 10)? $perpage : 100);

  $pagination = $locations->pagination();

  $elementLocations     = $locations;
  $elementSublocations  = $locations;
  $sublocations         = $locations;

  return A::merge($form , compact('locations', 'sublocations', 'elementLocations', 'elementSublocations', 'services', 'locationNames'));

};