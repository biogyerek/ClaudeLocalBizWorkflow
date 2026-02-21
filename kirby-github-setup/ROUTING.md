# Kirby Microsite - URL Routing Rendszer

> Részletes útmutató a Kirby CMS egyedi routing architektúrájához

[English version](ROUTING.en.md) | [Vissza az Architektúrához](ARCHITECTURE.md)

## 📋 Tartalomjegyzék

- [Áttekintés](#áttekintés)
- [Routing Fájl Struktúra](#routing-fájl-struktúra)
- [Route Típusok](#route-típusok)
- [Működési Példák](#működési-példák)
- [URL Pattern Szintaxis](#url-pattern-szintaxis)
- [Best Practices](#best-practices)
- [Hibaelhárítás](#hibaelhárítás)

---

## 🎯 Áttekintés

A Kirby Microsite egyedi **URL routing rendszert** használ, amely:

✅ **Eltávolítja a `/service-areas/` prefix-et** az URL-ekből
✅ **Szebb, rövidebb URL-eket** biztosít
✅ **SEO-barát** URL struktúrát nyújt
✅ **Automatikus átirányításokat** kezel

### Cél

Fizikai struktúra:
```
content/2_szolgaltatasi-teruletek/0_asztalos-budapest/
```

Elérhető URL:
```
/asztalos-budapest  ✅ (nem /szolgaltatasi-teruletek/asztalos-budapest)
```

---

## 📁 Routing Fájl Struktúra

### Fő Konfiguráció

```php
// site/config/config.php
return [
    'routes' => require_once 'routes.php',
];
```

### Routes Fájl

```php
// site/config/routes.php
<?php

return [
    // Route #1: Sublocations
    [
        'pattern' => 'asztalos-(:any)-(:all)',
        'action' => function ($mainLocationSlug, $subLocationSlug) {
            // ...
        }
    ],

    // Route #2: Single level URLs
    [
        'pattern' => '(:any)',
        'action'  => function($uid) {
            // ...
        }
    ],

    // Route #3: Redirect /szolgaltatasi-teruletek/X → /X
    [
        'pattern' => 'szolgaltatasi-teruletek/(:any)',
        'action'  => function($uid) {
            go($uid);
        }
    ],

    // Route #4: Two-level URLs
    [
        'pattern' => '(:any)/(:any)',
        'action'  => function($parent, $uid) {
            // ...
        }
    ],

    // Route #5: Deep paths redirect
    [
        'pattern' => 'szolgaltatasi-teruletek/(:all)',
        'action'  => function($uid) {
            go($uid);
        }
    ],
];
```

---

## 🗺️ Route Típusok

### ROUTE #1: Alhelyek (Sublocations)

```php
[
    'pattern' => 'asztalos-(:any)-(:all)',
    'action' => function ($mainLocationSlug, $subLocationSlug) {
        // Slug normalizálás
        $mainLocationSlug = Str::slug(urldecode($mainLocationSlug));
        $subLocationSlug = Str::slug(urldecode(str_replace([' ', '.', ','], '-', $subLocationSlug)));

        // Sublocation keresése
        $subLocationPage = page('szolgaltatasi-teruletek')
            ->index()
            ->filterBy('slug', 'asztalos-' . $mainLocationSlug . '-' . $subLocationSlug)
            ->first();

        if ($subLocationPage) {
            return $subLocationPage;
        }

        return site()->errorPage();
    }
]
```

**Mit csinál:**
- Alhelyek kezelése egyszerűsített URL-lel
- Példa URL: `/asztalos-budapest-i-kerulet`

**URL Komponensek:**
- `(:any)` = Főhelyszín (pl. `budapest`)
- `(:all)` = Alhelyszín (pl. `i-kerulet`)

**Működés:**
1. URL: `/asztalos-budapest-i-kerulet`
2. Parse: `budapest` + `i-kerulet`
3. Keres: slug = `asztalos-budapest-i-kerulet`
4. `page('szolgaltatasi-teruletek')->index()` - **ÖSSZES** leszármazott
5. `filterBy('slug', ...)` - Szűrés
6. Ha megvan → megjeleníti, különben 404

**Miért kell az `index()`?**
```php
// ❌ ROSSZ - csak közvetlen gyerekek
$page->children()

// ✅ JÓ - összes leszármazott (gyerekek + unokák + dédunokák)
$page->index()
```

**Példák:**

| URL | Főhelyszín | Alhelyszín | Végső Slug |
|-----|-----------|-----------|-----------|
| `/asztalos-budapest-i-kerulet` | budapest | i-kerulet | `asztalos-budapest-i-kerulet` |
| `/asztalos-budapest-ii-kerulet` | budapest | ii-kerulet | `asztalos-budapest-ii-kerulet` |

---

### ROUTE #2: Egyszintű URL-ek

```php
[
    'pattern' => '(:any)',
    'action'  => function($uid) {
        $page = page($uid);
        if(!$page) $page = page('service-areas/' . $uid);
        if(!$page) $page = site()->errorPage();
        return site()->visit($page);
    }
]
```

**Mit csinál:**
- **Eltávolítja a `/service-areas/` prefix-et**
- Minden egyszintű URL-t kezel

**Működés:**
1. URL: `/asztalos-budapest`
2. Keres: `page('asztalos-budapest')` → **NINCS**
3. Keres: `page('szolgaltatasi-teruletek/asztalos-budapest')` → **MEGVAN!**
4. Megjeleníti az oldalt

**Példák:**

| Beírt URL | 1. Próbálkozás | 2. Próbálkozás | Eredmény |
|-----------|---------------|---------------|----------|
| `/asztalos-budapest` | `page('asztalos-budapest')` → ❌ | `page('szolgaltatasi-teruletek/asztalos-budapest')` → ✅ | Megjelenik |
| `/magunkrol` | `page('magunkrol')` → ✅ | - | Megjelenik |
| `/szolgaltatasaink` | `page('szolgaltatasaink')` → ✅ | - | Megjelenik |
| `/nonexistent` | `page('nonexistent')` → ❌ | `page('szolgaltatasi-teruletek/nonexistent')` → ❌ | 404 |

**⚠️ Fontos:** Ez a route **mohó** (greedy) - minden egyszintű URL-t elkap!

---

### ROUTE #3: Service-Areas Átirányítás (Egyszintű)

```php
[
    'pattern' => 'szolgaltatasi-teruletek/(:any)',
    'action'  => function($uid) {
        go($uid);
    }
]
```

**Mit csinál:**
- Átirányít `/szolgaltatasi-teruletek/X` → `/X`
- 302 redirect

**Példák:**

| Beírt URL | Átirányítás | Státusz |
|-----------|-------------|---------|
| `/szolgaltatasi-teruletek/asztalos-budapest` | `/asztalos-budapest` | 302 Redirect |
| `/szolgaltatasi-teruletek/magunkrol` | `/magunkrol` | 302 Redirect |

**Miért kell?**
- SEO: Ne legyenek duplikált URL-ek
- Ha valaki könyvjelzőzte a régi URL-t, működjön
- Google ne indexeljen két verziót

---

### ROUTE #4: Kétszintű URL-ek

```php
[
    'pattern' => '(:any)/(:any)',
    'action'  => function($parent, $uid) {
        $page = page($parent.'/'.$uid);

        if(!$page) $page = page('service-areas/' .$parent .'/'. $uid);
        if(!$page) $page = site()->errorPage();

        return site()->visit($page);
    }
]
```

**Mit csinál:**
- Kétszintű oldal struktúrákat kezel
- Példa: `/about/team` vagy `/services/carpentry`

**Működés:**
1. URL: `/about/team`
2. Keres: `page('about/team')` → **MEGVAN!**
3. Megjeleníti

**Példák:**

| URL | 1. Próbálkozás | 2. Próbálkozás | Eredmény |
|-----|---------------|---------------|----------|
| `/about/team` | `page('about/team')` → ✅ | - | Megjelenik |
| `/services/carpentry` | `page('services/carpentry')` → ✅ | - | Megjelenik |
| `/unknown/page` | `page('unknown/page')` → ❌ | `page('service-areas/unknown/page')` → ❌ | 404 |

---

### ROUTE #5: Service-Areas Átirányítás (Többszintű)

```php
[
    'pattern' => 'szolgaltatasi-teruletek/(:all)',
    'action'  => function($uid) {
        go($uid);
    }
]
```

**Mit csinál:**
- Átirányít **bármilyen mély** `/szolgaltatasi-teruletek/` URL-t
- Példa: `/szolgaltatasi-teruletek/x/y/z` → `/x/y/z`

**Különbség `(:any)` és `(:all)` között:**
- `(:any)` = **egy** szegmens (pl. `budapest`)
- `(:all)` = **bármennyi** szegmens (pl. `something/else/deep`)

**Példák:**

| Beírt URL | Átirányítás |
|-----------|-------------|
| `/szolgaltatasi-teruletek/budapest` | `/budapest` |
| `/szolgaltatasi-teruletek/budapest/i-kerulet` | `/budapest/i-kerulet` |
| `/szolgaltatasi-teruletek/x/y/z/deep` | `/x/y/z/deep` |

---

## 🚀 Működési Példák

### Példa 1: Budapest Location Megnyitása

```
1. Felhasználó beírja: /asztalos-budapest

2. Kirby ellenőrzi a route-okat sorrendben:

   ✅ ROUTE #1 (asztalos-(:any)-(:all))
      → NEM illeszkedik (nincs második kötőjel)

   ✅ ROUTE #2 ((:any))
      → ILLESZKEDIK!
      → $uid = 'asztalos-budapest'

3. ROUTE #2 futtatása:
   - page('asztalos-budapest') → NINCS
   - page('szolgaltatasi-teruletek/asztalos-budapest') → MEGVAN!

4. locations.php MODEL fut:
   - Beolvassa locations.csv
   - ELLENŐRZI: Van-e fizikai 0_asztalos-budapest mappa?
   - HA VAN: A fizikai mappa tartalma használódik ✅
   - HA NINCS: Virtuális oldalt generál: slug='asztalos-budapest', title='Budapest'

5. location.php CONTROLLER:
   - $template = $page (saját oldal, NEM parent!)
   - Előkészíti az adatokat

6. location.php TEMPLATE:
   - Megjeleníti az EGYEDI location.en.txt tartalmát
   - (Intro, Leader, Text mezők a location mappából)

7. HTML kimenet → Browser
```

### Példa 2: Alhelyszín (Budapest I. kerület)

```
1. Felhasználó beírja: /asztalos-budapest-i-kerulet

2. Kirby ellenőrzi:

   ✅ ROUTE #1 (asztalos-(:any)-(:all))
      → ILLESZKEDIK!
      → $mainLocationSlug = 'budapest'
      → $subLocationSlug = 'i-kerulet'

3. ROUTE #1 futtatása:
   - Slug normalizálás
   - Keres: slug = 'asztalos-budapest-i-kerulet'
   - page('szolgaltatasi-teruletek')->index() → ÖSSZES leszármazott
   - filterBy('slug', 'asztalos-budapest-i-kerulet') → MEGVAN!

4. sublocation.php TEMPLATE:
   - Megjeleníti az alhelyszín oldalt (virtuális)

5. HTML kimenet → Browser
```

### Példa 3: Régi URL Átirányítása

```
1. Felhasználó beírja: /szolgaltatasi-teruletek/asztalos-budapest

2. Kirby ellenőrzi:

   ✅ ROUTE #1 → NEM illeszkedik
   ✅ ROUTE #2 → NEM illeszkedik (2 szegmens van)
   ✅ ROUTE #3 (szolgaltatasi-teruletek/(:any))
      → ILLESZKEDIK!
      → $uid = 'asztalos-budapest'

3. ROUTE #3 futtatása:
   - go('asztalos-budapest')
   - 302 Redirect → /asztalos-budapest

4. Browser követi a redirectet → /asztalos-budapest

5. Most ROUTE #2 fut (lásd Példa 1)
```

---

## 📝 URL Pattern Szintaxis

### Kirby Pattern Típusok

| Pattern | Mit fog el | Példák |
|---------|-----------|--------|
| `(:any)` | **Egy** szegmens (alfanumerikus + kötőjel) | `budapest`, `asztalos-123`, `magunkrol` |
| `(:all)` | **Bármennyi** szegmens (beleértve `/`) | `budapest/i-kerulet`, `a/b/c/d` |
| `(:num)` | Csak számok | `123`, `2024` |
| `(:alpha)` | Csak betűk | `about`, `services` |
| `asztalos-(:any)` | Fix prefix + változó | `asztalos-budapest` |

### Példák

```php
// Csak számok
'pattern' => 'page-(:num)'
// Illeszkedik: /page-123
// NEM illeszkedik: /page-abc

// Alfa karakterek
'pattern' => '(:alpha)'
// Illeszkedik: /about
// NEM illeszkedik: /about-us

// Fix + változó
'pattern' => 'blog/(:any)/(:num)'
// Illeszkedik: /blog/my-post/2024
// NEM illeszkedik: /blog/my-post/title
```

---

## ⚡ Best Practices

### 1. Route Sorrend Fontos!

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
    'pattern' => '(:any)',  // Ez mindent elkap!
    'action' => ...
],
[
    'pattern' => 'asztalos-(:any)-(:all)',  // Soha nem fut le!
    'action' => ...
],
```

### 2. Használj Visszatérési Értékeket

```php
// ✅ JÓ
return site()->visit($page);

// ❌ ROSSZ
echo $page;  // Nem teljes Kirby renderelés
```

### 3. 404 Kezelés

```php
// ✅ JÓ
if (!$page) {
    return site()->errorPage();
}

// ❌ ROSSZ
if (!$page) {
    return 'Page not found';  // Nincs megfelelő HTTP státusz
}
```

### 4. Redirect vs Visit

```php
// Redirect (302) - új URL a böngészőben
go($newUrl);

// Visit - renderelés ugyanazon az URL-en
return site()->visit($page);
```

---

## 🔧 Hibaelhárítás

### Probléma 1: "Route nem működik"

**Tünet:** Az URL 404-et ad vissza
**Ok:** Route pattern nem illeszkedik

**Megoldás:**
```php
// Debug: Nézd meg mi az $uid
'action' => function($uid) {
    dump($uid);  // Mi jött be?
    exit;
}
```

### Probléma 2: "Végtelen átirányítás"

**Tünet:** Browser timeout
**Ok:** Két route egymásra irányít át

**Megoldás:**
```php
// ❌ ROSSZ
'pattern' => 'A',
'action' => function() { go('B'); }

'pattern' => 'B',
'action' => function() { go('A'); }  // Végtelen loop!
```

### Probléma 3: "Locations nem jelennek meg"

**Tünet:** Üres oldal, nincs location lista
**Ok:** `index()` helyett `children()` használata

**Megoldás:**
```php
// ❌ ROSSZ - csak közvetlen gyerekek
page('szolgaltatasi-teruletek')->children()->filterBy(...)

// ✅ JÓ - összes leszármazott
page('szolgaltatasi-teruletek')->index()->filterBy(...)
```

### Probléma 4: "Route túl mohó"

**Tünet:** Más oldalak is a route-ba esnek
**Ok:** `(:any)` mindent elkap

**Megoldás:**
```php
// Specifikusabb pattern
'pattern' => 'asztalos-(:any)'  // Csak asztalos- kezdetűek
```

---

## 📊 Route Prioritási Diagram

```
Request: /asztalos-budapest-i-kerulet
│
├─ ROUTE #1: asztalos-(:any)-(:all)
│  └─ ILLESZKEDIK! ✅
│     └─ Sublocation kezelése
│
├─ ROUTE #2: (:any)
│  └─ NEM fut (Route #1 előbb volt)
│
├─ ROUTE #3: szolgaltatasi-teruletek/(:any)
│  └─ NEM illeszkedik (nincs szolgaltatasi-teruletek/)
│
├─ ROUTE #4: (:any)/(:any)
│  └─ NEM illeszkedik (nincs / a közepén)
│
└─ ROUTE #5: szolgaltatasi-teruletek/(:all)
   └─ NEM illeszkedik (nincs szolgaltatasi-teruletek/)
```

---

## 🔄 Fizikai vs Virtuális Location Oldalak

### Működési Elv (2024-12-03 óta)

A `locations.php` model **kombinálja** a fizikai és virtuális location oldalakat:

**1. Fizikai Location Oldalak** (pl. `0_asztalos-budapest/`)
- Ha létezik fizikai mappa → használja azt ✅
- Tartalom: `location.en.txt` (Intro, Leader, Text mezők)
- AI-generált tartalom itt tárolódik
- URL: `/asztalos-budapest`

**2. Virtuális Location Oldalak**
- Ha NEM létezik fizikai mappa → generál virtuális oldalt
- Adatforrás: `locations.csv`
- Tartalom: Parent template (locations.en.txt) placeholder-ekkel
- URL: `/asztalos-erd` (ha nincs fizikai mappa)

### locations.php Model Működés

```php
// Ellenőrzi: van-e fizikai mappa?
$physicalPage = $physicalChildren->findBy('slug', 'asztalos-budapest');

if (!$physicalPage) {
    // Csak akkor generál virtuális oldalt, ha nincs fizikai
    $virtualChildren[$slug] = [
        'slug' => 'asztalos-budapest',
        'title' => 'Budapest',
        ...
    ];
}

// Kombinálja fizikai + virtuális oldalakat
return parent::children()->merge(Pages::factory($virtualChildren));
```

### location.php Controller Változás

**RÉGI** (problémás):
```php
$template = $page->parent();  // locations.en.txt ❌
```

**ÚJ** (helyes):
```php
$template = $page;  // asztalos-budapest/location.en.txt ✅
$parentTemplate = $page->parent();  // fallback
```

### Eredmény

✅ **AI-generált tartalom megmarad** mentés után
✅ **Fizikai mappák tartalma látszik** a weboldalon
✅ **Virtuális oldalak** továbbra is működnek (CSV-ből)
✅ **Nincs duplikáció** vagy felülírás

---

## 📚 További Olvasnivaló

- [Kirby Routing Docs](https://getkirby.com/docs/guide/routing)
- [Architektúra](ARCHITECTURE.md)
- [Fejlesztői Útmutató](DEVELOPMENT.md)

---

**Utolsó frissítés:** 2024-12-03
**Készítette:** Claude Code
