<?php

return function($kirby, $site, $pages, $page) {

    $form = $kirby->controller('form', compact('kirby'));
    $template = $page->parent()->parent();
    $services   = $site->index()->filterBy('template', 'in', ['service']);

    $perpage = $page->perpage()->int();

    $locations = $site->grandchildren()->filterBy('template', 'location');

    // Fetch all sublocations
    $allSublocations = $page->siblings()->filterBy('template', 'sublocation');

    // Filter to get related locations (siblings with the same mainLocation)
    $relatedLocations = $allSublocations->filter(function ($subpage) use ($page) {
        return $subpage->mainLocation()->value() == $page->mainLocation()->value() && $subpage->id() !== $page->id();
    });

    // Sort and paginate related locations
    $relatedLocations = $relatedLocations->sortBy('title')->paginate(($perpage >= 10) ? $perpage : 100);
    $pagination = $relatedLocations->pagination();

    $sublocations = $relatedLocations;

    $elementLocations       = $locations;
    $elementSublocations    = $relatedLocations;

    return A::merge($form, compact('relatedLocations', 'elementLocations', 'elementSublocations', 'sublocations', 'template', 'services', 'pagination'));
};
