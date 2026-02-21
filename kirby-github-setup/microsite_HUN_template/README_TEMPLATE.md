# Magyar Mikrosite Template

> Tiszta Kirby CMS sablon magyar helyi vállalkozások számára

## 📋 Mi Ez?

Ez egy **tiszta template mappa**, amely tartalmazza az összes szükséges fájlt egy új magyar mikrosite létrehozásához, DE:

✅ **NINCS benne:**
- Media fájlok (képek, dokumentumok)
- Fizikális location mappák (asztalos-budapest, stb.)
- Asztalos-specifikus tartalom

✅ **VAN benne:**
- Teljes Kirby CMS rendszer
- Magyar lokalizációs fájlok
- AI prompt template-ek
- `locations.csv` fájl (virtuális location-ök generálásához)
- Alapértelmezett konfiguráció
- Blueprint struktúra

## 🚀 Új Mikrosite Létrehozása

### 1. Mappa Másolása

```bash
cp -R microsite_HUN_template your-new-project
cd your-new-project
```

### 2. Iparágtól Függő Módosítások

#### A. Routing Frissítése

**Fájl**: `site/config/routes.php`

Cseréld ki a pattern-t az új iparág slug-jára:

```php
// JELENLEGI (asztalos):
'pattern' => 'asztalos-(:any)-(:all)',

// ÚJ példák:
'pattern' => 'terkovezes-(:any)-(:all)',      // Térkövezés
'pattern' => 'melegburkolo-(:any)-(:all)',    // Melegburkoló
'pattern' => 'hidegburkolo-(:any)-(:all)',    // Hidegburkoló
```

#### B. Locations.csv Frissítése

**Fájl**: `content/locations.csv`

```csv
location,slug
Budapest,terkovezes-budapest
Budaörs,terkovezes-budaors
Gödöllő,terkovezes-godollo
```

Generáld a teljes listát az új slug prefix-szel!

#### C. Cégadatok (site.en.txt)

**Fájl**: `content/site.en.txt`

```yaml
Companyname: [Cég Neve]
Companyindustry: [Térkövezés / Melegburkolás / stb.]
Companyservices: [szolgáltatás1, szolgáltatás2, szolgáltatás3]
Companyarea: [Budapest / Régió]
Phone: [+36 XX XXX XXXX]
Email: [info@ceg.hu]
Address: [Teljes cím]
```

#### D. Példa Tartalmak Testreszabása

**Ugyancsak `content/site.en.txt`**:

Frissítsd az alábbi mezőket az új iparágra:
- `Exampleleader`
- `Examplefaqpage`
- `Exampleservicepage`
- `Examplemission`
- `Exampletagline`
- `Examplecontact`

#### E. Szolgáltatások Létrehozása

**Mappa**: `content/1_szolgaltatasaink/`

Hozz létre almappákat:
```
1_szolgaltatasi-teruletek/
  1_terkovezes/
    service.en.txt
  2_jardalap-fektetese/
    service.en.txt
  3_betonozas/
    service.en.txt
```

**service.en.txt tartalom**:
```yaml
Title: Térkövezés
Leader: ### Professzionális Térkövezés
# Kiváló minőségű térkő lerakás Budapest környékén
Hívj: {{ phone }}

Intro: [Bevezető szöveg...]
Text: [Fő tartalom...]
```

#### F. Prompt Tartalmak

**Fájlok**: `site/blueprints/prompts/*/auto-*.yml`

Ellenőrizd, hogy a prompt szövegek megfelelnek-e az új iparágnak. Általában működnek változtatás nélkül, mert a `{{ site.companyIndustry }}` és `{{ site.companyServices }}` placeholder-eket használnak.

### 3. Képek Feltöltése

**Panel**: `http://your-domain.com/panel`

1. Jelentkezz be
2. Site Settings > Files
3. Tölts fel:
   - Logo
   - Favicon
   - Cover image (hero képek)
   - Szolgáltatás képek

### 4. Színek és Branding

**Panel**: Site Settings > Layout

```yaml
Colorbrand: #27ae60     # Fő brand szín
Colortext: #ecf0f1      # Szöveg szín
Colorbtn: #f1c40f       # Gomb szín
```

Vagy szerkeszd közvetlenül: `content/site.en.txt`

### 5. Teszt

Ellenőrizd:
- [ ] Főoldal betöltődik
- [ ] Szolgáltatások listája megjelenik
- [ ] Location oldalak elérhetők (pl. `/terkovezes-budapest`)
- [ ] AI generálás működik
- [ ] Placeholder-ek helyettesítődnek
- [ ] Footer és menük helyesek

### 6. Cache Tisztítás

```bash
rm -rf site/cache/* site/sessions/*
```

## 📚 Részletes Dokumentáció

A repository gyökerében található dokumentációk:

- **SYSTEM_ARCHITECTURE.md** - Teljes rendszer architektúra útmutató
- **ROUTING.en.md** - URL routing részletek
- **README.en.md** - Általános áttekintés

## 🔧 Gyakori Problémák

### "Location oldal 404-et ad"

**Ok**: Routing nincs frissítve vagy locations.csv helytelen

**Megoldás**:
1. Ellenőrizd `site/config/routes.php` pattern-t
2. Ellenőrizd `content/locations.csv` slug oszlopot
3. Cache tisztítás

### "Placeholder nem jelenik meg"

**Ok**: Hiányzik a `.kti()` hívás vagy hibás a field név

**Megoldás**:
- Template-ben: `<?= $site->fieldName()->kti() ?>`
- Content-ben: `{{ fieldName }}`

### "AI generálás nem működik"

**Ok**: API kulcs hiányzik

**Megoldás**:
- Állítsd be a `site/config/config.php`-ban az OpenAI API kulcsot

## 🎯 Következő Lépések

1. ✅ Másolj
2. ✅ Customizálj (routing, locations.csv, site.en.txt)
3. ✅ Tölts fel képeket
4. ✅ Tesztelj
5. ✅ Deploy-olj a szerverre

## 📞 Támogatás

Kérdések esetén lásd a fő dokumentációt: `/SYSTEM_ARCHITECTURE.md`
