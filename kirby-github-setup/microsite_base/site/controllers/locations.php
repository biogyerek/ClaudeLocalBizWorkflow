<?php

return function($kirby, $site, $pages, $page) {

  $form = $kirby->controller('form' , compact('kirby'));

  $perpage  = $page->perpage()->int();

  $locations = $page->children()->filterBy('template', 'location');
  $services   = $site->index()->filterBy('template', 'in', ['service']);

  $locations = $locations
                   ->sortBy('title')
                   ->paginate(($perpage >= 10)? $perpage : 100);

  $pagination = $locations->pagination();

  $elementLocations     = $locations;
  $elementSublocations  = $locations;
  $sublocations         = $locations;

  return A::merge($form , compact('locations', 'sublocations', 'elementLocations', 'elementSublocations', 'services'));

};