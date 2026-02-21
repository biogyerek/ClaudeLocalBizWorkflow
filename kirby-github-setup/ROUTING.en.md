# Kirby Microsite - URL Routing System

> Detailed guide to the Kirby CMS custom routing architecture

[Magyar verzió](ROUTING.md) | [Back to Architecture](ARCHITECTURE.en.md)

## 📋 Table of Contents

- [Overview](#overview)
- [Routing File Structure](#routing-file-structure)
- [Route Types](#route-types)
- [Working Examples](#working-examples)
- [URL Pattern Syntax](#url-pattern-syntax)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

---

## 🎯 Overview

The Kirby Microsite uses a custom **URL routing system** that:

✅ **Removes the `/szolgaltatasi-teruletek/` prefix** from URLs
✅ **Provides cleaner, shorter URLs**
✅ **Offers SEO-friendly** URL structure with Hungarian slugs
✅ **Handles automatic redirects**

### Goal

Physical structure:
```
content/2_szolgaltatasi-teruletek/
```

Accessible URL:
```
/asztalos-budapest  ✅ (not /szolgaltatasi-teruletek/asztalos-budapest)
```

**Note:** The slug format changed from `service-in-budapest` to `asztalos-budapest` (2024-12-03)

### ⚠️ Industry-Specific Routing (IMPORTANT!)

The current routing is configured for **carpentry (asztalos)** business:
- Slug pattern: `asztalos-{city}`
- Example: `/asztalos-budapest`, `/asztalos-godollo`

**To change to a different industry** (e.g. paving, heating, cooling):
1. Update `/site/config/routes.php` pattern
2. Update `/content/locations.csv` slug column
3. Update physical folder names in `/content/2_szolgaltatasi-teruletek/`

See [SYSTEM_ARCHITECTURE.md](SYSTEM_ARCHITECTURE.md) section 4 for detailed instructions

---

## 📁 Routing File Structure

### Main Configuration

```php
// site/config/config.php
return [
    'routes' => require_once 'routes.php',
];
```

### Routes File

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

## 🗺️ Route Types

### ROUTE #1: Sublocations

```php
[
    'pattern' => 'asztalos-(:any)-(:all)',
    'action' => function ($mainLocationSlug, $subLocationSlug) {
        // Slug normalization
        $mainLocationSlug = Str::slug(urldecode($mainLocationSlug));
        $subLocationSlug = Str::slug(urldecode(str_replace([' ', '.', ','], '-', $subLocationSlug)));

        // Find sublocation
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

**What it does:**
- Handles sublocations with simplified URLs
- Example URL: `/asztalos-budapest-i-kerulet`

**URL Components:**
- `(:any)` = Main location (e.g. `budapest`)
- `(:all)` = Sublocation (e.g. `i-district`)

**How it works:**
1. URL: `/asztalos-budapest-i-kerulet`
2. Parse: `budapest` + `i-kerulet`
3. Search: slug = `asztalos-budapest-i-kerulet`
4. `page('szolgaltatasi-teruletek')->index()` - **ALL** descendants
5. `filterBy('slug', ...)` - Filter
6. If found → display, otherwise 404

**Why `index()`?**
```php
// ❌ WRONG - only direct children
$page->children()

// ✅ CORRECT - all descendants (children + grandchildren + great-grandchildren)
$page->index()
```

---

### ROUTE #2: Single-Level URLs

```php
[
    'pattern' => '(:any)',
    'action'  => function($uid) {
        $page = page($uid);
        if(!$page) $page = page('szolgaltatasi-teruletek/' . $uid);
        if(!$page) $page = site()->errorPage();
        return site()->visit($page);
    }
]
```

**What it does:**
- **Removes the `/szolgaltatasi-teruletek/` prefix**
- Handles all single-level URLs

**How it works:**
1. URL: `/asztalos-budapest`
2. Search: `page('asztalos-budapest')` → **NOT FOUND**
3. Search: `page('szolgaltatasi-teruletek/asztalos-budapest')` → **FOUND!**
4. Display the page

**Examples:**

| Entered URL | 1st Attempt | 2nd Attempt | Result |
|-------------|-------------|-------------|--------|
| `/service-in-budapest` | `page('service-in-budapest')` → ❌ | `page('service-areas/service-in-budapest')` → ✅ | Displays |
| `/about` | `page('about')` → ✅ | - | Displays |
| `/services` | `page('services')` → ✅ | - | Displays |
| `/nonexistent` | `page('nonexistent')` → ❌ | `page('service-areas/nonexistent')` → ❌ | 404 |

---

### ROUTE #3: Service-Areas Redirect (Single Level)

```php
[
    'pattern' => 'service-areas/(:any)',
    'action'  => function($uid) {
        go($uid);
    }
]
```

**What it does:**
- Redirects `/service-areas/X` → `/X`
- 302 redirect

**Examples:**

| Entered URL | Redirects To | Status |
|-------------|--------------|--------|
| `/service-areas/service-in-budapest` | `/service-in-budapest` | 302 Redirect |
| `/service-areas/about` | `/about` | 302 Redirect |

---

### ROUTE #4: Two-Level URLs

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

**What it does:**
- Handles two-level page structures
- Example: `/about/team` or `/services/carpentry`

---

### ROUTE #5: Service-Areas Redirect (Multi-Level)

```php
[
    'pattern' => 'service-areas/(:all)',
    'action'  => function($uid) {
        go($uid);
    }
]
```

**What it does:**
- Redirects **any deep** `/service-areas/` URL
- Example: `/service-areas/x/y/z` → `/x/y/z`

**Difference between `(:any)` and `(:all)`:**
- `(:any)` = **one** segment (e.g. `budapest`)
- `(:all)` = **any number** of segments (e.g. `something/else/deep`)

---

## 🚀 Working Examples

### Example 1: Opening Budapest Location

```
1. User enters: /service-in-budapest

2. Kirby checks routes in order:

   ✅ ROUTE #1 (service-in-(:any)-(:all))
      → NO MATCH (no second hyphen)

   ✅ ROUTE #2 ((:any))
      → MATCH!
      → $uid = 'service-in-budapest'

3. ROUTE #2 executes:
   - page('service-in-budapest') → NOT FOUND
   - page('service-areas/service-in-budapest') → FOUND!

4. locations.php MODEL runs:
   - Reads locations.csv
   - Generates: slug='service-in-budapest', title='Budapest'

5. location.php CONTROLLER:
   - Prepares data

6. location.php TEMPLATE:
   - Displays the page

7. HTML output → Browser
```

---

## 📝 URL Pattern Syntax

### Kirby Pattern Types

| Pattern | What it Matches | Examples |
|---------|-----------------|----------|
| `(:any)` | **One** segment (alphanumeric + hyphen) | `budapest`, `service-123`, `about-us` |
| `(:all)` | **Any number** of segments (including `/`) | `budapest/i-district`, `a/b/c/d` |
| `(:num)` | Numbers only | `123`, `2024` |
| `(:alpha)` | Letters only | `about`, `services` |
| `service-in-(:any)` | Fixed prefix + variable | `service-in-budapest` |

---

## ⚡ Best Practices

### 1. Route Order Matters!

```php
// ✅ GOOD - specific route first
[
    'pattern' => 'service-in-(:any)-(:all)',  // Specific
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
    'pattern' => 'service-in-(:any)-(:all)',  // Never runs!
    'action' => ...
],
```

### 2. Use Return Values

```php
// ✅ GOOD
return site()->visit($page);

// ❌ BAD
echo $page;  // Not a complete Kirby render
```

### 3. 404 Handling

```php
// ✅ GOOD
if (!$page) {
    return site()->errorPage();
}

// ❌ BAD
if (!$page) {
    return 'Page not found';  // No proper HTTP status
}
```

---

## 🔧 Troubleshooting

### Problem 1: "Route not working"

**Symptom:** URL returns 404
**Cause:** Route pattern doesn't match

**Solution:**
```php
// Debug: See what comes in
'action' => function($uid) {
    dump($uid);  // What came in?
    exit;
}
```

### Problem 2: "Infinite redirect"

**Symptom:** Browser timeout
**Cause:** Two routes redirect to each other

**Solution:**
```php
// ❌ BAD
'pattern' => 'A',
'action' => function() { go('B'); }

'pattern' => 'B',
'action' => function() { go('A'); }  // Infinite loop!
```

### Problem 3: "Locations not showing"

**Symptom:** Empty page, no location list
**Cause:** Using `children()` instead of `index()`

**Solution:**
```php
// ❌ BAD - only direct children
page('service-areas')->children()->filterBy(...)

// ✅ GOOD - all descendants
page('service-areas')->index()->filterBy(...)
```

---

## 📚 Further Reading

- [Kirby Routing Docs](https://getkirby.com/docs/guide/routing)
- [Architecture](ARCHITECTURE.en.md)
- [Developer Guide](DEVELOPMENT.en.md)

---

**Last updated:** 2024-12-03
**Created by:** Claude Code
