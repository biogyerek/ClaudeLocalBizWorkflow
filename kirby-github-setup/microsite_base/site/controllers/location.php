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

  return A::merge($form, compact('template', 'parentTemplate', 'services', 'locations', 'sublocations', 'elementLocations', 'elementSublocations', 'pagination'));
};