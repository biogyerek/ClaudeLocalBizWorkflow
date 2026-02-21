<?php

use Kirby\Uuid\Uuid;

class LocationsPage extends Page
{
    public function children(): Pages
    {
        if ($this->children instanceof Pages) {
            return $this->children;
        }

        $csvFilePath = kirby()->root('content') . '/locations.csv';

        if (!file_exists($csvFilePath)) {
            return Pages::factory([], $this);
        }

        $csv = csv($csvFilePath, ';');
        $virtualChildren = [];

        // Get existing physical pages first
        $physicalChildren = parent::children();

        foreach ($csv as $location) {
            $mainLocation = $location['MainLocation'];
            $subLocation = $location['SubLocation'] ?? null;

            $mainLocationSlug = Str::slug($mainLocation);
            $subLocationSlug = $subLocation ? Str::slug($subLocation) : null;
            $slug = 'asztalos-' . $mainLocationSlug;

            if (!isset($virtualChildren[$mainLocationSlug])) {
                // Check if physical page exists with this slug
                $physicalPage = $physicalChildren->findBy('slug', $slug);

                if (!$physicalPage) {
                    // Only generate virtual page if no physical page exists
                    $virtualChildren[$mainLocationSlug] = [
                        'slug'     => $slug,
                        'template' => 'location',
                        'model'    => 'location',
                        'num'      => 0,
                        'content'  => [
                            'title' => $mainLocation,
                            'uuid'  => Uuid::generate(),
                        ],
                        'children' => []
                    ];
                }
                // If physical page exists, skip it - will be included via parent::children()
            }

            if ($subLocation) {
                // Sublocations (kerületek) are always virtual
                // Check if main location is physical or virtual
                $physicalPage = $physicalChildren->findBy('slug', $slug);

                if ($physicalPage) {
                    // Main location is physical - need to handle sublocations separately
                    // TODO: This needs a different approach - physical pages can't have virtual children
                    // For now, sublocations work only with virtual main locations
                } else {
                    // Main location is virtual - add sublocation as child
                    if (isset($virtualChildren[$mainLocationSlug])) {
                        $virtualChildren[$mainLocationSlug]['children'][$subLocationSlug] = [
                            'slug'     => 'asztalos-' . $mainLocationSlug . '-' . $subLocationSlug,
                            'template' => 'sublocation',
                            'model'    => 'sublocation',
                            'num'      => 0,
                            'content'  => [
                                'title'       => $subLocation,
                                'uuid'        => Uuid::generate(),
                                'mainLocation' => $mainLocation,
                                'subLocation' => $subLocation,
                            ]
                        ];
                    }
                }
            }
        }

        // Merge physical pages with virtual pages
        return $this->children = parent::children()->merge(Pages::factory(array_values($virtualChildren), $this));
    }
}
