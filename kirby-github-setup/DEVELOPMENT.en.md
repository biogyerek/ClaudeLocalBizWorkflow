# Kirby Microsite - Developer Guide

> Comprehensive developer documentation for the Kirby CMS-based microsite system

[Magyar verzió](DEVELOPMENT.md) | [Back to Architecture](ARCHITECTURE.en.md)

## 📋 Table of Contents

- [Overview](#overview)
- [Development Environment](#development-environment)
- [Creating New Pages](#creating-new-pages)
- [Template Development](#template-development)
- [Creating Controllers](#creating-controllers)
- [Using Snippets](#using-snippets)
- [Blueprint Editing](#blueprint-editing)
- [Model Development](#model-development)
- [Plugin Integration](#plugin-integration)
- [CSV Location System](#csv-location-system)
- [AI Content Generation](#ai-content-generation)
- [Asset Management](#asset-management)
- [Form Handling](#form-handling)
- [Routing Modifications](#routing-modifications)
- [Debugging and Testing](#debugging-and-testing)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

---

## 🎯 Overview

Kirby Microsite is a **file-based CMS system** with main development areas:

```
📁 content/          → Content (editable)
💻 site/templates/   → HTML templates (PHP)
🎨 site/snippets/    → Reusable components
⚙️ site/controllers/ → Data preparation
📋 site/blueprints/  → Admin panel fields
🔧 site/models/      → Dynamic page generation
🔌 site/plugins/     → Feature extensions
```

---

## 💻 Development Environment

### Required Tools

```bash
# PHP 8.2+
php -v

# Composer (dependencies)
composer --version

# Git (version control)
git --version

# SSH client (server access)
ssh -V
```

### Local Environment Setup

```bash
# 1. Clone
git clone https://github.com/your-username/kirby-github-setup.git
cd kirby-github-setup/microsite_base

# 2. Permissions
chmod -R 755 .
chmod -R 777 site/sessions site/cache media

# 3. Enable debug mode (for development)
# Edit: site/config/config.php
'debug' => true,

# 4. Open in browser
# http://localhost/microsite_base
```

### SSH Access

```bash
# Connect to server
ssh -p 65002 u388646151@82.29.186.244

# Navigate to project
cd domains/mykirtemplate.top/public_html
```

---

## 📄 Creating New Pages

### 1. Static Page (Content File)

**Example: New "About Us" page**

```bash
# 1. Create directory
mkdir content/3_about

# 2. Create content file
touch content/3_about/about.en.txt
```

**content/3_about/about.en.txt:**
```
Title: About Us

----

Showleader: true

----

Leader: Professional wooden house construction since 2010

----

Text:

## Our Story

We are a family-owned business specializing in wooden house construction...

## Our Team

- **John Doe** - Master Carpenter
- **Jane Smith** - Project Manager

----

Uuid: ABC123XYZ456
```

**Notes:**
- `3_` = 3rd position in menu
- `about` = Slug (URL: `/about`)
- `.en.txt` = English language
- `----` = Field separator

### 2. Creating Blueprint

**site/blueprints/pages/about.yml:**
```yaml
title: About Page

columns:
  - width: 2/3
    sections:
      content:
        type: fields
        fields:
          showleader:
            type: toggle
            label: Show Leader Text
            default: true

          leader:
            type: textarea
            label: Leader Text
            size: small
            when:
              showleader: true

          text:
            type: textarea
            label: Main Content
            size: large

  - width: 1/3
    sections:
      meta:
        type: fields
        fields:
          metaTitle:
            type: text
            label: SEO Title

          metaDescription:
            type: textarea
            label: SEO Description
            maxlength: 160
```

### 3. Creating Template

**site/templates/about.php:**
```php
<!DOCTYPE html>
<html lang="<?= $kirby->language()->code() ?>">
<head>
    <?php snippet('layouts/head') ?>
</head>
<body>
    <?php snippet('layouts/header') ?>

    <main class="about-page">
        <div class="container">
            <h1><?= $page->title()->html() ?></h1>

            <?php if ($page->showleader()->toBool() && $page->leader()->isNotEmpty()): ?>
                <div class="leader">
                    <?= $page->leader()->kt() ?>
                </div>
            <?php endif ?>

            <div class="content">
                <?= $page->text()->kt() ?>
            </div>
        </div>
    </main>

    <?php snippet('layouts/footer') ?>
</body>
</html>
```

### 4. Creating Controller (Optional)

**site/controllers/about.php:**
```php
<?php

return function($kirby, $site, $pages, $page) {
    // Get team members
    $teamMembers = $site->index()->filterBy('template', 'team-member');

    // Get services
    $services = $site->index()->filterBy('template', 'service')->limit(3);

    // Return
    return [
        'teamMembers' => $teamMembers,
        'services'    => $services,
    ];
};
```

**Template update (about.php):**
```php
<!-- Display team members -->
<?php if ($teamMembers->count() > 0): ?>
    <section class="team">
        <h2>Our Team</h2>
        <div class="team-grid">
            <?php foreach ($teamMembers as $member): ?>
                <div class="team-member">
                    <h3><?= $member->title() ?></h3>
                    <p><?= $member->role() ?></p>
                </div>
            <?php endforeach ?>
        </div>
    </section>
<?php endif ?>
```

---

## 🎨 Template Development

### Template Structure

```php
<!DOCTYPE html>
<html lang="<?= $kirby->language()->code() ?>">
<head>
    <?php snippet('layouts/head') ?>
</head>
<body class="<?= $page->template() ?>-page">

    <?php snippet('layouts/header') ?>

    <main>
        <!-- PAGE CONTENT -->
        <div class="container">
            <h1><?= $page->title()->html() ?></h1>

            <div class="content">
                <?= $page->text()->kt() ?>
            </div>
        </div>
    </main>

    <?php snippet('layouts/footer') ?>

</body>
</html>
```

### Using Kirby Fields

```php
// Text field
<?= $page->title()->html() ?>

// Textarea (KirbyText)
<?= $page->text()->kt() ?>

// Toggle (true/false)
<?php if ($page->showLeader()->toBool()): ?>
    <!-- Content -->
<?php endif ?>

// Number
<?= $page->year()->toInt() ?>

// Date
<?= $page->published()->toDate('d.m.Y') ?>

// URL
<a href="<?= $page->websiteUrl()->toUrl() ?>">Website</a>

// Email
<a href="mailto:<?= $page->email()->html() ?>">Contact</a>

// Image
<?php if ($image = $page->image()): ?>
    <img src="<?= $image->url() ?>" alt="<?= $image->alt() ?>">
<?php endif ?>

// Gallery
<?php foreach ($page->images() as $image): ?>
    <img src="<?= $image->url() ?>" alt="<?= $image->alt() ?>">
<?php endforeach ?>
```

### KirbyText (kt) vs HTML

```php
// ❌ WRONG - Escapes HTML
<?= $page->text()->html() ?>

// ✅ CORRECT - Renders KirbyText (markdown)
<?= $page->text()->kt() ?>
```

**KirbyText example:**
```
## Heading

This is **bold** and this is *italic*.

(link: https://example.com text: Click here)

(image: myimage.jpg)
```

---

## ⚙️ Creating Controllers

### Simple Controller

**site/controllers/services.php:**
```php
<?php

return function($kirby, $site, $pages, $page) {

    // Get services
    $services = $page->children()->filterBy('template', 'service');

    // Pagination
    $perpage = $page->perpage()->toInt() ?: 10;
    $services = $services->paginate($perpage);

    return [
        'services'   => $services,
        'pagination' => $services->pagination(),
    ];
};
```

### Complex Controller

**site/controllers/home.php:**
```php
<?php

return function($kirby, $site, $pages, $page) {

    // Services
    $services = $site->index()
        ->filterBy('template', 'service')
        ->sortBy('title')
        ->limit(6);

    // Locations (from CSV)
    $locations = page('szolgaltatasi-teruletek')
        ->children()
        ->filterBy('template', 'location')
        ->sortBy('title')
        ->limit(12);

    // Latest projects
    $projects = $site->index()
        ->filterBy('template', 'project')
        ->sortBy('date', 'desc')
        ->limit(3);

    // SEO data
    $seoTitle = $page->metaTitle()->or($page->title() . ' - ' . $site->title());
    $seoDescription = $page->metaDescription()->or($page->leader());

    // Form handling
    $form = $kirby->controller('form', compact('kirby'));

    return A::merge($form, [
        'services'       => $services,
        'locations'      => $locations,
        'projects'       => $projects,
        'seoTitle'       => $seoTitle,
        'seoDescription' => $seoDescription,
    ]);
};
```

### Controller Reuse

```php
// Reuse form controller
$form = $kirby->controller('form', compact('kirby'));
return A::merge($form, ['myData' => $data]);
```

---

## 🧩 Using Snippets

### Creating Snippet

**site/snippets/components/service-card.php:**
```php
<?php
/**
 * Service Card Snippet
 *
 * @param $service Page The service to display
 * @param $showExcerpt bool Show excerpt or not
 */

$showExcerpt = $showExcerpt ?? true;
?>

<article class="service-card">
    <?php if ($image = $service->image()): ?>
        <div class="service-card__image">
            <img src="<?= $image->crop(400, 300)->url() ?>"
                 alt="<?= $service->title()->html() ?>">
        </div>
    <?php endif ?>

    <div class="service-card__content">
        <h3><?= $service->title()->html() ?></h3>

        <?php if ($showExcerpt && $service->excerpt()->isNotEmpty()): ?>
            <p><?= $service->excerpt()->html() ?></p>
        <?php endif ?>

        <a href="<?= $service->url() ?>" class="btn">
            Learn More
        </a>
    </div>
</article>
```

### Using Snippet in Template

```php
<!-- Default usage -->
<?php snippet('components/service-card', ['service' => $service]) ?>

<!-- With parameters -->
<?php snippet('components/service-card', [
    'service'     => $service,
    'showExcerpt' => false
]) ?>

<!-- In loop -->
<?php foreach ($services as $service): ?>
    <?php snippet('components/service-card', compact('service')) ?>
<?php endforeach ?>
```

### Snippet Slots (Kirby 3.6+)

**site/snippets/components/card.php:**
```php
<div class="card">
    <div class="card__header">
        <?php slot('header') ?>
    </div>

    <div class="card__body">
        <?php slot() ?>
    </div>

    <div class="card__footer">
        <?php slot('footer') ?>
    </div>
</div>
```

**Usage:**
```php
<?php snippet('components/card', slots: true) ?>
    <?php slot('header') ?>
        <h2>Card Title</h2>
    <?php endslot() ?>

    This is the main content

    <?php slot('footer') ?>
        <button>Action</button>
    <?php endslot() ?>
<?php endsnippet() ?>
```

---

## 📋 Blueprint Editing

### Blueprint Structure

**site/blueprints/pages/service.yml:**
```yaml
title: Service

# Icon
icon: 🛠️

# Statuses
status:
  draft:
    label: Draft
    text: Service is being prepared
  unlisted:
    label: Unlisted
    text: Service is hidden
  listed:
    label: Published
    text: Service is public

# Columns
columns:
  # Left side (2/3)
  - width: 2/3
    sections:
      content:
        type: fields
        fields:

          # Text field
          title:
            type: text
            label: Service Name
            required: true

          # Textarea
          excerpt:
            type: textarea
            label: Short Description
            size: small
            maxlength: 160

          # KirbyText editor
          text:
            type: textarea
            label: Full Description
            size: large
            buttons:
              - bold
              - italic
              - link
              - ul
              - ol

          # Image upload
          cover:
            type: files
            label: Cover Image
            max: 1
            layout: cards

          # Toggle
          featured:
            type: toggle
            label: Featured Service
            default: false

  # Right side (1/3)
  - width: 1/3
    sections:

      # SEO section
      seo:
        type: fields
        label: SEO Settings
        fields:
          metaTitle:
            type: text
            label: Meta Title
            counter: true
            maxlength: 60

          metaDescription:
            type: textarea
            label: Meta Description
            counter: true
            maxlength: 160
            size: small

      # Images section
      images:
        type: files
        label: Gallery Images
        layout: cards
        template: service-image
```

### Field Types

```yaml
# Text
fieldname:
  type: text
  label: Label
  placeholder: Enter text...
  maxlength: 100
  counter: true

# Textarea
fieldname:
  type: textarea
  label: Label
  size: small  # small, medium, large
  buttons: false  # Disable KirbyText buttons

# Toggle (Yes/No)
fieldname:
  type: toggle
  label: Label
  text:
    - "No"
    - "Yes"
  default: false

# Select (Dropdown)
fieldname:
  type: select
  label: Label
  options:
    option1: Option 1
    option2: Option 2
    option3: Option 3
  default: option1

# Multiselect
fieldname:
  type: multiselect
  label: Label
  options:
    tag1: Tag 1
    tag2: Tag 2
    tag3: Tag 3

# Number
fieldname:
  type: number
  label: Label
  min: 0
  max: 100
  step: 5
  default: 10

# Date
fieldname:
  type: date
  label: Label
  format: DD.MM.YYYY
  default: now

# URL
fieldname:
  type: url
  label: Label
  placeholder: https://example.com

# Email
fieldname:
  type: email
  label: Label

# Files (images)
fieldname:
  type: files
  label: Label
  max: 10
  layout: cards  # list, cards
  template: image  # File blueprint

# Pages (page selection)
fieldname:
  type: pages
  label: Related Pages
  query: site.index
  max: 5
```

### Conditional Fields

```yaml
# Show field only if another field is true
leader:
  type: textarea
  label: Leader Text
  when:
    showleader: true

# Hide field
fieldname:
  type: text
  label: Label
  when: false
```

---

## 🔧 Model Development

### Simple Model

**site/models/project.php:**
```php
<?php

class ProjectPage extends Page
{
    // Custom field
    public function year(): Field
    {
        return $this->content()->get('date')->toDate('Y');
    }

    // Computed property
    public function isRecent(): bool
    {
        return $this->date()->toDate() > strtotime('-1 year');
    }

    // Related content
    public function relatedServices(): Pages
    {
        return $this->site()
            ->index()
            ->filterBy('template', 'service')
            ->filterBy('category', $this->category())
            ->not($this);
    }
}
```

### Dynamic Children (CSV example)

**site/models/locations.php:**
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

        // CSV file path
        $csvFilePath = kirby()->root('content') . '/locations.csv';

        if (!file_exists($csvFilePath)) {
            return Pages::factory([], $this);
        }

        // Read CSV
        $csv = csv($csvFilePath, ';');
        $children = [];

        foreach ($csv as $location) {
            $mainLocation = $location['MainLocation'];
            $subLocation = $location['SubLocation'] ?? null;

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

---

## 🔌 Plugin Integration

### Plugin Structure

```
site/plugins/my-plugin/
├── index.php              # Plugin loading
├── composer.json          # Dependencies
├── src/
│   ├── Helpers.php        # Helper functions
│   └── Components.php     # Components
└── snippets/
    └── component.php      # Snippet files
```

### Creating Plugin

**site/plugins/my-plugin/index.php:**
```php
<?php

Kirby::plugin('yourname/my-plugin', [

    // Options
    'options' => [
        'api_key' => null,
    ],

    // Page methods
    'pageMethods' => [
        'customMethod' => function () {
            return 'Custom value';
        }
    ],

    // Field methods
    'fieldMethods' => [
        'toUpperCase' => function () {
            return strtoupper($this->value);
        }
    ],

    // Snippets
    'snippets' => [
        'my-component' => __DIR__ . '/snippets/component.php'
    ],

    // Blueprints
    'blueprints' => [
        'blocks/custom-block' => __DIR__ . '/blueprints/blocks/custom-block.yml'
    ],

    // Hooks
    'hooks' => [
        'page.create:after' => function ($page) {
            // After page creation
        },
        'page.update:after' => function ($newPage, $oldPage) {
            // After page update
        }
    ],

    // Routes
    'routes' => [
        [
            'pattern' => 'my-custom-route',
            'action'  => function () {
                return 'Custom route response';
            }
        ]
    ]
]);
```

### Using Kirby Copilot

**AI content generation:**

```php
// In blueprint (site/blueprints/site.yml)
fields:
  mission:
    type: textarea
    label: Mission Statement
    buttons:
      - aiwriter
    aiwriter:
      prompt: "Write a professional mission statement for a {{companyindustry}} company in {{companylocation}}"
      model: gpt-4
      temperature: 0.7
```

---

## 📊 CSV Location System

### CSV File Format

**content/locations.csv:**
```csv
MainLocation;SubLocation
Budapest;I. district
Budapest;II. district
Budapest;III. district
Érd;
Budaörs;
Törökbálint;
```

**Important:**
- `;` delimiter character
- Header row required: `MainLocation;SubLocation`
- Empty `SubLocation` = main location only

### Editing Locations.csv

```bash
# 1. Open CSV
nano content/locations.csv

# 2. Add new row
Budapest;XI. district

# 3. Save (Ctrl+O, Enter, Ctrl+X)

# 4. Clear cache
rm -rf site/cache/* site/sessions/*

# 5. Check
# https://mykirtemplate.top/szolgaltatasi-teruletek
```

### Generating CSV from PHP

```php
<?php

// Location array
$locations = [
    ['Budapest', 'I. district'],
    ['Budapest', 'II. district'],
    ['Érd', ''],
];

// Create CSV file
$csvFilePath = kirby()->root('content') . '/locations.csv';
$file = fopen($csvFilePath, 'w');

// Header
fputcsv($file, ['MainLocation', 'SubLocation'], ';');

// Data
foreach ($locations as $location) {
    fputcsv($file, $location, ';');
}

fclose($file);
```

---

## 🤖 AI Content Generation

### Kirby Copilot Configuration

**site/config/config.php:**
```php
return [
    'johannschopplich.copilot' => [
        'openai' => [
            'model'       => 'gpt-4',
            'temperature' => 0.7,
            'apiKey'      => env('OPENAI_API_KEY'),
        ],
    ],
];
```

**.env file:**
```
OPENAI_API_KEY=sk-xxxxxxxxxxxxxxxxxx
```

### Blueprint AI Writer

```yaml
fields:
  text:
    type: textarea
    label: Content
    buttons:
      - aiwriter
    aiwriter:
      prompt: |
        Write professional content about {{title}} for a {{companyindustry}} company.

        Context:
        - Company: {{site.companyname}}
        - Location: {{site.companylocation}}
        - Services: {{site.companyservices}}

        Requirements:
        - Professional tone
        - SEO optimized
        - 300-500 words
      model: gpt-4
      temperature: 0.7
      max_tokens: 1000
```

### Text Replacements

**site/config/config.php:**
```php
'textReplacements' => [
    'companyName'     => fn() => site()->companyName()->value(),
    'companyIndustry' => fn() => site()->companyIndustry()->value(),
    'companyLocation' => fn() => site()->companyLocation()->value(),
    'companyServices' => fn() => site()->companyServices()->value(),
    'phone'           => fn() => site()->phone()->value(),
    'email'           => fn() => site()->email()->value(),
],
```

**Using in AI prompt:**
```
Write content for {{companyName}}, a {{companyIndustry}} company in {{companyLocation}}.
```

---

## 🎨 Asset Management

### Asset Fingerprinting

**site/config/config.php:**
```php
'assetFingerprinting' => [
    'enabled' => true,
    'assets'  => [
        '/assets/css/index.css',
        '/assets/js/index.js',
    ],
],
```

**Snippet usage (site/snippets/layouts/head.php):**
```php
<link rel="stylesheet" href="<?= assetUrl('/assets/css/index.css') ?>">
<script src="<?= assetUrl('/assets/js/index.js') ?>" defer></script>
```

**Generated HTML:**
```html
<link rel="stylesheet" href="/assets/css/index.css?v=1234567890">
<script src="/assets/js/index.js?v=1234567890" defer></script>
```

### WebP Images

**Image conversion:**
```php
<?php
// In template
if ($image = $page->image()) {
    // Create WebP version
    $webp = $image->toWebp();
    ?>
    <picture>
        <source srcset="<?= $webp->url() ?>" type="image/webp">
        <img src="<?= $image->url() ?>" alt="<?= $image->alt() ?>">
    </picture>
    <?php
}
?>
```

### Image Optimization

```php
<?php
// Different sizes
$image->crop(400, 300)->url();      // Crop
$image->resize(800)->url();          // Resize
$image->thumb([
    'width'  => 400,
    'height' => 300,
    'crop'   => true,
    'blur'   => false,
])->url();
?>
```

---

## 📧 Form Handling

### Kirby Uniform

**Blueprint (site/blueprints/pages/contact.yml):**
```yaml
fields:
  uniformRecipient:
    type: email
    label: Form Recipient Email
    default: info@example.com
```

**Controller (site/controllers/contact.php):**
```php
<?php

use Uniform\Form;

return function($kirby, $site, $pages, $page) {

    $form = new Form([
        'name' => [
            'rules'   => ['required'],
            'message' => 'Please enter your name',
        ],
        'email' => [
            'rules'   => ['required', 'email'],
            'message' => 'Please enter a valid email',
        ],
        'message' => [
            'rules'   => ['required', 'minLength' => 10],
            'message' => 'Please enter a message (min. 10 characters)',
        ],
    ]);

    if ($kirby->request()->is('POST')) {
        $form->emailAction([
            'to'      => $page->uniformRecipient()->value(),
            'from'    => 'noreply@example.com',
            'subject' => 'New Contact Form Submission',
        ]);
    }

    return compact('form');
};
```

**Template (site/templates/contact.php):**
```php
<?php if ($form->success()): ?>
    <div class="alert alert-success">
        Thank you! Your message has been sent.
    </div>
<?php else: ?>

    <form method="POST">
        <?php if ($form->hasErrors()): ?>
            <div class="alert alert-error">
                Please correct the errors below.
            </div>
        <?php endif ?>

        <div class="form-group">
            <label for="name">Name *</label>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?= $form->old('name') ?>"
                   required>
            <?php if ($error = $form->error('name')): ?>
                <span class="error"><?= $error ?></span>
            <?php endif ?>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?= $form->old('email') ?>"
                   required>
            <?php if ($error = $form->error('email')): ?>
                <span class="error"><?= $error ?></span>
            <?php endif ?>
        </div>

        <div class="form-group">
            <label for="message">Message *</label>
            <textarea id="message"
                      name="message"
                      rows="5"
                      required><?= $form->old('message') ?></textarea>
            <?php if ($error = $form->error('message')): ?>
                <span class="error"><?= $error ?></span>
            <?php endif ?>
        </div>

        <button type="submit">Send Message</button>
    </form>

<?php endif ?>
```

---

## 🗺️ Routing Modifications

### Adding New Route

**site/config/routes.php:**
```php
<?php

return [

    // ... existing routes ...

    // New route: Blog categories
    [
        'pattern' => 'blog/(:any)',
        'action'  => function($category) {
            $page = page('blog');
            $posts = $page->children()
                ->filterBy('category', $category)
                ->sortBy('date', 'desc');

            return [
                'page'  => $page,
                'posts' => $posts,
            ];
        }
    ],

    // API endpoint
    [
        'pattern' => 'api/locations',
        'method'  => 'GET',
        'action'  => function() {
            $locations = page('szolgaltatasi-teruletek')->children();
            return [
                'status' => 'success',
                'data'   => $locations->toArray(),
            ];
        }
    ],

];
```

### Route Priority

**IMPORTANT:** Route order matters!

```php
// ✅ GOOD - specific route first
[
    'pattern' => 'asztalos-(:any)-(:all)',  // Specific
    'action' => ...
],
[
    'pattern' => '(:any)',  // General
    'action' => ...
],

// ❌ BAD - general route first
[
    'pattern' => '(:any)',  // This catches everything!
    'action' => ...
],
[
    'pattern' => 'asztalos-(:any)-(:all)',  // Never runs!
    'action' => ...
],
```

---

## 🐛 Debugging and Testing

### Debug Mode

**site/config/config.php:**
```php
return [
    'debug' => true,  // For development
];
```

**In production:**
```php
return [
    'debug' => false,  // In production!
];
```

### Debug Tools

```php
// Dump (display and stop)
dump($variable);
exit;

// Dump and Die (shorter)
dd($variable);

// Kirby Debugger
kirby()->dump($page);

// Log to file
kirby()->log()->error('Error message');
kirby()->log()->info('Info message');
```

### Whoops Error Messages

In debug mode, Whoops library provides detailed error messages:

```
Whoops! There was an error.

InvalidArgumentException
Undefined array key "MainLocation"

Stack trace:
  1. site/models/locations.php:25
  2. kirby/src/Cms/Page.php:123
  ...
```

### Clearing Cache

```bash
# Via SSH
rm -rf site/cache/* site/sessions/*

# From PHP
kirby()->cache()->flush();
```

### Testing Checklist

**After implementing new feature:**

- [ ] Works locally
- [ ] Cache cleared
- [ ] Browser cache cleared (Ctrl+Shift+R)
- [ ] Works in different browsers
- [ ] Works on mobile
- [ ] SEO meta tags correct
- [ ] No PHP errors (debug mode)
- [ ] No JavaScript errors (Console)
- [ ] No CSS breaks
- [ ] Images optimized
- [ ] Deployment tested
- [ ] Works in production

---

## ✅ Best Practices

### 1. File Structure

```
✅ GOOD - Logical naming
site/templates/home.php
site/controllers/home.php
site/blueprints/pages/home.yml

❌ BAD - Inconsistent
site/templates/homepage.php
site/controllers/main.php
site/blueprints/pages/index.yml
```

### 2. Template Code

```php
// ✅ GOOD - Use Kirby methods
<?= $page->title()->html() ?>
<?= $page->text()->kt() ?>

// ❌ BAD - Raw PHP
<?= htmlspecialchars($page->title()->value()) ?>
<?= nl2br($page->text()->value()) ?>
```

### 3. Controller vs Template Logic

```php
// ✅ GOOD - Controller
// site/controllers/home.php
return [
    'featuredServices' => $site->index()
        ->filterBy('template', 'service')
        ->filterBy('featured', true)
        ->limit(3)
];

// ❌ BAD - In template
// site/templates/home.php
<?php
$featuredServices = $site->index()->filterBy('template', 'service')->filterBy('featured', true)->limit(3);
?>
```

### 4. Security

```php
// ✅ GOOD - Escape output
<?= $page->title()->html() ?>

// ❌ BAD - Raw output
<?= $page->title() ?>

// ✅ GOOD - Email validation
if (v::email($email)) {
    // Send email
}

// ❌ BAD - No validation
mail($email, $subject, $message);
```

### 5. Performance

```php
// ✅ GOOD - Use cache
$services = $site->index()->filterBy('template', 'service');

// ❌ BAD - Repeated query
<?php foreach ($site->index()->filterBy('template', 'service') as $service): ?>
<?php endforeach ?>
```

---

## 🔧 Troubleshooting

### Problem 1: "Page not found"

**Symptom:** 404 error
**Causes:**
1. Content file missing
2. Route incorrect
3. Blueprint missing

**Solution:**
```bash
# 1. Check content file
ls -la content/

# 2. Check routes
cat site/config/routes.php

# 3. Clear cache
rm -rf site/cache/* site/sessions/*
```

### Problem 2: "Undefined array key"

**Symptom:** PHP error
**Cause:** CSV or content field missing

**Solution:**
```php
// ❌ WRONG
$location = $csv['MainLocation'];

// ✅ CORRECT
$location = $csv['MainLocation'] ?? 'Default';

// ✅ CORRECT - Kirby field
if ($page->fieldName()->isNotEmpty()) {
    $value = $page->fieldName()->value();
}
```

### Problem 3: "Locations not showing"

**Symptom:** Empty list
**Causes:**
1. CSV format wrong
2. Model error
3. Physical folders conflicting

**Solution:**
```bash
# 1. Check CSV
head -5 content/locations.csv

# 2. Delete physical folders
rm -rf content/2_szolgaltatasi-teruletek/0_asztalos-*

# 3. Clear cache
rm -rf site/cache/* site/sessions/*
```

### Problem 4: "AI generation not working"

**Symptom:** Missing content
**Causes:**
1. API key missing
2. Text replacements not configured
3. OpenAI API error

**Solution:**
```bash
# 1. Check API key
cat .env | grep OPENAI_API_KEY

# 2. Check config
cat site/config/config.php | grep johannschopplich

# 3. Check logs
tail -f site/logs/error.log
```

### Problem 5: "Changes not visible"

**Symptom:** Old content
**Cause:** Cache

**Solution:**
```bash
# 1. Clear Kirby cache
rm -rf site/cache/* site/sessions/*

# 2. Clear browser cache (Hard refresh)
# Chrome/Firefox: Ctrl+Shift+R
# Safari: Cmd+Option+R

# 3. Clear opcode cache (if any)
php -r "opcache_reset();"
```

---

## 📚 Further Reading

- [Kirby Official Documentation](https://getkirby.com/docs)
- [Kirby Cookbook](https://getkirby.com/docs/cookbook)
- [Kirby Plugins](https://getkirby.com/plugins)
- [Architecture](ARCHITECTURE.en.md)
- [Routing Details](ROUTING.en.md)

---

**Version:** 1.0.0
**Last updated:** 2024-12-03
**Created by:** Claude Code
