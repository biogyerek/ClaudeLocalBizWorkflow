# Kirby Microsite - Fejlesztői Útmutató

> Átfogó fejlesztői dokumentáció a Kirby CMS alapú mikrooldal rendszerhez

[English version](DEVELOPMENT.en.md) | [Vissza az Architektúrához](ARCHITECTURE.md)

## 📋 Tartalomjegyzék

- [Áttekintés](#áttekintés)
- [Fejlesztői Környezet](#fejlesztői-környezet)
- [Új Oldal Létrehozása](#új-oldal-létrehozása)
- [Template Fejlesztés](#template-fejlesztés)
- [Controller Létrehozása](#controller-létrehozása)
- [Snippet Használata](#snippet-használata)
- [Blueprint Szerkesztése](#blueprint-szerkesztése)
- [Model Fejlesztés](#model-fejlesztés)
- [Plugin Integráció](#plugin-integráció)
- [CSV Helyszín Rendszer](#csv-helyszín-rendszer)
- [AI Tartalom Generálás](#ai-tartalom-generálás)
- [Asset Kezelés](#asset-kezelés)
- [Űrlap Kezelés](#űrlap-kezelés)
- [Routing Módosítás](#routing-módosítás)
- [Debug és Tesztelés](#debug-és-tesztelés)
- [Best Practices](#best-practices)
- [Hibaelhárítás](#hibaelhárítás)

---

## 🎯 Áttekintés

A Kirby Microsite egy **fájl-alapú CMS rendszer**, ahol a fejlesztés fő területei:

```
📁 content/          → Tartalom (szerkeszthető)
💻 site/templates/   → HTML sablonok (PHP)
🎨 site/snippets/    → Újrafelhasználható komponensek
⚙️ site/controllers/ → Adat előkészítés
📋 site/blueprints/  → Admin panel mezők
🔧 site/models/      → Dinamikus oldal generálás
🔌 site/plugins/     → Funkció bővítmények
```

---

## 💻 Fejlesztői Környezet

### Szükséges Eszközök

```bash
# PHP 8.2+
php -v

# Composer (függőségek)
composer --version

# Git (verziókezelés)
git --version

# SSH kliens (szerver hozzáférés)
ssh -V
```

### Lokális Környezet Beállítás

```bash
# 1. Klónozás
git clone https://github.com/your-username/kirby-github-setup.git
cd kirby-github-setup/microsite_base

# 2. Jogosultságok
chmod -R 755 .
chmod -R 777 site/sessions site/cache media

# 3. Debug mód bekapcsolás (fejlesztéshez)
# Szerkesztd: site/config/config.php
'debug' => true,

# 4. Böngészőben megnyitás
# http://localhost/microsite_base
```

### SSH Hozzáférés

```bash
# Csatlakozás a szerverhez
ssh -p 65002 u388646151@82.29.186.244

# Navigálás a projekthez
cd domains/mykirtemplate.top/public_html
```

---

## 📄 Új Oldal Létrehozása

### 1. Statikus Oldal (Content File)

**Példa: Új "Rólunk" oldal**

```bash
# 1. Könyvtár létrehozása
mkdir content/3_about

# 2. Content fájl
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

**Megjegyzés:**
- `3_` = Menüben 3. helyen
- `about` = Slug (URL: `/about`)
- `.en.txt` = Angol nyelv
- `----` = Mező elválasztó

### 2. Blueprint Létrehozása

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

### 3. Template Létrehozása

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

### 4. Controller Létrehozása (Opcionális)

**site/controllers/about.php:**
```php
<?php

return function($kirby, $site, $pages, $page) {
    // Team tagok lekérése
    $teamMembers = $site->index()->filterBy('template', 'team-member');

    // Szolgáltatások lekérése
    $services = $site->index()->filterBy('template', 'service')->limit(3);

    // Visszatérés
    return [
        'teamMembers' => $teamMembers,
        'services'    => $services,
    ];
};
```

**Template frissítés (about.php):**
```php
<!-- Team members megjelenítése -->
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

## 🎨 Template Fejlesztés

### Template Szerkezet

```php
<!DOCTYPE html>
<html lang="<?= $kirby->language()->code() ?>">
<head>
    <?php snippet('layouts/head') ?>
</head>
<body class="<?= $page->template() ?>-page">

    <?php snippet('layouts/header') ?>

    <main>
        <!-- OLDAL TARTALOM -->
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

### Kirby Mezők Használata

```php
// Szöveg mező
<?= $page->title()->html() ?>

// Textarea (KirbyText)
<?= $page->text()->kt() ?>

// Toggle (true/false)
<?php if ($page->showLeader()->toBool()): ?>
    <!-- Tartalom -->
<?php endif ?>

// Number
<?= $page->year()->toInt() ?>

// Date
<?= $page->published()->toDate('d.m.Y') ?>

// URL
<a href="<?= $page->websiteUrl()->toUrl() ?>">Website</a>

// Email
<a href="mailto:<?= $page->email()->html() ?>">Contact</a>

// Kép
<?php if ($image = $page->image()): ?>
    <img src="<?= $image->url() ?>" alt="<?= $image->alt() ?>">
<?php endif ?>

// Galéria
<?php foreach ($page->images() as $image): ?>
    <img src="<?= $image->url() ?>" alt="<?= $image->alt() ?>">
<?php endforeach ?>
```

### KirbyText (kt) vs HTML

```php
// ❌ ROSSZ - Escape-eli a HTML-t
<?= $page->text()->html() ?>

// ✅ JÓ - Rendereli a KirbyText-et (markdown)
<?= $page->text()->kt() ?>
```

**KirbyText példa:**
```
## Heading

This is **bold** and this is *italic*.

(link: https://example.com text: Click here)

(image: myimage.jpg)
```

---

## ⚙️ Controller Létrehozása

### Egyszerű Controller

**site/controllers/services.php:**
```php
<?php

return function($kirby, $site, $pages, $page) {

    // Szolgáltatások lekérése
    $services = $page->children()->filterBy('template', 'service');

    // Lapozás
    $perpage = $page->perpage()->toInt() ?: 10;
    $services = $services->paginate($perpage);

    return [
        'services'   => $services,
        'pagination' => $services->pagination(),
    ];
};
```

### Összetett Controller

**site/controllers/home.php:**
```php
<?php

return function($kirby, $site, $pages, $page) {

    // Szolgáltatások
    $services = $site->index()
        ->filterBy('template', 'service')
        ->sortBy('title')
        ->limit(6);

    // Helyszínek (CSV-ből)
    $locations = page('szolgaltatasi-teruletek')
        ->children()
        ->filterBy('template', 'location')
        ->sortBy('title')
        ->limit(12);

    // Legújabb projektek
    $projects = $site->index()
        ->filterBy('template', 'project')
        ->sortBy('date', 'desc')
        ->limit(3);

    // SEO adatok
    $seoTitle = $page->metaTitle()->or($page->title() . ' - ' . $site->title());
    $seoDescription = $page->metaDescription()->or($page->leader());

    // Űrlap kezelés
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

### Controller Újrafelhasználás

```php
// Űrlap controller újrafelhasználása
$form = $kirby->controller('form', compact('kirby'));
return A::merge($form, ['myData' => $data]);
```

---

## 🧩 Snippet Használata

### Snippet Létrehozás

**site/snippets/components/service-card.php:**
```php
<?php
/**
 * Service Card Snippet
 *
 * @param $service Page Az megjelenítendő szolgáltatás
 * @param $showExcerpt bool Mutassa-e a kivonatot
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

### Snippet Használat Template-ben

```php
<!-- Alapértelmezett használat -->
<?php snippet('components/service-card', ['service' => $service]) ?>

<!-- Paraméterekkel -->
<?php snippet('components/service-card', [
    'service'     => $service,
    'showExcerpt' => false
]) ?>

<!-- Ciklusban -->
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

**Használat:**
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

## 📋 Blueprint Szerkesztése

### Blueprint Szerkezet

**site/blueprints/pages/service.yml:**
```yaml
title: Service

# Ikon
icon: 🛠️

# Státuszok
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

# Oszlopok
columns:
  # Bal oldal (2/3)
  - width: 2/3
    sections:
      content:
        type: fields
        fields:

          # Szöveg mező
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

          # Kép feltöltés
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

  # Jobb oldal (1/3)
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

      # Képek section
      images:
        type: files
        label: Gallery Images
        layout: cards
        template: service-image
```

### Mező Típusok

```yaml
# Szöveg
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
  buttons: false  # KirbyText gombok letiltása

# Toggle (Igen/Nem)
fieldname:
  type: toggle
  label: Label
  text:
    - "No"
    - "Yes"
  default: false

# Select (Legördülő)
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

# Fájlok (képek)
fieldname:
  type: files
  label: Label
  max: 10
  layout: cards  # list, cards
  template: image  # Fájl blueprint

# Pages (oldal kiválasztás)
fieldname:
  type: pages
  label: Related Pages
  query: site.index
  max: 5
```

### Feltételes Mezők

```yaml
# Mező megjelenítése csak akkor, ha más mező true
leader:
  type: textarea
  label: Leader Text
  when:
    showleader: true

# Mező elrejtése
fieldname:
  type: text
  label: Label
  when: false
```

---

## 🔧 Model Fejlesztés

### Egyszerű Model

**site/models/project.php:**
```php
<?php

class ProjectPage extends Page
{
    // Custom mező
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

### Dinamikus Children (CSV példa)

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

        // CSV fájl elérési út
        $csvFilePath = kirby()->root('content') . '/locations.csv';

        if (!file_exists($csvFilePath)) {
            return Pages::factory([], $this);
        }

        // CSV beolvasás
        $csv = csv($csvFilePath, ';');
        $children = [];

        foreach ($csv as $location) {
            $mainLocation = $location['MainLocation'];
            $subLocation = $location['SubLocation'] ?? null;

            $mainLocationSlug = Str::slug($mainLocation);

            // Fő helyszín létrehozása
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

            // Alhelyszín hozzáadása
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

## 🔌 Plugin Integráció

### Plugin Struktúra

```
site/plugins/my-plugin/
├── index.php              # Plugin betöltés
├── composer.json          # Függőségek
├── src/
│   ├── Helpers.php        # Helper függvények
│   └── Components.php     # Komponensek
└── snippets/
    └── component.php      # Snippet fájlok
```

### Plugin Létrehozás

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
            // Új oldal létrehozása után
        },
        'page.update:after' => function ($newPage, $oldPage) {
            // Oldal frissítése után
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

### Kirby Copilot Használat

**AI tartalom generálás:**

```php
// Blueprint-ben (site/blueprints/site.yml)
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

## 📊 CSV Helyszín Rendszer

### CSV Fájl Formátum

**content/locations.csv:**
```csv
MainLocation;SubLocation
Budapest;I. kerület
Budapest;II. kerület
Budapest;III. kerület
Érd;
Budaörs;
Törökbálint;
```

**Fontos:**
- `;` elválasztó karakter
- Fejléc sor kötelező: `MainLocation;SubLocation`
- Üres `SubLocation` = csak fő helyszín

### Locations.csv Szerkesztés

```bash
# 1. CSV megnyitása
nano content/locations.csv

# 2. Új sor hozzáadása
Budapest;XI. kerület

# 3. Mentés (Ctrl+O, Enter, Ctrl+X)

# 4. Cache törlés
rm -rf site/cache/* site/sessions/*

# 5. Ellenőrzés
# https://mykirtemplate.top/szolgaltatasi-teruletek
```

### CSV Generálás PHP-ból

```php
<?php

// Helyszínek tömbje
$locations = [
    ['Budapest', 'I. kerület'],
    ['Budapest', 'II. kerület'],
    ['Érd', ''],
];

// CSV fájl létrehozása
$csvFilePath = kirby()->root('content') . '/locations.csv';
$file = fopen($csvFilePath, 'w');

// Fejléc
fputcsv($file, ['MainLocation', 'SubLocation'], ';');

// Adatok
foreach ($locations as $location) {
    fputcsv($file, $location, ';');
}

fclose($file);
```

---

## 🤖 AI Tartalom Generálás

### Kirby Copilot Konfiguráció

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

**.env fájl:**
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

**Használat AI prompt-ban:**
```
Write content for {{companyName}}, a {{companyIndustry}} company in {{companyLocation}}.
```

---

## 🎨 Asset Kezelés

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

**Snippet használat (site/snippets/layouts/head.php):**
```php
<link rel="stylesheet" href="<?= assetUrl('/assets/css/index.css') ?>">
<script src="<?= assetUrl('/assets/js/index.js') ?>" defer></script>
```

**Generált HTML:**
```html
<link rel="stylesheet" href="/assets/css/index.css?v=1234567890">
<script src="/assets/js/index.js?v=1234567890" defer></script>
```

### WebP Képek

**Kép konverzió:**
```php
<?php
// Template-ben
if ($image = $page->image()) {
    // WebP verzió létrehozása
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

### Képek Optimalizálás

```php
<?php
// Különböző méretek
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

## 📧 Űrlap Kezelés

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

## 🗺️ Routing Módosítás

### Új Route Hozzáadás

**site/config/routes.php:**
```php
<?php

return [

    // ... meglévő route-ok ...

    // Új route: Blog kategóriák
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

### Route Prioritás

**FONTOS:** A route-ok sorrendje számít!

```php
// ✅ JÓ - specifikus route előbb
[
    'pattern' => 'asztalos-(:any)-(:all)',  // Specifikus
    'action' => ...
],
[
    'pattern' => '(:any)',  // Általános
    'action' => ...
],

// ❌ ROSSZ - általános route előbb
[
    'pattern' => '(:any)',  // Ez elkapja az összeset!
    'action' => ...
],
[
    'pattern' => 'asztalos-(:any)-(:all)',  // Soha nem fut le!
    'action' => ...
],
```

---

## 🐛 Debug és Tesztelés

### Debug Mód

**site/config/config.php:**
```php
return [
    'debug' => true,  // Fejlesztéshez
];
```

**Produkción:**
```php
return [
    'debug' => false,  // Éles környezetben!
];
```

### Debug Eszközök

```php
// Dump (megjelenít és megáll)
dump($variable);
exit;

// Dump and Die (rövidebb)
dd($variable);

// Kirby Debugger
kirby()->dump($page);

// Log fájlba
kirby()->log()->error('Error message');
kirby()->log()->info('Info message');
```

### Whoops Hibaüzenetek

Debug módban a Whoops könyvtár részletes hibaüzeneteket ad:

```
Whoops! There was an error.

InvalidArgumentException
Undefined array key "MainLocation"

Stack trace:
  1. site/models/locations.php:25
  2. kirby/src/Cms/Page.php:123
  ...
```

### Cache Törlés

```bash
# SSH-n keresztül
rm -rf site/cache/* site/sessions/*

# PHP-ból
kirby()->cache()->flush();
```

### Tesztelési Checklist

**Új feature implementálás után:**

- [ ] Működik lokálisan
- [ ] Cache törölve
- [ ] Böngésző cache törölve (Ctrl+Shift+R)
- [ ] Működik különböző böngészőkben
- [ ] Működik mobilon
- [ ] SEO meta tagek helyesek
- [ ] Nincs PHP hiba (debug mód)
- [ ] Nincs JavaScript hiba (Console)
- [ ] Nincs CSS törés
- [ ] Képek optimalizálva
- [ ] Deployment tesztelve
- [ ] Működik éles környezetben

---

## ✅ Best Practices

### 1. Fájl Struktúra

```
✅ JÓ - Logikus elnevezés
site/templates/home.php
site/controllers/home.php
site/blueprints/pages/home.yml

❌ ROSSZ - Következetlen
site/templates/homepage.php
site/controllers/main.php
site/blueprints/pages/index.yml
```

### 2. Template Kód

```php
// ✅ JÓ - Kirby methods használata
<?= $page->title()->html() ?>
<?= $page->text()->kt() ?>

// ❌ ROSSZ - Nyers PHP
<?= htmlspecialchars($page->title()->value()) ?>
<?= nl2br($page->text()->value()) ?>
```

### 3. Controller vs Template Logic

```php
// ✅ JÓ - Controller
// site/controllers/home.php
return [
    'featuredServices' => $site->index()
        ->filterBy('template', 'service')
        ->filterBy('featured', true)
        ->limit(3)
];

// ❌ ROSSZ - Template-ben
// site/templates/home.php
<?php
$featuredServices = $site->index()->filterBy('template', 'service')->filterBy('featured', true)->limit(3);
?>
```

### 4. Security

```php
// ✅ JÓ - Escape output
<?= $page->title()->html() ?>

// ❌ ROSSZ - Nyers output
<?= $page->title() ?>

// ✅ JÓ - Email validation
if (v::email($email)) {
    // Send email
}

// ❌ ROSSZ - Nincs validáció
mail($email, $subject, $message);
```

### 5. Performance

```php
// ✅ JÓ - Cache használat
$services = $site->index()->filterBy('template', 'service');

// ❌ ROSSZ - Ismételt lekérdezés
<?php foreach ($site->index()->filterBy('template', 'service') as $service): ?>
<?php endforeach ?>
```

---

## 🔧 Hibaelhárítás

### Probléma 1: "Oldal nem található"

**Tünet:** 404 hiba
**Okok:**
1. Content fájl hiányzik
2. Route nem megfelelő
3. Blueprint hiányzik

**Megoldás:**
```bash
# 1. Ellenőrizd a content fájlt
ls -la content/

# 2. Ellenőrizd a route-okat
cat site/config/routes.php

# 3. Cache törlés
rm -rf site/cache/* site/sessions/*
```

### Probléma 2: "Undefined array key"

**Tünet:** PHP hiba
**Ok:** CSV vagy content mező hiányzik

**Megoldás:**
```php
// ❌ ROSSZ
$location = $csv['MainLocation'];

// ✅ JÓ
$location = $csv['MainLocation'] ?? 'Default';

// ✅ JÓ - Kirby field
if ($page->fieldName()->isNotEmpty()) {
    $value = $page->fieldName()->value();
}
```

### Probléma 3: "Locations nem jelenik meg"

**Tünet:** Üres lista
**Okok:**
1. CSV formátum rossz
2. Model hiba
3. Fizikai mappák ütköznek

**Megoldás:**
```bash
# 1. CSV ellenőrzés
head -5 content/locations.csv

# 2. Fizikai mappák törlése
rm -rf content/2_szolgaltatasi-teruletek/0_asztalos-*

# 3. Cache törlés
rm -rf site/cache/* site/sessions/*
```

### Probléma 4: "AI generálás nem működik"

**Tünet:** Hiányzó tartalom
**Okok:**
1. API key hiányzik
2. Text replacements nem konfigurálva
3. OpenAI API hiba

**Megoldás:**
```bash
# 1. API key ellenőrzés
cat .env | grep OPENAI_API_KEY

# 2. Config ellenőrzés
cat site/config/config.php | grep johannschopplich

# 3. Log ellenőrzés
tail -f site/logs/error.log
```

### Probléma 5: "Változtatások nem látszanak"

**Tünet:** Régi tartalom
**Ok:** Cache

**Megoldás:**
```bash
# 1. Kirby cache törlés
rm -rf site/cache/* site/sessions/*

# 2. Böngésző cache törlés (Hard refresh)
# Chrome/Firefox: Ctrl+Shift+R
# Safari: Cmd+Option+R

# 3. Opcode cache törlés (ha van)
php -r "opcache_reset();"
```

---

## 📚 További Olvasnivalók

- [Kirby Hivatalos Dokumentáció](https://getkirby.com/docs)
- [Kirby Cookbook](https://getkirby.com/docs/cookbook)
- [Kirby Plugins](https://getkirby.com/plugins)
- [Architektúra](ARCHITECTURE.md)
- [Routing Részletek](ROUTING.md)

---

**Verzió:** 1.0.0
**Utolsó frissítés:** 2024-12-03
**Készítette:** Claude Code
