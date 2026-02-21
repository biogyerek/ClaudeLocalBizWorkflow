<?php

class SublocationPage extends Page
{
    public function url($options = null): string
    {
        $mainLocationSlug = Str::slug($this->mainLocation()->value());
        $subLocationSlug = Str::slug($this->subLocation()->value());
        return 'service-in-' . $subLocationSlug . '-' . $mainLocationSlug;
    }
}