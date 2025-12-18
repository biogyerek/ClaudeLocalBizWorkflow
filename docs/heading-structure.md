# Heading Structure Rules (H1-H3)

A heading-ek hierarchiája kritikus SEO szempontból. A keresők ezekből értik meg az oldal struktúráját és fő témáját.

---

## Alapszabályok

| Szabály | Leírás |
|---------|--------|
| **Egy H1 per oldal** | Soha ne legyen több H1 egy oldalon |
| **Hierarchia** | H1 → H2 → H3 (ne ugorj szintet) |
| **H1 = Fő kulcsszó** | Az oldal célzott kulcsszava |
| **H1 ≠ Title** | Hasonló, de nem kell azonos |
| **Ne használd design-ra** | Heading = struktúra, nem formázás |

---

## Fő Kulcsszó Logika

A heading-ekben a **fő kulcsszót** használd, ami lehet:

1. `seo.primary_keyword` - ha meg van adva (pl. "vinyl padló lerakás")
2. `business.industry` - ha nincs primary_keyword (pl. "padlóburkoló")

**Példa különbségre:**

| Mező | Érték |
|------|-------|
| `business.industry` | padlóburkoló |
| `seo.primary_keyword` | vinyl padló lerakás |
| **Homepage H1** | Vinyl Padló Lerakás Budapest |
| **NEM** | ~~Padlóburkoló Budapest~~ |

---

## Oldaltípusonkénti H1 Formátumok

### Homepage

```
H1: [Fő kulcsszó] [Város]
```

**Példák:**
- `Vinyl Padló Lerakás Budapest`
- `Konyhabútor Készítés Budapest`
- `Asztalos Budapest` (ha nincs primary_keyword)

### Service Page

```
H1: [Szolgáltatás neve] [Város]
```

**Példák:**
- `Laminált Padló Lerakás Budapest`
- `Beépített Szekrény Készítés`
- `Gardróbszekrény Egyedi Méretben`

### Location Page

```
H1: [Fő kulcsszó] [Település]
```

**Példák:**
- `Vinyl Padló Lerakás Budaörs`
- `Konyhabútor Készítés Érd`
- `Asztalos Budapest 2. Kerület`

### About Page

```
H1: [Cégnév] - Bemutatkozás
    VAGY
H1: Rólunk
```

### Contact Page

```
H1: Kapcsolat
    VAGY
H1: Elérhetőségeink
```

### FAQ Page

```
H1: Gyakran Ismételt Kérdések
    VAGY
H1: [Fő kulcsszó] - GYIK
```

---

## Teljes Heading Struktúra Oldaltípusonként

### Homepage Struktúra

```
H1: Vinyl Padló Lerakás Budapest
│
├── H2: Szolgáltatásaink
│   ├── H3: Vinyl padló lerakás
│   ├── H3: Laminált padló lerakás
│   ├── H3: SPC padló lerakás
│   └── H3: Szőnyegpadló lerakás
│
├── H2: Miért válasszon minket?
│   ├── H3: Tapasztalt szakemberek
│   ├── H3: Garancia minden munkára
│   └── H3: Ingyenes felmérés
│
├── H2: Szolgáltatási területeink
│
├── H2: Referenciáink
│
├── H2: Gyakori kérdések
│   ├── H3: Mennyi idő a lerakás?
│   ├── H3: Milyen padlót válasszak?
│   └── H3: Mennyibe kerül?
│
└── H2: Kapcsolat / Árajánlatkérés
```

### Service Page Struktúra

```
H1: Vinyl Padló Lerakás Budapest
│
├── H2: Mi a vinyl padló?
│
├── H2: Vinyl padló előnyei
│   ├── H3: Vízállóság
│   ├── H3: Tartósság
│   └── H3: Könnyű karbantartás
│
├── H2: Vinyl padló típusok
│   ├── H3: Kattintós vinyl
│   ├── H3: Ragasztós vinyl
│   └── H3: SPC vinyl
│
├── H2: Árak és költségek
│
├── H2: Lerakás menete
│   ├── H3: Felmérés
│   ├── H3: Aljzatelőkészítés
│   └── H3: Lerakás
│
├── H2: Gyakori kérdések
│   ├── H3: Mennyi idő a lerakás?
│   └── H3: Kell aljzatkiegyenlítés?
│
└── H2: Kérjen árajánlatot
```

### Location Page Struktúra

```
H1: Vinyl Padló Lerakás Budaörs
│
├── H2: Padlóburkolás Budaörsön
│
├── H2: Szolgáltatásaink Budaörsön
│   ├── H3: Vinyl padló lerakás
│   ├── H3: Laminált padló lerakás
│   └── H3: Padlócsiszolás
│
├── H2: Miért válasszon minket Budaörsön?
│
├── H2: Környező települések
│
└── H2: Kapcsolatfelvétel
```

### About Page Struktúra

```
H1: Rólunk - PadlóMester Budapest
│
├── H2: Történetünk
│
├── H2: Küldetésünk
│
├── H2: Csapatunk
│   ├── H3: [Név 1] - Ügyvezető
│   ├── H3: [Név 2] - Szakvezető
│   └── H3: [Név 3] - Padlóburkoló
│
├── H2: Értékeink
│
└── H2: Partnereink
```

### FAQ Page Struktúra

```
H1: Gyakran Ismételt Kérdések - Padlóburkolás
│
├── H2: Általános kérdések
│   ├── H3: Milyen padlót válasszak?
│   ├── H3: Mennyi ideig tart a lerakás?
│   └── H3: Mennyibe kerül a padlóburkolás?
│
├── H2: Vinyl padlóval kapcsolatos kérdések
│   ├── H3: Vízálló a vinyl padló?
│   └── H3: Padlófűtésre alkalmas?
│
├── H2: Megrendeléssel kapcsolatos kérdések
│   ├── H3: Hogyan kérjek árajánlatot?
│   └── H3: Van garancia?
│
└── H2: Nincs itt a kérdésed?
```

---

## H2 és H3 Generálási Szabályok

### H2 - Fő szekciók

A `prompts/*/text.md` promptokban:

```markdown
Generálj tartalmat a következő H2 szekciókkal:

{{#if page.type == "service"}}
- Mi a {{service.name}}?
- {{service.name}} előnyei
- Árak és költségek
- Gyakori kérdések
- Kérjen árajánlatot
{{/if}}

{{#if page.type == "location"}}
- {{seo.primary_keyword}} {{location.name}} területén
- Szolgáltatásaink
- Miért válasszon minket?
- Kapcsolatfelvétel
{{/if}}
```

### H3 - Alszekciók

A H3 szekciók tartalomfüggőek:
- **Szolgáltatások listája** → H3 minden szolgáltatáshoz
- **Előnyök listája** → H3 minden előnyhöz
- **FAQ szekció** → H3 minden kérdéshez
- **Csapat** → H3 minden csapattaghoz

---

## Hibák, amiket kerülni kell

| Hiba | Miért rossz | Helyes |
|------|-------------|--------|
| Több H1 | Zavaró a keresőknek | Egy H1 per oldal |
| H1 → H3 (H2 nélkül) | Hibás hierarchia | H1 → H2 → H3 |
| H1 = cégnév | Nem kulcsszó fókuszált | H1 = fő kulcsszó + hely |
| Minden H2 | Nincs struktúra | Vegyes H2, H3 |
| `<h2>` design-ra | Szemantikailag hibás | CSS class használata |

**Rossz példa:**
```html
<h1>PadlóMester Kft.</h1>           <!-- Nincs kulcsszó -->
<h3>Szolgáltatásaink</h3>           <!-- Ugrott H2 -->
<h2 class="big-text">Akció!</h2>    <!-- Design, nem struktúra -->
```

**Helyes példa:**
```html
<h1>Vinyl Padló Lerakás Budapest</h1>
<h2>Szolgáltatásaink</h2>
<h3>Vinyl padló lerakás</h3>
<h3>Laminált padló lerakás</h3>
<p class="big-text highlight">Akció!</p>  <!-- CSS a design-hoz -->
```

---

## Prompt Integráció

A `prompts/*/text.md` fájlokban add hozzá:

```markdown
## Heading szabályok

- Használj H2 címeket a fő szekciókhoz
- Használj H3 címeket az alszekciókhoz
- NE használj H1-et (az a leader-ben van)
- Hierarchia: H2 → H3 (ne ugorj szintet)
- Minden H2/H3 legyen informatív, ne csak "Tovább" vagy "Részletek"
```

---

## SEO Ellenőrzés

Generálás után ellenőrizd:

- [ ] Pontosan 1 db H1 van az oldalon
- [ ] H1 tartalmazza a fő kulcsszót
- [ ] Nincs szint ugrás (H1→H3)
- [ ] H2/H3 szekciók logikus struktúrát alkotnak
- [ ] Heading-ek nem üresek
- [ ] Heading-ek nem túl hosszúak (max ~70 karakter)

---

**Verzió:** 1.0
**Utolsó frissítés:** 2024-12-18
