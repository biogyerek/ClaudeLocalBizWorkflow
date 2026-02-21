# Kirby Microsite - System Architecture

> Comprehensive technical documentation for the Kirby CMS-based microsite system

[Magyar verzió](ARCHITECTURE.md) | [Back to README](README.en.md)

## 📋 Table of Contents

- [Overview](#overview)
- [Root Structure](#root-structure)
- [Request Lifecycle](#request-lifecycle)
- [Content Folder](#content-folder)
- [Site Folder](#site-folder)
- [Data Flow Examples](#data-flow-examples)
- [Kirby Concepts](#kirby-concepts)

---

## 🎯 Overview

The Kirby Microsite is a **file-based CMS architecture** consisting of the following main components:

```
┌─────────────────────────────────────────┐
│            USER                         │
│             ↓                           │
│        URL Request                      │
└─────────────────────────────────────────┘
               ↓
┌─────────────────────────────────────────┐
│   index.php → Kirby Bootstrap           │
└─────────────────────────────────────────┘
               ↓
┌─────────────────────────────────────────┐
│   config.php → routes.php               │
└─────────────────────────────────────────┘
               ↓
     ┌─────────┴─────────┐
     ↓                   ↓
┌─────────┐       ┌──────────────┐
│ CONTENT │       │ MODELS       │
│ (files) │       │ (dynamic)    │
└─────────┘       └──────────────┘
     ↓                   ↓
     └─────────┬─────────┘
               ↓
┌─────────────────────────────────────────┐
│   CONTROLLER → Data preparation         │
└─────────────────────────────────────────┘
               ↓
┌─────────────────────────────────────────┐
│   TEMPLATE → HTML rendering             │
└─────────────────────────────────────────┘
               ↓
┌─────────────────────────────────────────┐
│   SNIPPETS → Components                 │
└─────────────────────────────────────────┘
               ↓
┌─────────────────────────────────────────┐
│   HTML + CSS + JS → BROWSER             │
└─────────────────────────────────────────┘
```

---

## 📁 Root Structure

```
microsite_base/
├── index.php              # 🚪 Entry point
├── .htaccess              # ⚙️ Apache URL rewrite rules
├── composer.json          # 📦 PHP dependencies
│
├── assets/                # 🎨 Frontend assets
│   ├── css/               # CSS files
│   ├── js/                # JavaScript files
│   └── images/            # Static images
│
├── content/                      # 📄 CONTENT - All page data
│   ├── site.en.txt               # Global settings
│   ├── locations.csv             # Location data (CSV)
│   ├── home/                     # Home page
│   ├── 1_szolgaltatasaink/       # Services (Hungarian slug)
│   ├── 2_szolgaltatasi-teruletek/  # Service areas (Hungarian slug)
│   ├── 3_magunkrol/              # About (Hungarian slug)
│   ├── 4_ingyenes-arajanlat/     # Estimate (Hungarian slug)
│   └── 5_kapcsolatfelvetel/      # Contact (Hungarian slug)
│
├── kirby/                 # 🔒 Kirby CMS core (DON'T MODIFY)
│   ├── bootstrap.php      # System initialization
│   ├── config/            # Default config
│   └── src/               # Kirby source code
│
├── media/                 # 🖼️ Generated media files
│   ├── pages/             # Page image thumbs
│   └── site/              # Site image thumbs
│
└── site/                  # 💻 CUSTOM CODE - All logic here
    ├── accounts/          # User accounts
    ├── blueprints/        # Admin panel definitions
    ├── cache/             # Cache storage
    ├── config/            # Configuration files
    ├── controllers/       # Data preparation
    ├── languages/         # Translations
    ├── models/            # Dynamic page generation
    ├── plugins/           # Installed plugins
    ├── sessions/          # Session data
    ├── snippets/          # Reusable components
    └── templates/         # HTML templates
```

### File Types and Roles

| Type | Location | Function | Example |
|------|----------|----------|---------|
| **Content** | `content/` | Page content storage | `home.en.txt` |
| **Blueprint** | `site/blueprints/` | Admin panel fields | `home.yml` |
| **Model** | `site/models/` | Dynamic page generation | `locations.php` |
| **Controller** | `site/controllers/` | Data preparation | `home.php` |
| **Template** | `site/templates/` | HTML structure | `home.php` |
| **Snippet** | `site/snippets/` | Reusable HTML | `header.php` |
| **Plugin** | `site/plugins/` | Feature extensions | `kirby-copilot/` |
| **Config** | `site/config/` | Settings | `config.php` |

---

## 🔄 Request Lifecycle

### 1. Request Initiation

```
User → https://mykirtemplate.top/asztalos-budapest
```

### 2. Apache .htaccess

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L]
```
**Explanation:** Redirects all non-existing file/folder requests to `index.php`.

### 3. index.php

```php
<?php
require 'kirby/bootstrap.php';
echo (new Kirby)->render();
```
**Task:** Loads Kirby system and renders the page.

### 4. config.php Loading

```php
// site/config/config.php
return [
  'debug'      => false,
  'url'        => require_once 'url.php',
  'routes'     => require_once 'routes.php',
  'panel'      => require_once 'panel.php',
  // ...
];
```
**Task:** Configuration settings loading.

### 5. Routing Check

```php
// site/config/routes.php
'pattern' => '(:any)',
'action'  => function($uid) {
    $page = page($uid);
    if(!$page) $page = page('szolgaltatasi-teruletek/' . $uid);
    return site()->visit($page);
}
```
**Task:** URL pattern recognition and page finding.

### 6. Model Execution (if exists)

```php
// site/models/locations.php
class LocationsPage extends Page {
    public function children(): Pages {
        // Read CSV
        $csv = csv($csvFilePath, ';');
        // Generate virtual pages
        return Pages::factory($children, $this);
    }
}
```
**Task:** Generate dynamic pages (e.g., locations from CSV).

### 7. Controller Execution

```php
// site/controllers/home.php
return function($kirby, $site, $pages, $page) {
    $services = $site->index()->filterBy('template', 'service');
    return compact('services');
};
```
**Task:** Prepare data for template.

### 8. Template Rendering

```php
// site/templates/home.php
<!DOCTYPE html>
<html>
<head>
  <?php snippet('layouts/head') ?>
</head>
<body>
  <?php snippet('layouts/header') ?>
  <?php snippet('templates/home/services') ?>
  <?php snippet('layouts/footer') ?>
</body>
</html>
```
**Task:** Assemble HTML structure from snippets.

### 9. HTML Output

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home - Company Name</title>
  <link rel="stylesheet" href="/assets/css/index.css?v=1234567890">
</head>
<body>
  <!-- Rendered content -->
</body>
</html>
```

---

## 📄 Content Folder

The `content/` folder is the **content storage**. Every page is a directory or file.

### File Naming Convention

```
[number]_[slug]/[slug].en.txt
```

**Examples:**
- `home/home.en.txt` - Home page (not numbered)
- `1_szolgaltatasaink/services.en.txt` - Services (1st in menu, Hungarian slug)
- `2_szolgaltatasi-teruletek/locations.en.txt` - Service areas (2nd in menu, Hungarian slug)

### Content File Format

```
Title: Our Services

----

Showleader: true

----

Leader: Professional wooden house construction in Budapest area

----

Text:

## Our Services

Lorem ipsum dolor sit amet...

----

Uuid: ABC123XYZ
```

**Important:**
- `----` = Field separator
- Field names = Defined in blueprint
- YAML-like format, but **NOT** YAML

### Global Settings (site.en.txt)

```
Title: Wooden House Construction

----

Companyname: Wooden House Construction Ltd

----

Companyindustry: Carpenter

----

Companyarea: Budapest

----

Companylocation: Budapest

----

Companyservices: wooden house construction, light frame house building

----

Phone: +36 1 234 5678

----

Email: info@woodenhouse.hu
```

### CSV Locations (locations.csv)

```csv
MainLocation;SubLocation
Budapest;I. district
Budapest;II. district
Budapest;III. district
Érd;
Budaörs;
```

**Important:**
- `;` delimiter character
- Header row required: `MainLocation;SubLocation`
- Empty SubLocation = main location only

---

## 💻 Site Folder

### site/models/ - Dynamic Page Generation

**locations.php** - LocationsPage Model:

```php
<?php

use Kirby\Uuid\Uuid;

class LocationsPage extends Page
{
    public function children(): Pages
    {
        // Cache check
        if ($this->children instanceof Pages) {
            return $this->children;
        }

        // Read CSV
        $csvFilePath = kirby()->root('content') . '/locations.csv';
        if (!file_exists($csvFilePath)) {
            return Pages::factory([], $this);
        }

        $csv = csv($csvFilePath, ';');
        $children = [];

        // Process locations
        foreach ($csv as $location) {
            $mainLocation = $location['MainLocation'];
            $subLocation = $location['SubLocation'] ?? null;

            // Generate slug
            $mainLocationSlug = Str::slug($mainLocation);

            // Create main location
            if (!isset($children[$mainLocationSlug])) {
                $children[$mainLocationSlug] = [
                    'slug'     => 'asztalos-' . $mainLocationSlug,
                    'template' => 'location',
                    'num'      => 0,
                    'content'  => [
                        'title' => $mainLocation,
                        'uuid'  => Uuid::generate(),
                    ],
                    'children' => []
                ];
            }

            // Add sublocation
            if ($subLocation) {
                $subLocationSlug = Str::slug($subLocation);
                $children[$mainLocationSlug]['children'][$subLocationSlug] = [
                    'slug'     => 'asztalos-' . $mainLocationSlug . '-' . $subLocationSlug,
                    'template' => 'sublocation',
                    'num'      => 0,
                    'content'  => [
                        'title'        => $subLocation,
                        'uuid'         => Uuid::generate(),
                        'mainLocation' => $mainLocation,
                        'subLocation'  => $subLocation,
                    ]
                ];
            }
        }

        return $this->children = Pages::factory(array_values($children), $this);
    }
}
```

**How it works:**
1. Read CSV
2. Generate virtual page from each row
3. Set slug, template, content fields
4. `Pages::factory()` - Create Kirby Page objects

---

## 🔑 Kirby Concepts

### Page Object

```php
// Access page
$page = page('services');
$page = $site->find('services');

// Page data
$page->title()            // Title
$page->url()              // URL
$page->slug()             // Slug (e.g. 'services')
$page->template()         // Template name
$page->intendedTemplate() // Intended template

// Navigation
$page->children()         // Direct children
$page->index()            // ALL descendants (recursive)
$page->parent()           // Parent page
$page->siblings()         // Sibling pages
$page->prev()             // Previous sibling
$page->next()             // Next sibling

// Content fields
$page->text()             // 'text' field
$page->intro()            // 'intro' field
$page->customField()      // Custom field

// Filtering
$page->children()->filterBy('template', 'service')
$page->children()->sortBy('title')
$page->children()->limit(10)
```

### Site Object

```php
// Access site
$site = site();

// Site data
$site->title()            // Site name
$site->url()              // Base URL
$site->index()            // ALL pages
$site->children()         // First level pages
$site->find('about')      // Find page by slug

// Global fields
$site->companyName()      // site.en.txt fields
$site->phone()
$site->email()
```

---

## 📚 Further Reading

- [Kirby Official Documentation](https://getkirby.com/docs)
- [Kirby Cookbook](https://getkirby.com/docs/cookbook)
- [Routing Details](ROUTING.en.md)
- [Developer Guide](DEVELOPMENT.en.md)

---

**Last updated:** 2024-12-03
**Created by:** Claude Code
