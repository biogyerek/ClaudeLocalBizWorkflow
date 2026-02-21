# Kirby Microsite - Rendszer Architektúra

> Teljes körű technikai dokumentáció a Kirby CMS alapú microsite rendszerről

[English version](ARCHITECTURE.en.md) | [Vissza a README-hez](README.md)

## 📋 Tartalomjegyzék

- [Áttekintés](#áttekintés)
- [Gyökér Struktúra](#gyökér-struktúra)
- [Request Életciklus](#request-életciklus)
- [Content Mappa](#content-mappa)
- [Site Mappa](#site-mappa)
- [Adatfolyam Példák](#adatfolyam-példák)
- [Kirby Koncepciók](#kirby-koncepciók)

---

## 🎯 Áttekintés

A Kirby Microsite egy **file-based CMS architektúra**, amely a következő főbb komponensekből áll:

```
┌─────────────────────────────────────────┐
│         FELHASZNÁLÓ                     │
│            ↓                            │
│       URL Request                       │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│  index.php → Kirby Bootstrap            │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│  config.php → routes.php                │
└─────────────────────────────────────────┘
              ↓
    ┌─────────┴─────────┐
    ↓                   ↓
┌─────────┐      ┌──────────────┐
│ CONTENT │      │ MODELS       │
│ (files) │      │ (dynamic)    │
└─────────┘      └──────────────┘
    ↓                   ↓
    └─────────┬─────────┘
              ↓
┌─────────────────────────────────────────┐
│  CONTROLLER → Adatok előkészítése       │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│  TEMPLATE → HTML renderelés             │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│  SNIPPETS → Komponensek                 │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│  HTML + CSS + JS → BROWSER              │
└─────────────────────────────────────────┘
```

---

## 📁 Gyökér Struktúra

```
microsite_base/
├── index.php              # 🚪 Belépési pont
├── .htaccess              # ⚙️ Apache URL rewrite szabályok
├── composer.json          # 📦 PHP függőségek
│
├── assets/                # 🎨 Frontend eszközök
│   ├── css/               # CSS fájlok
│   ├── js/                # JavaScript fájlok
│   └── images/            # Statikus képek
│
├── content/                      # 📄 TARTALOM - Minden oldal adatai
│   ├── site.en.txt               # Globális beállítások
│   ├── locations.csv             # Helyszín adatok (CSV)
│   ├── home/                     # Főoldal
│   ├── 1_szolgaltatasaink/       # Szolgáltatások
│   ├── 2_szolgaltatasi-teruletek/  # Szolgáltatási területek
│   ├── 3_magunkrol/              # Rólunk
│   ├── 4_ingyenes-arajanlat/     # Árajánlat
│   └── 5_kapcsolatfelvetel/      # Kapcsolat
│
├── kirby/                 # 🔒 Kirby CMS mag (NE MÓDOSÍTSD)
│   ├── bootstrap.php      # Rendszer inicializálás
│   ├── config/            # Alapértelmezett config
│   └── src/               # Kirby forráskód
│
├── media/                 # 🖼️ Generált médiafájlok
│   ├── pages/             # Oldal képek thumbjai
│   └── site/              # Site képek thumbjai
│
└── site/                  # 💻 EGYEDI KÓD - Itt van minden logika
    ├── accounts/          # Felhasználói fiókok
    ├── blueprints/        # Admin panel definíciók
    ├── cache/             # Gyorsítótár
    ├── config/            # Konfiguráció fájlok
    ├── controllers/       # Adat előkészítés
    ├── languages/         # Fordítások
    ├── models/            # Dinamikus oldal generálás
    ├── plugins/           # Telepített pluginek
    ├── sessions/          # Session adatok
    ├── snippets/          # Újrahasználható komponensek
    └── templates/         # HTML template-ek
```

### Fájl Típusok és Szerepük

| Típus | Hol van | Mit csinál | Példa |
|-------|---------|------------|-------|
| **Content** | `content/` | Oldal tartalom tárolása | `home.en.txt` |
| **Blueprint** | `site/blueprints/` | Admin panel mezők | `home.yml` |
| **Model** | `site/models/` | Dinamikus oldal generálás | `locations.php` |
| **Controller** | `site/controllers/` | Adat előkészítés | `home.php` |
| **Template** | `site/templates/` | HTML struktúra | `home.php` |
| **Snippet** | `site/snippets/` | Újrahasználható HTML | `header.php` |
| **Plugin** | `site/plugins/` | Funkcionalitás bővítés | `kirby-copilot/` |
| **Config** | `site/config/` | Beállítások | `config.php` |

---

## 🔄 Request Életciklus

### 1. Request Indítása

```
Felhasználó → https://mykirtemplate.top/asztalos-budapest
```

### 2. Apache .htaccess

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L]
```
**Magyarázat:** Minden nem létező fájl/mappa kérést átirányít az `index.php`-nek.

### 3. index.php

```php
<?php
require 'kirby/bootstrap.php';
echo (new Kirby)->render();
```
**Feladat:** Betölti a Kirby rendszert és rendereli az oldalt.

### 4. config.php Betöltése

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
**Feladat:** Konfiguráció beállítások betöltése.

### 5. Routing Ellenőrzése

```php
// site/config/routes.php
'pattern' => '(:any)',
'action'  => function($uid) {
    $page = page($uid);
    if(!$page) $page = page('service-areas/' . $uid);
    return site()->visit($page);
}
```
**Feladat:** URL pattern felismerése és oldal megkeresése.

### 6. Model Futtatása (ha van)

```php
// site/models/locations.php
class LocationsPage extends Page {
    public function children(): Pages {
        // CSV beolvasás
        $csv = csv($csvFilePath, ';');
        // Virtuális oldalak generálása
        return Pages::factory($children, $this);
    }
}
```
**Feladat:** Dinamikus oldalak generálása (pl. locations CSV-ből).

### 7. Controller Futtatása

```php
// site/controllers/home.php
return function($kirby, $site, $pages, $page) {
    $services = $site->index()->filterBy('template', 'service');
    return compact('services');
};
```
**Feladat:** Adatok előkészítése a template számára.

### 8. Template Renderelése

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
**Feladat:** HTML struktúra összeállítása snippet-ekből.

### 9. HTML Kimenet

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home - Company Name</title>
  <link rel="stylesheet" href="/assets/css/index.css?v=1234567890">
</head>
<body>
  <!-- Renderelt tartalom -->
</body>
</html>
```

---

## 📄 Content Mappa

A `content/` mappa a **tartalom tárolója**. Minden oldal egy könyvtár vagy fájl.

### Fájl Elnevezési Konvenció

```
[sorszám]_[slug]/[slug].en.txt
```

**Példák:**
- `home/home.en.txt` - Főoldal (nem számozott)
- `1_services/services.en.txt` - Szolgáltatások (1. helyen a menüben)
- `2_service-areas/locations.en.txt` - Szolgáltatási területek (2. helyen)

### Content Fájl Formátum

```
Title: Szolgáltatásaink

----

Showleader: true

----

Leader: Professzionális faház kivitelezés Budapest területén

----

Text:

## Szolgáltatásaink

Lorem ipsum dolor sit amet...

----

Uuid: ABC123XYZ
```

**Fontos:**
- `----` = Mező elválasztó
- Mező nevek = Blueprint-ben definiálva
- YAML-szerű formátum, de **NEM** YAML

### Globális Beállítások (site.en.txt)

```
Title: Faház Kivitelezés

----

Companyname: Faház Kivitelezés Kft

----

Companyindustry: Ács

----

Companyarea: Budapest

----

Companylocation: Budapest

----

Companyservices: faház kivitelezés, könnyűszerkezetes ház építése

----

Phone: +36 1 234 5678

----

Email: info@fahazkivitelezes.hu
```

### CSV Helyszínek (locations.csv)

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
- Header sor kötelező: `MainLocation;SubLocation`
- Üres SubLocation = csak főhelyszín

---

## 💻 Site Mappa

### site/config/ - Konfiguráció

| Fájl | Feladat |
|------|---------|
| `config.php` | Fő konfiguráció, betölti a többi config fájlt |
| `routes.php` | **URL routing szabályok** |
| `panel.php` | Admin panel beállítások |
| `copilot.php` | AI Copilot OpenAI konfig |
| `email.php` | Email küldés beállítások |
| `url.php` | Domain autodektálás |
| `cache.php` | Cache beállítások |
| `thumbs.php` | Kép thumbnail generálás |

### site/blueprints/ - Admin Panel Felület

```
blueprints/
├── pages/               # Oldaltípus blueprintek
│   ├── home.yml
│   ├── services.yml
│   ├── location.yml
│   └── service.yml
├── sections/            # Újrahasználható szekciók
│   ├── pages/
│   └── site/
├── fields/              # Egyedi mezők
├── prompts/             # AI Copilot promptok
│   ├── site/
│   ├── home/
│   └── service/
└── tabs/                # Panel fülek
    ├── knowledge.yml
    └── elements-home.yml
```

**Blueprint példa (home.yml):**

```yaml
title: Home

tabs:
  page:
    icon: page
    columns:
      sidebar:
        width: 1/4
        sections:
          leader:
            extends: sections/pages/leader
          auto-leader:
            extends: prompts/home/auto-leader

      main:
        width: 3/4
        sections:
          text:
            extends: sections/pages/text

  knowledge: tabs/knowledge
  files: tabs/files
  seo: seo/page
```

### site/models/ - Dinamikus Oldalgenerálás

**locations.php** - LocationsPage Model:

```php
<?php

use Kirby\Uuid\Uuid;

class LocationsPage extends Page
{
    public function children(): Pages
    {
        // Cache ellenőrzés
        if ($this->children instanceof Pages) {
            return $this->children;
        }

        // CSV beolvasás
        $csvFilePath = kirby()->root('content') . '/locations.csv';
        if (!file_exists($csvFilePath)) {
            return Pages::factory([], $this);
        }

        $csv = csv($csvFilePath, ';');
        $virtualChildren = [];

        // Fizikai oldalak lekérése először
        $physicalChildren = parent::children();

        // Helyszínek feldolgozása
        foreach ($csv as $location) {
            $mainLocation = $location['MainLocation'];
            $subLocation = $location['SubLocation'] ?? null;

            // Slug generálás
            $mainLocationSlug = Str::slug($mainLocation);
            $slug = 'asztalos-' . $mainLocationSlug;

            // Fő helyszín létrehozása
            if (!isset($virtualChildren[$mainLocationSlug])) {
                // Ellenőrzi: létezik-e fizikai mappa?
                $physicalPage = $physicalChildren->findBy('slug', $slug);

                if (!$physicalPage) {
                    // Csak akkor generál virtuális oldalt, ha nincs fizikai
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
                // Ha fizikai oldal létezik, azt használja (nincs felülírás)
            }

            // Alhelyszín hozzáadása
            if ($subLocation) {
                $subLocationSlug = Str::slug($subLocation);

                // Csak virtuális main location-ökhöz ad sublocation-t
                if (isset($virtualChildren[$mainLocationSlug])) {
                    $virtualChildren[$mainLocationSlug]['children'][$subLocationSlug] = [
                        'slug'     => 'asztalos-' . $mainLocationSlug . '-' . $subLocationSlug,
                        'template' => 'sublocation',
                        'model'    => 'sublocation',
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
        }

        // Fizikai + virtuális oldalak kombinálása
        return $this->children = parent::children()->merge(
            Pages::factory(array_values($virtualChildren), $this)
        );
    }
}
```

**Működés (2024-12-03 óta frissítve):**
1. CSV beolvasás
2. Fizikai oldalak lekérése (`parent::children()`)
3. Csak akkor generál virtuális oldalt, ha **nincs fizikai mappa**
4. Fizikai + virtuális oldalak összefűzése (`merge()`)
5. **Nincs felülírás** - fizikai oldalak mindig prioritást élveznek

**Példa:**
- Ha létezik `0_asztalos-budapest/` mappa → használja azt ✅
- Ha nincs fizikai mappa → virtuális oldal generálás CSV-ből ✅
- AI-generált tartalom fizikai mappába mentődik, megmarad ✅

### site/controllers/ - Adat Előkészítés

**home.php** - Home Controller:

```php
<?php

return function($kirby, $site, $pages, $page) {
    // Form controller betöltése
    $form = $kirby->controller('form', compact('kirby'));

    // Services lekérése
    $services = $site->index()->filterBy('template', 'in', ['service']);

    // Adatok visszaadása
    return A::merge($form, compact('services'));
};
```

**locations.php** - Locations Controller:

```php
<?php

return function($kirby, $site, $pages, $page) {
    $form = $kirby->controller('form', compact('kirby'));

    // Pagination beállítás
    $perpage = $page->perpage()->int();

    // Locations lekérése és szűrése
    $locations = $page->children()->filterBy('template', 'location');
    $locations = $locations->sortBy('title')->paginate(($perpage >= 10) ? $perpage : 100);

    // Services lekérése
    $services = $site->index()->filterBy('template', 'in', ['service']);

    return A::merge($form, compact('locations', 'services'));
};
```

### site/templates/ - HTML Struktúra

**home.php** - Home Template:

```php
<!DOCTYPE html>
<html lang="<?= $site->lang() ?>">
<head>
  <?php snippet('layouts/head') ?>
  <?= Bnomei\Fingerprint::css('assets/css/layouts/home.css') ?>
</head>
<body>
  <?php snippet('layouts/header') ?>
  <?php snippet('templates/home/leader') ?>
  <?php snippet('templates/home/text') ?>
  <?php snippet('templates/home/services') ?>
  <?php snippet('templates/home/usps') ?>
  <?php snippet('templates/home/faq') ?>
  <?php snippet('templates/home/contact') ?>
  <?php snippet('seo/schemas'); ?>
</body>
<?php snippet('layouts/footer') ?>
</html>
```

### site/snippets/ - Újrahasználható Komponensek

```
snippets/
├── layouts/              # Általános layout elemek
│   ├── head.php          # <head> tartalom
│   ├── header.php        # Fejléc + navigáció
│   ├── footer.php        # Lábléc
│   ├── menu.php          # Menü rendszer
│   └── sidebar.php       # Oldalsáv
│
├── templates/            # Template-specifikus snippetek
│   ├── home/
│   │   ├── leader.php    # Hero section
│   │   ├── services.php  # Services lista
│   │   ├── usps.php      # Why Choose Us
│   │   ├── faq.php       # GYIK
│   │   └── contact.php   # Kapcsolat szekció
│   ├── locations/
│   │   ├── locations.php # Location lista
│   │   └── leader.php    # Location hero
│   └── service/
│       └── text.php      # Service tartalom
│
└── form/                 # Form komponensek
    ├── contact-form.php
    └── estimate.php
```

**Példa snippet (templates/home/services.php):**

```php
<?php if ($page->showServices()->toBool() === true): ?>
<section class="services-container">
    <div class="container">
        <h2><?= $page->titleServices()->kti() ?></h2>

        <div class="services">
        <?php foreach($services as $service): ?>
            <div class="service-box">
                <h3><?= $service->title()->kti() ?></h3>
                <span><?= $service->intro()->kti() ?></span>
                <a class="btn" href="<?= $service->url() ?>">
                    <?php echo t('readMore') ?>
                </a>
            </div>
        <?php endforeach ?>
        </div>
    </div>
</section>
<?php endif ?>
```

### site/plugins/ - Telepített Pluginek

| Plugin | Funkció |
|--------|---------|
| **kirby-copilot** | AI tartalom generálás (OpenAI GPT-4) |
| **placeholders** | Text replacement system (`{{companyName}}`) |
| **kirby-seo** | SEO meta tagek, Open Graph |
| **kirby3-fingerprint** | Asset verzionálás (cache busting) |
| **kirby-uniform** | Form handling és validáció |
| **toc** | Tartalomjegyzék generálás |
| **webp** | Automatikus WebP konverzió |
| **kirby-navigation** | Menü builder rendszer |

---

## 📊 Adatfolyam Példák

### Példa 1: Főoldal Betöltése

```
1. URL: https://mykirtemplate.top/
   ↓
2. .htaccess → index.php
   ↓
3. Kirby Bootstrap
   ↓
4. config.php betöltése
   ↓
5. Route matching: home page
   ↓
6. home.php CONTROLLER
   - $services lekérése
   ↓
7. home.php TEMPLATE
   - Header snippet
   - Services snippet ($services változó használata)
   - Footer snippet
   ↓
8. HTML output → Browser
```

### Példa 2: Budapest Location Oldal

```
1. URL: /asztalos-budapest
   ↓
2. routes.php ROUTE #2
   - pattern: '(:any)'
   - page('asztalos-budapest') → NINCS fizikailag
   - page('szolgaltatasi-teruletek/asztalos-budapest') → KERES
   ↓
3. locations.php MODEL
   - CSV beolvasás: locations.csv
   - Fizikai oldalak ellenőrzése → NINCS
   - Virtuális oldal generálás
   - slug: 'asztalos-budapest'
   - title: 'Budapest' (ez jelenik meg a panel-ben!)
   ↓
4. location.php CONTROLLER
   - Sublocations lekérése
   - Related services
   - $template = $page (saját oldal tartalma)
   ↓
5. location.php TEMPLATE
   - Location tartalom renderelés
   - Showleader ellenőrzés fallback-kel
   ↓
6. HTML output → Browser
```

### Példa 3: Alhelyszín (Budapest I. kerület)

```
1. URL: /asztalos-budapest-i-kerulet
   ↓
2. routes.php ROUTE #1
   - pattern: 'asztalos-(:any)-(:all)'
   - $mainLocationSlug = 'budapest'
   - $subLocationSlug = 'i-kerulet'
   ↓
3. locations.php MODEL
   - CSV sor: Budapest;I. kerület
   - Virtuális sublocation generálás (csak virtuális main location-ökhöz)
   - slug: 'asztalos-budapest-i-kerulet'
   - title: 'I. kerület'
   ↓
4. sublocation.php MODEL
   - url() override: '/asztalos-budapest-i-kerulet'
   ↓
5. sublocation.php TEMPLATE
   - Sublocation tartalom
   ↓
6. HTML output → Browser
```

---

## 🔑 Kirby Koncepciók

### Page Objektum

```php
// Oldal elérése
$page = page('services');
$page = $site->find('services');

// Oldal adatok
$page->title()            // Cím
$page->url()              // URL
$page->slug()             // Slug (pl. 'services')
$page->template()         // Template neve
$page->intendedTemplate() // Tervezett template

// Navigáció
$page->children()         // Közvetlen gyerekek
$page->index()            // ÖSSZES leszármazott (rekurzív)
$page->parent()           // Szülő oldal
$page->siblings()         // Testvér oldalak
$page->prev()             // Előző testvér
$page->next()             // Következő testvér

// Tartalom mezők
$page->text()             // 'text' mező
$page->intro()            // 'intro' mező
$page->customField()      // Egyedi mező

// Szűrés
$page->children()->filterBy('template', 'service')
$page->children()->sortBy('title')
$page->children()->limit(10)
```

### Site Objektum

```php
// Site elérése
$site = site();

// Site adatok
$site->title()            // Oldal neve
$site->url()              // Alap URL
$site->index()            // ÖSSZES oldal
$site->children()         // Első szintű oldalak
$site->find('about')      // Oldal keresése slug alapján

// Globális mezők
$site->companyName()      // site.en.txt mezők
$site->phone()
$site->email()
```

### Template Választás

Kirby automatikusan választja ki a megfelelő template-et:

```
Content fájl neve    →  Blueprint      →  Template
---------------------------------------------------
home.en.txt          →  home.yml       →  home.php
services.en.txt      →  services.yml   →  services.php
location.en.txt      →  location.yml   →  location.php
```

### Kirby Helper Függvények

```php
// Oldal keresés
page('services')          // Oldal slug alapján
site()->find('about')     // Ugyanaz mint page()

// URL generálás
url('services')           // Abszolút URL
$page->url()              // Oldal URL-je

// Asset URL
asset('assets/css/style.css')->url()

// Fordítás
t('readMore')             // Translation key

// Markdown → HTML
$page->text()->kirbytext()
$page->text()->markdown()

// Képek
$page->image('cover.jpg')
$image->thumb('large')

// Placeholder replacement
$page->text()->kti()      // Kirby Text with placeholders
```

---

## 📈 Teljesítmény Optimalizálás

### Cache Stratégia

```php
// site/config/cache.php
return [
    'pages' => [
        'active' => true,
        'ignore' => fn ($page) => $page->kirby()->user() !== null
    ]
];
```

### Asset Fingerprinting

```php
// Template-ben
<?= Bnomei\Fingerprint::css('assets/css/index.css') ?>

// Output:
// <link href="/assets/css/index.css?v=1234567890">
```

### WebP Konverzió

```php
// Automatikus WebP generálás
$image->thumb([
    'width' => 800,
    'format' => 'webp'
]);
```

---

## 🛠️ Debug és Hibaelhárítás

### Debug Mód Aktiválása

```php
// site/config/config.php
return [
    'debug' => true
];
```

### Kirby Debug Bar

```php
// Template-ben
<?php dump($page) ?>
<?php dump($services) ?>
```

### Gyakori Hibák

**1. "This page is currently offline"**
- **Ok:** PHP hiba, hiányzó fájl
- **Megoldás:** Nézd meg a PHP error log-ot

**2. Locations nem jelennek meg**
- **Ok:** locations.csv rossz formátum vagy model hiba
- **Megoldás:** Ellenőrizd a CSV header sort

**3. Template nem található**
- **Ok:** Template fájl neve nem egyezik a content fájl nevével
- **Megoldás:** `home.en.txt` → `home.php`

---

## 📚 További Olvasnivaló

- [Kirby Hivatalos Dokumentáció](https://getkirby.com/docs)
- [Kirby Cookbook](https://getkirby.com/docs/cookbook)
- [Routing Részletek](ROUTING.md)
- [Fejlesztői Útmutató](DEVELOPMENT.md)

---

**Utolsó frissítés:** 2024-12-03
**Készítette:** Claude Code
