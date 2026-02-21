<?php

return function($kirby, $site, $pages, $page) {

  $form = $kirby->controller('form', compact('kirby'));

  // Use the current page as template (not parent), so individual location content is displayed
  $template = $page;

  // Keep parent reference for fallback if needed
  $parentTemplate = $page->parent();

  $perpage = $page->perpage()->int();

  $services   = $site->index()->filterBy('template', 'in', ['service']);

  $locations = $site->grandchildren()->filterBy('template', 'location');

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

  // Fetch sublocations directly under the root level
  $sublocations = $page->children()->filterBy('template', 'sublocation')->filter(function ($subpage) use ($page) {
      return $subpage->mainLocation()->value() == $page->title()->value();
  });

  // Sort and paginate sublocations
  $sublocations = $sublocations->sortBy('title')->paginate(($perpage >= 10) ? $perpage : 100);
  $pagination = $sublocations->pagination();

  $elementLocations     = $locations;
  $elementSublocations  = $sublocations;

  return A::merge($form, compact('template', 'parentTemplate', 'services', 'locations', 'locationNames', 'sublocations', 'elementLocations', 'elementSublocations', 'pagination'));
};