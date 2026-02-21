# Kirby Mikrosite Rendszer Architektúra

## Áttekintés

Ez a rendszer egy Kirby CMS alapú, magyar nyelvű mikrosite-ok gyors létrehozására szolgáló keretrendszer. Elsősorban helyi vállalkozások számára készült, akik szolgáltatási területeken (városokban) szeretnének megjelenni.

## Fő Komponensek

### 1. Kirby CMS Core
- **Verzió**: Kirby 4.x
- **PHP Követelmény**: 8.2+
- **Telepítés**: `/microsite_base/kirby/`

### 2. Plugin-ok

#### Kirby Copilot (AI Content Generation)
- **Helye**: `/microsite_base/site/plugins/kirby-copilot/`
- **Funkció**: AI alapú tartalom generálás magyar nyelvű promptokkal
- **Konfiguráció**:
  - Prompt fájlok: `/site/blueprints/prompts/`
  - Használt formátum: `extends: fields/auto-*` és `userPrompt:`

#### Egyéb Plugin-ok
- **Fingerprint**: CSS/JS cache-busting
- **Matomo Proxy**: Analytics
- **Custom SEO**: SEO mezők kezelése

### 3. Tartalomkezelés

#### Oldaltípusok (Templates)
```
/site/templates/
├── home.php              # Főoldal
├── services.php          # Szolgáltatások listája
├── service.php           # Egyedi szolgáltatás oldal
├── locations.php         # Szolgáltatási területek listája (városok)
├── location.php          # Egyedi város oldal
├── about.php             # Magunkról oldal
├── contact.php           # Kapcsolat oldal
├── faq.php              # GYIK oldal
└── article.php          # Blog cikk
```

#### Blueprint Struktúra
```
/site/blueprints/
├── pages/               # Oldal blueprint-ek
├── sections/            # Újrafelhasználható section-ök
├── fields/              # Egyedi mezők (auto-content generáláshoz)
├── prompts/             # AI prompt definíciók
└── tabs/                # Tab-ok (elements, knowledge, stb.)
```

### 4. Routing Rendszer

#### Jelenlegi Routing (Asztalos-specifikus)
A routing jelenleg **asztalos** vállalkozásra van szabva:

**Slug formátum**: `asztalos-{város}`
- Példák: `/asztalos-budapest`, `/asztalos-godollo`, `/asztalos-biatorbagy`

**Routing logika helye**:
```
/site/config/config.php - routes definíciók
/site/templates/location.php - város oldal template
/site/controllers/locations.php - locations controller
```

#### Routing Módosítása Új Iparághoz

**1. Slug Pattern Frissítése**

A `/site/config/config.php` fájlban:
```php
// RÉGI (asztalos):
'routes' => [
    [
        'pattern' => 'asztalos-(:any)',
        'action' => function($location) {
            // ...
        }
    ]
]

// ÚJ (pl. térkövezés):
'routes' => [
    [
        'pattern' => 'terkovezes-(:any)',
        'action' => function($location) {
            // ...
        }
    ]
]
```

**2. Locations.csv Frissítése**

A `/content/locations.csv` fájl formátuma:
```csv
location,slug
Budapest,asztalos-budapest
Budaörs,asztalos-budaors
```

Új iparághoz változtatni:
```csv
location,slug
Budapest,terkovezes-budapest
Budaörs,terkovezes-budaors
```

**3. Location Mappa Nevének Konvenciója**

Fizikális mappák: `/content/2_szolgaltatasi-teruletek/{szám}_{slug}/`
- Példa: `5_asztalos-budapest/`
- Új iparág: `5_terkovezes-budapest/`

**FONTOS**: A slug-okat minden helyen konzisztensen kell változtatni!

### 5. Placeholder Rendszer

#### Globális Változók (site.en.txt)
```yaml
Companyname: Asztalos Budapest
Companyindustry: Asztalos
Companyservices: beépített szekrény készítés, bútor javítás...
Companyarea: Budapest
```

#### Használat Template-ekben
```php
<?= $site->companyName()->kti() ?>
```

#### Használat Prompt-okban
```yaml
userPrompt: "A céged neve {{ site.companyName }}, és a {{ site.companyIndustry }} iparágban..."
```

#### Használat Content Mezőkben
```yaml
Mission: Megbízható {{ companyServices }} szolgáltatást nyújtunk {{ companyArea }} térségében.
```

A `.kti()` metódus felcseréli a `{{ változónév }}` placeholder-eket a megfelelő értékekkel.

### 6. AI Content Generation

#### Prompt Struktúra
```yaml
extends: fields/auto-leader  # vagy auto-intro, auto-text, stb.
userPrompt: "Magyar nyelvű prompt {{ site.companyName }} változókkal"
```

#### Field Típusok
- `auto-leader`: Hero szöveg generálása
- `auto-intro`: Bevezető szöveg
- `auto-text`: Fő szövegtörzs
- `auto-description`: Rövid leírás

#### Példa Prompt (location/auto-intro.yml)
```yaml
extends: fields/auto-intro
userPrompt: "Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{ site.companyName }}, és a {{ site.companyIndustry }} iparágban tevékenykedik..."
```

### 7. Többnyelvűség

#### Nyelvi Fájlok
```
/site/languages/
└── en.php  # "en" használatban, de magyar tartalom
```

**FONTOS**: A rendszer `en.php` néven tárolja a magyar fordításokat történelmi okokból. Új telepítéshez ajánlott `hu.php` néven használni.

#### Fordítási Kulcsok
```php
'translations' => [
    'directoryLocationsTitle' => 'Itt szolgáltatunk',
    'subtitleContact' => 'Lépj Kapcsolatba',
    'footerTitleAbout' => 'Rólunk',
    // ...
]
```

#### Használat Template-ekben
```php
<?php echo t('directoryLocationsTitle') ?>
```

### 8. CSS Struktúra

#### Fő CSS Fájlok
```
/assets/css/
├── site/
│   ├── leader.css       # Hero section stílusok
│   ├── global.css       # Globális stílusok
│   └── variables.css    # CSS változók
└── layouts/
    ├── home.css         # Főoldal specifikus
    ├── locations.css    # Locations oldal
    └── services.css     # Services oldal
```

#### CSS Változók (spacing, colors)
```css
:root {
    --spacing-4: 1rem;
    --spacing-8: 2rem;
    --color-brand: #27ae60;
}
```

### 9. Képkezelés

#### Thumbnaili Definíciók
`/site/config/config.php`:
```php
'thumbs' => [
    'cover' => ['width' => 1920, 'height' => 800, 'crop' => true],
    'card' => ['width' => 800, 'height' => 600, 'crop' => true]
]
```

#### Használat
```php
<?php $thumb = $image->thumb('cover') ?>
<img src="<?= $thumb->url() ?>" alt="<?= $thumb->alt() ?>" />
```

### 10. Virtual Location Pages (CSV-based)

#### Koncepció
A `/content/locations.csv` fájl alapján **virtuális location oldalakat** generál a rendszer, anélkül hogy fizikálisan léteznének a mappák.

#### locations.csv Formátum
```csv
location,slug
Budapest,asztalos-budapest
Gödöllő,asztalos-godollo
```

#### Controller Logika
`/site/controllers/location.php` vagy routing kezeli a virtuális oldalak megjelenítését.

**Előnyök**:
- Gyors location-ök hozzáadása
- Nincs szükség manuális mappa létrehozásra
- Központi kezelés

**Hátrányok**:
- Minden location ugyanazt a template-et használja
- Nincs egyedi tartalom per location (csak AI-generált)

### 11. SEO Kezelés

#### SEO Blueprint
`/site/blueprints/seo/page.yml`:
- Meta title, description
- Open Graph mezők
- Twitter Card mezők
- Robots meta tag-ek

#### Automatikus Meta Title Template
```yaml
Metatitle: {{ companyName }} - {{ page.title }} | {{ companyArea }}
```

## Új Magyar Mikrosite Létrehozása

### Előkészítés

1. **Template letöltése**: `microsite_HUN_template/` mappa
2. **Iparágtól függő módosítások** azonosítása

### Lépések

#### 1. Alapbeállítások (`/content/site.en.txt`)

```yaml
Companyname: [Cég Neve]
Companyindustry: [Iparág - pl. Térkövezés]
Companyservices: [szolgáltatás1, szolgáltatás2, ...]
Companyarea: [Város/Régió]
Phone: [Telefonszám]
Email: [Email]
Address: [Cím]
```

#### 2. Routing Frissítése

**Fájl**: `/site/config/config.php`

Cseréld ki a routing pattern-t:
```php
'pattern' => '[új-slug-prefix]-(:any)'
```

Példák:
- `terkovezes-(:any)`
- `melegburkolo-(:any)`
- `hidegburkolo-(:any)`

#### 3. Locations.csv Frissítése

**Fájl**: `/content/locations.csv`

Módosítsd a slug oszlopot:
```csv
location,slug
Budapest,[új-prefix]-budapest
Budaörs,[új-prefix]-budaors
```

#### 4. Prompt Tartalmak Testreszabása

**Fájlok**: `/site/blueprints/prompts/*/auto-*.yml`

Ellenőrizd és szerkeszd a prompt szövegeket, hogy megfeleljenek az új iparágnak:
```yaml
userPrompt: "...a {{ site.companyIndustry }} iparágban tevékenykedik..."
```

#### 5. Példa Tartalmak (site.en.txt)

Frissítsd az alábbi mezőket az új iparágra:
- `Exampleleader`
- `Examplefaqpage`
- `Exampleservicepage`
- `Examplemission`

#### 6. Szolgáltatások Létrehozása

**Mappa**: `/content/1_szolgaltatasaink/`

Hozz létre almappákat minden szolgáltatáshoz:
- `1_szolgaltatas-neve/service.en.txt`

**service.en.txt tartalom**:
```yaml
Title: [Szolgáltatás Neve]
Leader: [Hero szöveg]
Intro: [Bevezető]
Text: [Fő tartalom]
```

#### 7. Footer és Header Menük

**site.en.txt**:
```yaml
Quicklinks: [page://id1, page://id2]
Footerlinks: [page://id1, page://id2]
```

Panel-en belül a Settings > Site Settings > Layout szekcióban állítható.

#### 8. Színek és Branding

**site.en.txt**:
```yaml
Colorbrand: #27ae60
Colortext: #ecf0f1
Colorbtn: #f1c40f
Logo: [file://id]
Favicon: [file://id]
```

#### 9. Cache Tisztítás

Minden módosítás után:
```bash
rm -rf site/cache/* site/sessions/*
```

#### 10. Teszt és Validálás

- [ ] Főoldal betöltődik
- [ ] Szolgáltatások listája megjelenik
- [ ] Location oldalak elérhetők (pl. `/terkovezes-budapest`)
- [ ] AI generálás működik
- [ ] Placeholder-ek helyettesítődnek
- [ ] Footer és menük helyesek

## Hibakeresés

### Gyakori Problémák

**1. "Invalid section type" hiba blueprint-ekben**
- **Ok**: Régi prompt formátum (`label:` és `prompt:` használata)
- **Megoldás**: Cseréld `extends:` és `userPrompt:` formátumra

**2. Placeholder nem jelenik meg, csak `{{ }}`**
- **Ok**: Hiányzik a `.kti()` hívás vagy hibás a placeholder név
- **Megoldás**:
  - Template: `<?= $site->fieldName()->kti() ?>`
  - Content: Ellenőrizd a field nevét (camelCase!)

**3. Location oldal 404-et ad**
- **Ok**: Routing nincs beállítva vagy helytelen slug
- **Megoldás**: Ellenőrizd `config.php` routing-ot és `locations.csv` slug-ot

**4. AI generálás nem működik**
- **Ok**: API kulcs hiányzik vagy prompt formátum hibás
- **Megoldás**:
  - Ellenőrizd `config.php`-ban az API kulcsot
  - Prompt fájl: `extends: fields/auto-leader` formátum

**5. CSS változások nem jelennek meg**
- **Ok**: Fingerprint plugin cache-eli a fájlokat
- **Megoldás**: `rm -rf site/cache/*`

## Karbantartás

### Rendszeres Feladatok

1. **Cache tisztítás** (heti)
2. **Biztonsági mentés** (napi)
3. **Kirby frissítés** (havonta)
4. **Plugin frissítések** (havonta)
5. **Tartalom audit** (havi)

### Monitoring

- Panel elérhetőség: `/panel`
- Matomo analytics: konfiguráld a `site.en.txt`-ben
- Server logs: `/logs/` mappa

## Biztonsági Megfontolások

1. **Panel védelem**: Erős jelszavak használata
2. **File upload**: Csak megbízható formátumok
3. **API kulcsok**: Környezeti változókban vagy `.env` fájlban
4. **HTTPS**: Mindig használj SSL tanúsítványt
5. **Backup**: Automatikus napi mentés

## Teljesítmény Optimalizálás

1. **Képek**: Használd a thumb rendszert
2. **CSS/JS**: Fingerprint plugin minifikál
3. **Cache**: Kirby beépített cache használata
4. **CDN**: Fontold meg statikus asset-ekhez
5. **PHP OPcache**: Engedélyezd a szerveren

## További Dokumentációk

- `README.en.md` - Általános áttekintés
- `ARCHITECTURE.en.md` - Rendszer architektúra (angol)
- `ROUTING.en.md` - Routing részletek
- `DEVELOPMENT.en.md` - Fejlesztői útmutató
- `SERVER_ACCESS.md` - Szerver hozzáférés
- `WARP.md` - Cloudflare WARP konfiguráció
