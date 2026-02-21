# Changelog - 2024-12-03

## 🔧 Location Oldal Tartalom Megjelenítés Javítás

### Probléma

A location oldalak (pl. `/asztalos-budapest`, `/asztalos-erd`) **nem jelenítették meg** az AI-vel generált tartalmat, még mentés után sem.

**Oka:**
1. A `locations.php` model virtuális oldalakat generált, amelyek **felülírták** a fizikai mappák tartalmát
2. A `location.php` controller a parent (locations.en.txt) tartalmát jelenítette meg, nem a saját location.en.txt-t
3. Az AI-generált tartalom elmentődött, de nem lett megjelenítve

### Megoldás

#### 1. locations.php Model Módosítás

**RÉGI működés:**
```php
// Minden location-höz virtuális oldalt generált (üres tartalommal)
$children[$slug] = [
    'slug' => 'asztalos-budapest',
    'title' => 'Budapest',
    // Csak title és uuid, NINCS intro, text, leader!
];
```

**ÚJ működés:**
```php
// Ellenőrzi: van-e fizikai mappa?
$physicalPage = $physicalChildren->findBy('slug', 'asztalos-budapest');

if (!$physicalPage) {
    // Csak akkor generál virtuális oldalt, ha nincs fizikai
    $virtualChildren[$slug] = [...];
}

// Kombinálja fizikai + virtuális oldalakat
return parent::children()->merge(Pages::factory($virtualChildren));
```

**Eredmény:**
- Ha van fizikai `0_asztalos-budapest/` mappa → használja azt ✅
- Ha nincs → virtuális oldalt generál (CSV-ből)
- **Nincs felülírás vagy duplikáció**

#### 2. location.php Controller Módosítás

**RÉGI működés:**
```php
$template = $page->parent();  // locations.en.txt ❌
```

**ÚJ működés:**
```php
$template = $page;  // asztalos-budapest/location.en.txt ✅
$parentTemplate = $page->parent();  // fallback
```

**Eredmény:**
- A snippet-ek az **egyedi location.en.txt** tartalmát jelenítik meg
- Intro, Leader, Text mezők mind láthatók
- AI-generált tartalom megmarad mentés után

#### 3. location.php Template Módosítás

**RÉGI működés:**
```php
if ($template->showTemplateLeader()->toBool() === true):
```

**ÚJ működés:**
```php
$showLeader = $template->showLeader()->isNotEmpty()
    ? $template->showLeader()->toBool()
    : ($parentTemplate->showTemplateLeader()->toBool() ?? true);
```

**Eredmény:**
- Ellenőrzi a location oldal saját `showLeader` mezőjét
- Fallback a parent `showTemplateLeader`-re

### Slug Formátum Változások

| Régi formátum | Új formátum |
|--------------|-------------|
| `/service-in-budapest` | `/asztalos-budapest` |
| `/service-in-budapest-i-kerulet` | `/asztalos-budapest-i-kerulet` |
| `/service-areas/X` | `/szolgaltatasi-teruletek/X` |

### Frissített Fájlok

1. **site/models/locations.php**
   - Fizikai és virtuális oldalak kombinálása
   - Nincs felülírás

2. **site/controllers/location.php**
   - `$template = $page` (saját oldal)
   - `$parentTemplate` hozzáadva fallback-nek

3. **site/templates/location.php**
   - `showLeader` ellenőrzés fallback-kel

4. **site/config/routes.php**
   - `service-in-` → `asztalos-`
   - `service-areas` → `szolgaltatasi-teruletek`

### Dokumentáció Frissítések

- ✅ **ROUTING.md** - Új slug formátum, fizikai vs virtuális oldalak
- ⏳ **ROUTING.en.md** - Angol verzió frissítése folyamatban
- ⏳ **ARCHITECTURE.md** - Model működés frissítése
- ⏳ **ARCHITECTURE.en.md** - Angol verzió frissítése
- ⏳ **README.md** - Példák frissítése
- ⏳ **README.en.md** - Angol verzió frissítése

### Tesztelés

**Előtte:**
1. AI-generált tartalom mentése → ✅ sikeres
2. Oldal megnyitása `/asztalos-erd` → ❌ üres oldal
3. Oldal újra megnyitása a panelben → ❌ tartalom eltűnt

**Utána:**
1. AI-generált tartalom mentése → ✅ sikeres
2. Oldal megnyitása `/asztalos-erd` → ✅ teljes tartalom látszik
3. Oldal újra megnyitása a panelben → ✅ tartalom megmarad

### Commit

```
git commit -m "Fix location pages to display individual content

- Modified location.php controller: Use current page as \$template instead of parent
- Modified location.php template: Check showLeader field with fallback
- Modified locations.php model: Don't override physical pages with virtual ones

This fixes the issue where location pages were showing empty content
instead of their saved AI-generated content.

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```

---

**Frissítve:** 2024-12-03
**Létrehozta:** Claude Code
