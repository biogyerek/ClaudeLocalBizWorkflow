# SEO Implementation Guide

Ez a dokumentum a technikai SEO elemeket tartalmazza, amik nem fértek bele a schema-mapping.md-be.

---

## 1. Meta Tags

### Title Tag

**Formátum oldaltípusonként:**

| Oldal | Title formátum | Példa |
|-------|----------------|-------|
| Homepage | `[Industry] [City] \| [Brand]` | `Asztalos Budapest \| Asztalosmester` |
| Service | `[Service] [City] \| [Brand]` | `Konyhabútor Készítés \| Asztalosmester` |
| Location | `[Industry] [Location] \| [Brand]` | `Asztalos Budaörs \| Asztalosmester` |
| About | `Rólunk - [Brand]` | `Rólunk - Asztalosmester Budapest` |
| Contact | `Kapcsolat \| [Brand]` | `Kapcsolat \| Asztalosmester` |
| FAQ | `Gyakori Kérdések \| [Brand]` | `Gyakori Kérdések \| Asztalosmester` |

**Szabályok:**
- Max 60 karakter (Google levágja)
- Fő kulcsszó elöl
- Brand név végén
- Egyedi minden oldalon

**HTML:**
```html
<title>{{meta.title}}</title>
```

### Meta Description

- Max 155-160 karakter
- Cselekvésre ösztönző (CTA)
- Tartalmazza a fő kulcsszót
- Promptok: `prompts/meta/`

**HTML:**
```html
<meta name="description" content="{{meta.description}}">
```

---

## 2. Open Graph Tags (Facebook/Social)

Social media megosztáskor megjelenő preview.

**Kötelező tagek:**

```html
<!-- Open Graph -->
<meta property="og:type" content="{{og.type}}">
<meta property="og:title" content="{{meta.title}}">
<meta property="og:description" content="{{meta.description}}">
<meta property="og:url" content="{{page.canonical_url}}">
<meta property="og:image" content="{{site.url}}/assets/images/og-image.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="{{business.name}}">
<meta property="og:locale" content="hu_HU">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{meta.title}}">
<meta name="twitter:description" content="{{meta.description}}">
<meta name="twitter:image" content="{{site.url}}/assets/images/og-image.jpg">
```

**og:type értékek:**

| Oldal | og:type |
|-------|---------|
| Homepage | `website` |
| Service | `website` |
| Location | `website` |
| About | `profile` |
| Contact | `website` |
| FAQ | `website` |

**OG Image követelmények:**
- Méret: 1200x630px
- Formátum: JPG vagy PNG
- Max 8MB
- Elhelyezés: `/assets/images/og-image.jpg`
- Tartalom: Logo + cégnév + szlogen

---

## 3. Canonical URLs

A canonical URL megmondja a keresőknek, melyik az "eredeti" oldal.

**Miért fontos:**
- Elkerüli a duplicate content büntetést
- www vs non-www
- HTTP vs HTTPS
- Trailing slash konzisztencia

**HTML:**
```html
<link rel="canonical" href="{{page.canonical_url}}">
```

**Canonical URL szabályok:**

| Elérési út | Canonical URL |
|------------|---------------|
| `https://example.hu/szolgaltatas/` | `https://example.hu/szolgaltatas/` |
| `https://www.example.hu/szolgaltatas/` | `https://example.hu/szolgaltatas/` |
| `https://example.hu/szolgaltatas` | `https://example.hu/szolgaltatas/` |
| `https://example.hu/szolgaltatas/index.html` | `https://example.hu/szolgaltatas/` |

**Generálási logika:**
```
1. Mindig HTTPS
2. Soha nem www (vagy mindig www - konzisztens)
3. Mindig trailing slash (/szolgaltatas/)
4. Soha nem index.html
```

**config.json beállítás:**
```json
{
  "site": {
    "url": "https://asztalosmesterbudapest.hu",
    "use_www": false,
    "trailing_slash": true
  }
}
```

---

## 4. Image Alt Texts

Az alt text fontos az accessibility és image SEO szempontjából.

**Generálási szabályok:**

| Kép típus | Alt text formátum | Példa |
|-----------|-------------------|-------|
| Hero | `[Industry] [City] - [Business]` | `Asztalos Budapest - Asztalosmester munkában` |
| Service | `[Service] - [Business]` | `Konyhabútor készítés - egyedi tervezés` |
| Location | `[Service] [Location]` | `Asztalos szolgáltatás Budaörsön` |
| Logo | `[Business] logo` | `Asztalosmester Budapest logo` |
| Team | `[Name] - [Position]` | `Kovács János - ügyvezető` |
| Gallery | `[Description] - [Business]` | `Egyedi konyhabútor referencia` |

**Szabályok:**
- Max 125 karakter
- Leíró, nem kulcsszó spam
- Ne kezdje "kép" vagy "fotó" szóval
- Tartalmazza a kontextust

**HTML:**
```html
<img src="{{image.src}}"
     alt="{{image.alt}}"
     width="{{image.width}}"
     height="{{image.height}}"
     loading="lazy">
```

**Placeholder alt text (Phase 3-ban cserélendő):**
```html
<img src="/assets/images/placeholder-hero.jpg"
     alt="{{business.industry}} {{business.address.city}} - {{business.name}}"
     data-replace="hero">
```

---

## 5. Heading Hierarchy (H1-H6)

### Szabályok

1. **Egy H1 per oldal** - a fő cím
2. **Hierarchia betartása** - H1 → H2 → H3 (ne ugorj szintet)
3. **H1 = Fő kulcsszó** - amit az oldal céloz
4. **H2 = Szekciók** - fő tartalmi blokkok
5. **H3 = Alszekciók** - részletek

### Oldaltípusok struktúrája

**Homepage:**
```
H1: Asztalos Budapest - Egyedi Bútorok Készítése
├── H2: Szolgáltatásaink
│   ├── H3: Konyhabútor készítés
│   ├── H3: Beépített szekrények
│   └── H3: Gardróbok
├── H2: Miért válasszon minket?
├── H2: Szolgáltatási területeink
├── H2: Referenciáink
└── H2: Kapcsolat
```

**Service page:**
```
H1: Konyhabútor Készítés Budapest
├── H2: Modern konyhabútorok egyedi méretben
├── H2: Anyagok és kivitelezés
│   ├── H3: Faanyagok
│   ├── H3: Vasalatok
│   └── H3: Munkalapok
├── H2: Konyhabútor árak
├── H2: Gyakori kérdések
│   ├── H3: Mennyi idő a gyártás?
│   └── H3: Van garancia?
└── H2: Kérjen árajánlatot
```

**Location page:**
```
H1: Asztalos Budaörs - Bútorkészítés
├── H2: Szolgáltatásaink Budaörsön
│   ├── H3: Konyhabútor
│   ├── H3: Beépített szekrény
│   └── H3: Gardróbszekrény
├── H2: Miért válasszon minket?
├── H2: Budaörs és környéke
└── H2: Kapcsolatfelvétel
```

### H1 Prompt szabályok

A `prompts/*/leader.md` fájlokban a H1 generáláshoz:

```markdown
Az oldal H1 címe legyen:
- Maximum 60 karakter
- Tartalmazza: {{business.industry}} + {{location.name}} VAGY {{service.name}}
- Ne ismételje más oldalak H1-ét
- Természetes, olvasható
```

---

## 6. Internal Linking Strategy

### Link Struktúra Áttekintés

```
                              ┌─────────────┐
                              │  HOMEPAGE   │
                              └──────┬──────┘
                                     │
        ┌────────────────────────────┼────────────────────────────┐
        │                            │                            │
        ▼                            ▼                            ▼
┌───────────────┐          ┌─────────────────┐          ┌─────────────┐
│ Szolgáltatások│          │ Service Pages   │          │ Alap oldalak│
│    (list)     │          │ (4 db)          │          │             │
└───────┬───────┘          └────────┬────────┘          └─────────────┘
        │                           │                   - Rólunk
        │                           │                   - Kapcsolat
        ▼                           │                   - GYIK
┌───────────────┐                   │                   - Ajánlatkérés
│ Location Pages│◄──────────────────┘
│ (48 db)       │
└───────────────┘
```

---

### 1. Fő Menü (minden oldalon)

```html
<nav class="main-menu">
  <a href="/">Főoldal</a>
  <a href="/szolgaltatasaink/">Szolgáltatások</a>
  <a href="/rolunk/">Rólunk</a>
  <a href="/kapcsolat/">Kapcsolat</a>
  <a href="/ajanlatkeres/">Árajánlat</a>
</nav>
```

---

### 2. Homepage - Tartalmi Linkek

A főoldal H2 címsorai linkelnek a szolgáltatás aloldalakra:

```html
<section class="services-preview">
  <h2><a href="/konyhabutor-keszites/">Konyhabútor készítés</a></h2>
  <p>Egyedi konyhabútorok tervezése és gyártása...</p>

  <h2><a href="/beepitett-szekreny/">Beépített szekrény</a></h2>
  <p>Méretre készített beépített szekrények...</p>

  <h2><a href="/gardrobszekreny/">Gardróbszekrény</a></h2>
  <p>Modern gardróbszekrények egyedi méretben...</p>

  <h2><a href="/egyedi-butorok/">Egyedi bútorok</a></h2>
  <p>Bármilyen egyedi elképzelés megvalósítása...</p>
</section>
```

**Prompt szabály a homepage text generáláshoz:**
```
A szolgáltatások H2 címei legyenek linkek a megfelelő szolgáltatás aloldalra.
Formátum: <h2><a href="/{{service.slug}}/">{{service.name}}</a></h2>
```

---

### 3. Szolgáltatások Oldal → Location Oldalak

A szolgáltatások lista oldal (`/szolgaltatasaink/`) tartalmazza az összes location linket:

```html
<section class="service-areas">
  <h2>Szolgáltatási területeink</h2>
  <ul class="locations-list">
    {{#each locations}}
    <li><a href="/{{../seo.primary_keyword_slug}}-{{this.slug}}/">
      {{../seo.primary_keyword}} {{this.name}}
    </a></li>
    {{/each}}
  </ul>
</section>
```

**Példa output:**
```html
<ul class="locations-list">
  <li><a href="/vinyl-padlo-lerakas-budapest-1-kerulet/">Vinyl padló lerakás Budapest 1. kerület</a></li>
  <li><a href="/vinyl-padlo-lerakas-budapest-2-kerulet/">Vinyl padló lerakás Budapest 2. kerület</a></li>
  ...
  <li><a href="/vinyl-padlo-lerakas-budaors/">Vinyl padló lerakás Budaörs</a></li>
  <li><a href="/vinyl-padlo-lerakas-erd/">Vinyl padló lerakás Érd</a></li>
</ul>
```

---

### 4. Footer (minden oldalon)

```html
<footer>
  <!-- Szolgáltatások szekció -->
  <div class="footer-services">
    <h4>Szolgáltatásaink</h4>
    <ul>
      {{#each services}}
      <li><a href="/{{this.slug}}/">{{this.name}}</a></li>
      {{/each}}
    </ul>
  </div>

  <!-- Jogi linkek szekció -->
  <div class="footer-legal">
    <ul>
      <li><a href="/kapcsolat/">Kapcsolat</a></li>
      <li><a href="/aszf/">ÁSZF</a></li>
      <li><a href="/adatvedelmi-nyilatkozat/">Adatvédelmi nyilatkozat</a></li>
    </ul>
  </div>
</footer>
```

**Példa footer output:**
```html
<footer>
  <div class="footer-services">
    <h4>Szolgáltatásaink</h4>
    <ul>
      <li><a href="/konyhabutor-keszites/">Konyhabútor készítés</a></li>
      <li><a href="/beepitett-szekreny/">Beépített szekrény</a></li>
      <li><a href="/gardrobszekreny/">Gardróbszekrény</a></li>
      <li><a href="/egyedi-butorok/">Egyedi bútorok</a></li>
    </ul>
  </div>

  <div class="footer-legal">
    <ul>
      <li><a href="/kapcsolat/">Kapcsolat</a></li>
      <li><a href="/aszf/">ÁSZF</a></li>
      <li><a href="/adatvedelmi-nyilatkozat/">Adatvédelmi nyilatkozat</a></li>
    </ul>
  </div>
</footer>
```

---

### 5. Link Mátrix Összefoglaló

| Forrás oldal | Linkel ide | Hol |
|--------------|------------|-----|
| **Minden oldal** | Főoldal, Szolgáltatások, Rólunk, Kapcsolat, Ajánlat | Menü |
| **Minden oldal** | 4 szolgáltatás aloldal | Footer |
| **Minden oldal** | Kapcsolat, ÁSZF, Adatvédelem | Footer (legal) |
| **Homepage** | 4 szolgáltatás aloldal | H2 címsorok (tartalom) |
| **Szolgáltatások oldal** | Összes location oldal (48+) | Lista szekció |
| **Service page** | Kapcsolódó location oldalak | Tartalom |
| **Location page** | Szolgáltatás aloldalak | Tartalom |

---

### 6. Anchor Text Szabályok

| Link típus | Anchor text | Példa |
|------------|-------------|-------|
| Service link | Szolgáltatás neve | "Konyhabútor készítés" |
| Location link | [Kulcsszó] [Hely] | "Vinyl padló lerakás Budaörs" |
| Legal link | Oldal neve | "ÁSZF", "Adatvédelmi nyilatkozat" |
| CTA link | Cselekvésre ösztönző | "Kérjen árajánlatot" |

**Kerülendő:**
- "Kattintson ide"
- "Tovább"
- "Itt"
- Túl hosszú anchor text

---

## 7. Additional SEO Elements

### Robots Meta

```html
<!-- Indexelhető oldalak -->
<meta name="robots" content="index, follow">

<!-- Nem indexelhető (pl. köszönöm oldal) -->
<meta name="robots" content="noindex, nofollow">
```

### Language Declaration

```html
<html lang="hu">
```

### Viewport (Mobile)

```html
<meta name="viewport" content="width=device-width, initial-scale=1">
```

### Favicon

```html
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
```

---

## 8. Sitemap.xml Részletek

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>{{site.url}}/</loc>
    <lastmod>{{build_date}}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>{{site.url}}/szolgaltatasaink/</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  {{#each services}}
  <url>
    <loc>{{../site.url}}/{{this.slug}}/</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  {{/each}}
  {{#each locations}}
  <url>
    <loc>{{../site.url}}/{{../industry}}-{{this.slug}}/</loc>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
  {{/each}}
</urlset>
```

**Priority értékek:**
| Oldal típus | Priority |
|-------------|----------|
| Homepage | 1.0 |
| Services list | 0.8 |
| Service pages | 0.8 |
| Locations list | 0.7 |
| Location pages | 0.6 |
| About, Contact | 0.5 |
| Legal pages | 0.3 |

---

## 9. SEO Checklist (Phase 4)

Generálás után ellenőrizd:

- [ ] Minden oldal egyedi title
- [ ] Minden oldal egyedi meta description
- [ ] Egy H1 per oldal
- [ ] Canonical URL helyes
- [ ] OG image létezik
- [ ] Alt text minden képen
- [ ] Internal linkek működnek
- [ ] Sitemap.xml tartalmazza az összes oldalt
- [ ] Robots.txt helyes
- [ ] Schema.org markup valid (Google test)
- [ ] Mobile-friendly (Google test)
- [ ] Page speed elfogadható

---

**Verzió:** 1.0
**Utolsó frissítés:** 2024-12-18
