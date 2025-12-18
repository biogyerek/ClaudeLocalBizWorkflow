# Claude Code Workflow Instructions

Ez a fájl utasításokat tartalmaz Claude Code számára a local business website generálásához.

---

## Workflow Áttekintés

```
Phase 1: Configuration  →  config.json
Phase 2: Generation     →  generated/*.json
Phase 3: Build          →  output/*/index.html
Phase 4: Deploy         →  GitHub Pages
```

---

## Phase 1: Configuration Collection

### 1.1 Környezet ellenőrzése

Ellenőrizd, hogy létezik-e `.env` fájl:
- Ha nem: kérd meg a usert, hogy másolja `.env.example`-t `.env`-be és adja meg az API kulcsot
- Ha igen: olvasd be és validáld

### 1.2 Kérdések sorrendje

Kérdezd meg a usertől az alábbiakat ebben a sorrendben:

1. **LLM Provider**
   ```
   Melyik LLM provider-t szeretnéd használni?
   1. OpenAI (gpt-4o-mini) - Ajánlott
   2. Google Gemini (gemini-2.0-flash)
   ```
   Validáld, hogy a megfelelő API kulcs létezik a `.env`-ben.

2. **Business name**
   ```
   Mi a vállalkozás neve?
   ```

3. **Industry**
   ```
   Mi az iparág? (pl. asztalos, villanyszerelő, padlóburkoló)
   ```
   Generálj slug-ot: "Padlóburkoló" → "padlobur kolo"

4. **Primary keyword** (opcionális)
   ```
   Mi a fő kulcsszó, amire optimalizálni szeretnéd? (opcionális)
   Ez lehet specifikusabb mint az iparág.
   Pl. iparág: 'padlóburkoló', fő kulcsszó: 'vinyl padló lerakás'
   Ha kihagyod, az iparágat használjuk.
   ```

5. **Services** (minimum 4)
   ```
   Milyen szolgáltatásokat kínálsz? (adj meg legalább 4-et)
   Pl. Konyhabútor készítés, Beépített szekrény, stb.
   ```
   Minden szolgáltatáshoz generálj slug-ot.

6. **Phone**
   ```
   Mi a telefonszám? (pl. +36301234567)
   ```

7. **Email**
   ```
   Mi az email cím?
   ```

8. **Address**
   ```
   Mi a cím? (utca, város, irányítószám)
   ```

9. **Brand color**
   ```
   Mi a fő márka szín? (hex kód, pl. #8B4513)
   ```

10. **Tagline**
    ```
    Mi a szlogen/tagline?
    ```

11. **Domain knowledge** (opcionális)
    ```
    Van-e speciális szakterületi tudás, amit szeretnél megadni? (opcionális)
    Pl. anyagok, technikák, márkák, szabványok.
    Ha kihagyod, az LLM a saját tudását használja.
    ```

### 1.3 Config mentése

Mentsd a válaszokat `config.json`-ba a dokumentált struktúra szerint.

### 1.4 Összefoglaló és megerősítés

Mutasd meg az összefoglalót és kérj megerősítést:
```
=== Konfiguráció összefoglaló ===
Cégnév: ...
Iparág: ...
Fő kulcsszó: ...
Szolgáltatások: ...
...

Folytatjuk a tartalomgenerálással? (igen/nem)
```

---

## Phase 2: Content Generation

### 2.1 Előkészítés

1. Olvasd be a `config.json`-t
2. Olvasd be a `locations.csv`-t (vagy `locations_template.csv`-t)
3. Számold ki a generálandó tartalmak számát

### 2.2 Generálási sorrend

```
1. Site-level content (4 db)
   - site/backstory
   - site/mission
   - site/tagline
   - site/contact-text

2. Homepage (2 db)
   - home/leader
   - home/text

3. Services list page (3 db)
   - services/leader
   - services/intro
   - services/text

4. Service pages (4 szolgáltatás × 4 = 16 db)
   - service/leader
   - service/intro
   - service/description
   - service/text

5. Locations list page (3 db)
   - locations/leader
   - locations/intro
   - locations/text

6. Location pages (48 helyszín × 4 = 192 db)
   - location/leader
   - location/intro
   - location/description
   - location/text

7. Other pages (8 db)
   - about/leader, intro, text
   - contact/leader, text
   - faq/leader, text
   - estimate/leader, text

8. Meta content (minden oldalhoz title + description)
```

### 2.3 Rate limiting

- Olvasd be `API_RATE_LIMIT_MS`-t a `.env`-ből (default: 500ms)
- Várj ennyi időt minden API hívás között
- Retry logika: max 3 próbálkozás exponenciális backoff-fal

### 2.4 Progress display

```
=== Tartalom generálás ===
LLM: OpenAI (gpt-4o-mini)
Rate limit: 500ms

[1/228] Homepage leader...           ✓
[2/228] Homepage text...             ✓
[3/228] Service: Konyhabútor...      ⏳ Generálás...
[4/228] Service: Beépített...        ○ Várakozik

Progress: ████░░░░░░░░░░░░░░░░ 8%
Becsült hátralévő idő: ~15 perc
```

### 2.5 Content storage

Mentsd a generált tartalmat `generated/` mappába:
```
generated/
├── site.json
├── home.json
├── services.json
├── services/
│   ├── konyhabutor-keszites.json
│   └── ...
├── locations.json
├── locations/
│   ├── budapest-1-kerulet.json
│   └── ...
├── about.json
├── contact.json
├── faq.json
└── estimate.json
```

---

## Phase 3: Build Static Site

### 3.1 Mappa struktúra létrehozása

```
output/
├── index.html
├── szolgaltatasaink/
│   └── index.html
├── [service-slug]/
│   └── index.html
├── [keyword-slug]-[location-slug]/
│   └── index.html
├── rolunk/
│   └── index.html
├── kapcsolat/
│   └── index.html
├── ajanlatkeres/
│   └── index.html
├── gyik/
│   └── index.html
├── aszf/
│   └── index.html
├── adatvedelmi-nyilatkozat/
│   └── index.html
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── sitemap.xml
└── robots.txt
```

### 3.2 Template feldolgozás

1. Olvasd be a template-et `templates/` mappából
2. Helyettesítsd a változókat (`{{variable}}`)
3. Illessz be generált tartalmat
4. Mentsd `output/[path]/index.html`-be

### 3.3 SEO fájlok generálása

- `sitemap.xml` - minden oldal URL-je
- `robots.txt` - engedélyezés

### 3.4 Assets másolása

Másold az `assets/` mappa tartalmát `output/assets/`-ba.

---

## Phase 4: Deployment

### 4.1 Local preview

```bash
cd output
python3 -m http.server 8080
```

Kérd meg a usert, hogy ellenőrizze: `http://localhost:8080`

### 4.2 Git inicializálás

```bash
cd output
git init
git add .
git commit -m "Initial commit: [business.name] website"
```

### 4.3 GitHub repo létrehozása

```bash
gh repo create "[domain]" --public --source=. --remote=origin --push
```

### 4.4 GitHub Pages engedélyezése

Utasítsd a usert:
1. GitHub repo → Settings → Pages
2. Source: Deploy from branch
3. Branch: main / root
4. Save

### 4.5 Custom domain (opcionális)

Ha a user szeretne custom domain-t:
```bash
echo "[domain.hu]" > CNAME
git add CNAME && git commit -m "Add custom domain" && git push
```

DNS beállítások megadása.

---

## Prompt Használat

### Prompt betöltés

1. Olvasd be a prompt fájlt `prompts/[type]/[name].md`
2. Keresd meg a `## Prompt` szekciót
3. Helyettesítsd a változókat a `config.json`-ból

### Változó szintaxis

```
{{business.name}}           → config.business.name
{{service.name}}            → aktuális szolgáltatás neve
{{location.name}}           → aktuális helyszín neve
{{seo.primary_keyword}}     → config.seo.primary_keyword VAGY config.business.industry
{{services_list}}           → szolgáltatások vesszővel elválasztva
```

### Master prompt

Minden generáláshoz add hozzá a `prompts/master-prompts.md` tartalmát:
- `master_prompt` - fő stílus szabályok
- `format_leader` - leader formázás (ha leader-t generálsz)
- `text_prompt` - szöveg formázás (ha text-et generálsz)
- `knowledge_prompt` - domain tudás (ha van megadva)

---

## Error Handling

### API hibák

- 429 (Rate limit): várj és próbáld újra exponenciális backoff-fal
- 500/502/503: próbáld újra max 3x
- 401/403: API kulcs hiba - kérd a usert az ellenőrzésre

### Hiányzó adatok

- Ha változó hiányzik: logold és használj fallback-et ha van
- Ha kritikus adat hiányzik: állj meg és kérdezd a usert

### Validáció

Generálás után ellenőrizd:
- Minden oldal létrejött
- Nincs üres tartalom
- Linkek helyesek

---

## Fájl Hivatkozások

| Fájl | Cél |
|------|-----|
| `config.json` | Business konfiguráció |
| `locations.csv` | Helyszínek listája |
| `prompts/` | Tartalom promptok |
| `templates/` | HTML sablonok |
| `generated/` | Generált tartalom (JSON) |
| `output/` | Végleges statikus site |
| `.env` | API kulcsok |

---

## Példa Conversation Flow

```
User: Generálj nekem egy local business weboldalt

Claude: Rendben! Először ellenőrzöm a környezetet...
        ✓ .env fájl megtalálva
        ✓ OpenAI API kulcs beállítva

        Kezdjük a konfigurációval.

        Melyik LLM provider-t szeretnéd használni?
        1. OpenAI (gpt-4o-mini) - Ajánlott
        2. Google Gemini

User: 1

Claude: Mi a vállalkozás neve?

User: PadlóMester Budapest

Claude: Mi az iparág?

User: padlóburkoló

Claude: Mi a fő kulcsszó? (opcionális, enter a kihagyáshoz)

User: vinyl padló lerakás

[... további kérdések ...]

Claude: === Konfiguráció összefoglaló ===
        Cégnév: PadlóMester Budapest
        Iparág: padlóburkoló
        Fő kulcsszó: vinyl padló lerakás
        ...

        Folytatjuk? (igen/nem)

User: igen

Claude: === Tartalom generálás ===
        [1/228] Homepage leader... ✓
        [2/228] Homepage text... ✓
        ...
```
