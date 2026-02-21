<?php

use Kirby\Uuid\Uuid;

class LocationsPage extends Page
{
    public function children(): Pages
    {
        if ($this->children instanceof Pages) {
            return $this->children;
        }

        // Only return physical pages that start with "melegburkolo-"
        // This filters out duplicate locations without the company prefix
        return $this->children = parent::children()->filterBy('slug', '*=', 'melegburkolo-');
    }
}
