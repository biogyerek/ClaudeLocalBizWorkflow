# Schema.org Mapping Guide

Ez a dokumentum leírja, hogy az egyes oldaltípusokhoz milyen Schema.org markup tartozik, és hogyan töltődnek fel az adatok a `config.json`-ból.

---

## Oldaltípusok és Schema Típusok

| Oldal | Schema típus | Cél |
|-------|--------------|-----|
| Homepage | `LocalBusiness` | Fő cégadatok, szolgáltatások, területek |
| Service page | `Service` | Egyedi szolgáltatás részletei |
| Location page | `LocalBusiness` + `areaServed` | Helység-specifikus landing page |
| Services list | `ItemList` + `Service` | Szolgáltatások listája |
| Locations list | `ItemList` + `Place` | Területek listája |
| Contact | `ContactPage` | Kapcsolatfelvételi adatok |
| FAQ | `FAQPage` | Kérdés-válasz párok |
| About | `AboutPage` | Cég bemutatkozás |
| Quote request | `WebPage` + `Action` | Árajánlatkérés |

---

## Adatforrások (config.json → Schema)

```
config.json mező              →  Schema.org property
────────────────────────────────────────────────────
business.name                 →  name
business.industry             →  @type (altípus)
business.phone                →  telephone
business.email                →  email
business.address.street       →  address.streetAddress
business.address.city         →  address.addressLocality
business.address.zip          →  address.postalCode
services[].name               →  hasOfferCatalog.itemListElement[].name
services[].slug               →  hasOfferCatalog.itemListElement[].url
locations[]                   →  areaServed[].name
branding.tagline              →  slogan
```

---

## Industry → Schema @type Mapping

A `business.industry` mező alapján a `LocalBusiness` altípusa:

| Industry (config) | Schema @type |
|-------------------|--------------|
| asztalos | `Carpenter`, `HomeAndConstructionBusiness` |
| villanyszerelő | `Electrician`, `HomeAndConstructionBusiness` |
| vízszerelő | `Plumber`, `HomeAndConstructionBusiness` |
| festő | `HousePainter`, `HomeAndConstructionBusiness` |
| térkövező | `GeneralContractor`, `HomeAndConstructionBusiness` |
| tetőfedő | `RoofingContractor`, `HomeAndConstructionBusiness` |
| kertész | `Gardener`, `HomeAndConstructionBusiness` |
| lakatos | `Locksmith`, `HomeAndConstructionBusiness` |
| takarító | `CleaningService`, `LocalBusiness` |
| költöztető | `MovingCompany`, `LocalBusiness` |
| autószerelő | `AutoRepair`, `AutomotiveBusiness` |
| fodrász | `HairSalon`, `HealthAndBeautyBusiness` |
| *egyéb* | `ProfessionalService`, `LocalBusiness` |

---

## Schema Példák Oldaltípusonként

### 1. Homepage (`LocalBusiness`)

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": ["LocalBusiness", "{{schema.industry_type}}"],
  "@id": "{{site.url}}/#organization",
  "name": "{{business.name}}",
  "description": "{{meta.home.description}}",
  "slogan": "{{branding.tagline}}",
  "url": "{{site.url}}",
  "telephone": "{{business.phone}}",
  "email": "{{business.email}}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{business.address.street}}",
    "addressLocality": "{{business.address.city}}",
    "postalCode": "{{business.address.zip}}",
    "addressCountry": "HU"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "{{business.geo.lat}}",
    "longitude": "{{business.geo.lng}}"
  },
  "areaServed": [
    {{#each locations}}
    {
      "@type": "City",
      "name": "{{this.name}}"
    }{{#unless @last}},{{/unless}}
    {{/each}}
  ],
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Szolgáltatásaink",
    "itemListElement": [
      {{#each services}}
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "{{this.name}}",
          "url": "{{../site.url}}/{{this.slug}}/"
        }
      }{{#unless @last}},{{/unless}}
      {{/each}}
    ]
  },
  "priceRange": "$$",
  "currenciesAccepted": "HUF",
  "paymentAccepted": "Készpénz, Átutalás",
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
      "opens": "08:00",
      "closes": "17:00"
    }
  ],
  "sameAs": [
    "{{social.facebook}}",
    "{{social.instagram}}"
  ]
}
</script>
```

---

### 2. Service Page (`Service`)

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "@id": "{{site.url}}/{{service.slug}}/#service",
  "name": "{{service.name}}",
  "description": "{{service.meta_description}}",
  "url": "{{site.url}}/{{service.slug}}/",
  "provider": {
    "@type": "LocalBusiness",
    "@id": "{{site.url}}/#organization",
    "name": "{{business.name}}",
    "telephone": "{{business.phone}}"
  },
  "areaServed": [
    {{#each locations}}
    {
      "@type": "City",
      "name": "{{this.name}}"
    }{{#unless @last}},{{/unless}}
    {{/each}}
  ],
  "serviceType": "{{service.name}}",
  "category": "{{business.industry}}",
  "availableChannel": {
    "@type": "ServiceChannel",
    "serviceUrl": "{{site.url}}/ajanlatkeres/",
    "servicePhone": "{{business.phone}}",
    "serviceSmsNumber": "{{business.phone}}"
  },
  "termsOfService": "{{site.url}}/aszf/",
  "offers": {
    "@type": "Offer",
    "availability": "https://schema.org/InStock",
    "priceSpecification": {
      "@type": "PriceSpecification",
      "priceCurrency": "HUF",
      "price": "Árajánlat alapján"
    }
  }
}
</script>
```

---

### 3. Location Page (`LocalBusiness` + specific area)

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": ["LocalBusiness", "{{schema.industry_type}}"],
  "@id": "{{site.url}}/{{location.slug}}/#localbusiness",
  "name": "{{business.name}} - {{location.name}}",
  "description": "{{location.meta_description}}",
  "url": "{{site.url}}/{{location.slug}}/",
  "telephone": "{{business.phone}}",
  "email": "{{business.email}}",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "{{location.name}}",
    "addressRegion": "{{location.region}}",
    "addressCountry": "HU"
  },
  "areaServed": {
    "@type": "City",
    "name": "{{location.name}}"
  },
  "parentOrganization": {
    "@type": "LocalBusiness",
    "@id": "{{site.url}}/#organization"
  },
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Szolgáltatásaink {{location.name}} területén",
    "itemListElement": [
      {{#each services}}
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "{{this.name}}"
        }
      }{{#unless @last}},{{/unless}}
      {{/each}}
    ]
  },
  "makesOffer": [
    {{#each services}}
    {
      "@type": "Offer",
      "itemOffered": {
        "@type": "Service",
        "name": "{{this.name}} {{../location.name}}"
      }
    }{{#unless @last}},{{/unless}}
    {{/each}}
  ]
}
</script>
```

---

### 4. Services List (`ItemList`)

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "{{business.name}} - Szolgáltatások",
  "description": "Teljes szolgáltatás kínálatunk",
  "url": "{{site.url}}/szolgaltatasaink/",
  "numberOfItems": {{services.length}},
  "itemListElement": [
    {{#each services}}
    {
      "@type": "ListItem",
      "position": {{@index_1}},
      "name": "{{this.name}}",
      "url": "{{../site.url}}/{{this.slug}}/"
    }{{#unless @last}},{{/unless}}
    {{/each}}
  ]
}
</script>
```

---

### 5. Locations List (`ItemList`)

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "{{business.name}} - Szolgáltatási területek",
  "description": "Területek ahol szolgáltatásainkat nyújtjuk",
  "url": "{{site.url}}/szolgaltatasi-teruletek/",
  "numberOfItems": {{locations.length}},
  "itemListElement": [
    {{#each locations}}
    {
      "@type": "ListItem",
      "position": {{@index_1}},
      "name": "{{this.name}}",
      "url": "{{../site.url}}/{{../business.industry_slug}}-{{this.slug}}/"
    }{{#unless @last}},{{/unless}}
    {{/each}}
  ]
}
</script>
```

---

### 6. Contact Page (`ContactPage`)

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Kapcsolat - {{business.name}}",
  "description": "Vegye fel velünk a kapcsolatot",
  "url": "{{site.url}}/kapcsolat/",
  "mainEntity": {
    "@type": "LocalBusiness",
    "@id": "{{site.url}}/#organization",
    "name": "{{business.name}}",
    "telephone": "{{business.phone}}",
    "email": "{{business.email}}",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "{{business.address.street}}",
      "addressLocality": "{{business.address.city}}",
      "postalCode": "{{business.address.zip}}",
      "addressCountry": "HU"
    },
    "contactPoint": {
      "@type": "ContactPoint",
      "telephone": "{{business.phone}}",
      "contactType": "customer service",
      "availableLanguage": "Hungarian",
      "areaServed": "HU"
    }
  }
}
</script>
```

---

### 7. FAQ Page (`FAQPage`)

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "name": "Gyakran Ismételt Kérdések - {{business.name}}",
  "url": "{{site.url}}/gyik/",
  "mainEntity": [
    {{#each faq}}
    {
      "@type": "Question",
      "name": "{{this.question}}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{this.answer}}"
      }
    }{{#unless @last}},{{/unless}}
    {{/each}}
  ]
}
</script>
```

**FAQ adatok forrása:**
- Phase 2-ben generálva az LLM által
- Iparág-specifikus kérdések (pl. asztalos: "Mennyi idő egy konyhabútor készítése?")
- Tárolva: `generated/faq.json`

---

### 8. About Page (`AboutPage`)

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "name": "Rólunk - {{business.name}}",
  "url": "{{site.url}}/rolunk/",
  "mainEntity": {
    "@type": "LocalBusiness",
    "@id": "{{site.url}}/#organization",
    "name": "{{business.name}}",
    "description": "{{about.intro}}",
    "foundingDate": "{{business.founding_year}}",
    "slogan": "{{branding.tagline}}"
  }
}
</script>
```

---

### 9. Quote Request Page (`WebPage` + `Action`)

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Árajánlatkérés - {{business.name}}",
  "url": "{{site.url}}/ajanlatkeres/",
  "description": "Kérjen ingyenes árajánlatot szolgáltatásainkra",
  "potentialAction": {
    "@type": "QuoteAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "{{site.url}}/ajanlatkeres/",
      "actionPlatform": [
        "https://schema.org/DesktopWebPlatform",
        "https://schema.org/MobileWebPlatform"
      ]
    },
    "result": {
      "@type": "Order",
      "orderStatus": "https://schema.org/OrderProcessing"
    }
  },
  "mainEntity": {
    "@type": "LocalBusiness",
    "@id": "{{site.url}}/#organization"
  }
}
</script>
```

---

## BreadcrumbList (minden oldalon)

Minden oldal tartalmaz breadcrumb schema-t:

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Kezdőlap",
      "item": "{{site.url}}/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "{{current_page.parent.name}}",
      "item": "{{site.url}}/{{current_page.parent.slug}}/"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{current_page.name}}",
      "item": "{{site.url}}/{{current_page.slug}}/"
    }
  ]
}
</script>
```

---

## Implementációs Logika

### Phase 3: Template Population

```
1. Oldaltípus meghatározása (home, service, location, etc.)
2. Schema template kiválasztása az oldaltípus alapján
3. Változók behelyettesítése config.json + generated/*.json alapján
4. Schema JSON beillesztése a <head> szekcióba
5. BreadcrumbList generálása az oldal pozíciója alapján
```

### Schema Validáció

Generálás után ellenőrzés:
- Google Rich Results Test kompatibilitás
- JSON-LD szintaxis helyesség
- Kötelező mezők megléte

---

## Hiányzó Adatok Kezelése

| Mező | Ha hiányzik | Fallback |
|------|-------------|----------|
| `geo.lat/lng` | Kihagyja a geo blokkot | - |
| `social.facebook` | Kihagyja a sameAs-ból | - |
| `business.founding_year` | Kihagyja | - |
| `openingHours` | Alapértelmezett H-P 8-17 | Konfigurálható |

---

## Tesztelés

Generálás után teszteld:
1. [Google Rich Results Test](https://search.google.com/test/rich-results)
2. [Schema.org Validator](https://validator.schema.org/)
3. Chrome DevTools → Elements → keress `application/ld+json`-ra

---

**Verzió:** 1.0
**Utolsó frissítés:** 2024-12-18
