# Statikus Oldal Automatikus Javítások

## Probléma

A Kirby CMS által generált statikus oldal három fő problémával küzdött:

1. **CSS fájlnevek**: A fájlok `index.css@v=1234.css` formátumban voltak elnevezve, de a HTML `index.css?v=1234` formátumban hivatkozott rájuk → 404 hiba
2. **Template változók**: `{{ telefon }}`, `{{ companyName }}` nem lettek lecserélve
3. **Domain URL-ek**: `mykirtemplate.top` maradt minden HTML fájlban a végleges domain helyett

## Megoldás

### 1. Automatikus Javítási Script

Létrehoztunk egy `post-generate-fixes.sh` scriptet, amely:

- ✅ Átnevezi a CSS fájlokat helyes formátumra
- ✅ Javítja a CSS hivatkozásokat minden HTML fájlban
- ✅ Lecseréli a template változókat
- ✅ Frissíti a domain URL-eket
- ✅ Javítja a JSON-LD schema URL-eket

### 2. Integráció a Generate Scriptbe

A `generate-static.php` automatikusan meghívja a javítási scriptet minden generálás után.

## Használat

### Statikus oldal generálása javításokkal

```bash
cd microsite_base
/usr/local/opt/php@8.2/bin/php generate-static.php
```

A script automatikusan:
1. Generálja a statikus oldalt
2. Lefuttatja a `post-generate-fixes.sh` scriptet
3. Elkészíti a deployolható verziót

### Manuális javítás (ha szükséges)

```bash
cd microsite_base
bash post-generate-fixes.sh
```

## Konfigurálható Változók

A `post-generate-fixes.sh` fájl elején állítsd be a saját értékeidet:

```bash
DOMAIN="asztalosmesterbudapest.hu"
PHONE="+36703546606"
COMPANY_NAME="Asztalos Budapest"
```

## Mit Javít Pontosan?

### 1. CSS Fájlnevek
**Előtte:**
```
assets/css/index.css@v=1764532195.css
assets/css/layouts/home.css@v=1764842151.css
```

**Utána:**
```
assets/css/index.css
assets/css/layouts/home.css
```

### 2. HTML CSS Hivatkozások
**Előtte:**
```html
<link href="assets/css/index.css@v=1764532195.css" rel="stylesheet">
```

**Utána:**
```html
<link href="assets/css/index.css?v=1764532195" rel="stylesheet">
```

### 3. Template Változók
**Előtte:**
```html
<p>Hívjon most: {{ telefon }}</p>
<title>{{ companyName }}</title>
```

**Utána:**
```html
<p>Hívjon most: +36703546606</p>
<title>Asztalos Budapest</title>
```

### 4. Domain URL-ek
**Előtte:**
```html
<link rel="canonical" href="https://mykirtemplate.top" />
<a href="https://mykirtemplate.top">Home</a>
```

**Utána:**
```html
<link rel="canonical" href="https://asztalosmesterbudapest.hu" />
<a href="https://asztalosmesterbudapest.hu">Home</a>
```

### 5. JSON-LD Schema
**Előtte:**
```json
{"@context":"https://schema.org","url":"https://mykirtemplate.top"}
```

**Utána:**
```json
{"@context":"https://schema.org","url":"https://asztalosmesterbudapest.hu"}
```

## Ellenőrzés

### Ellenőrizd, hogy minden rendben van

```bash
cd static

# Ellenőrizd a CSS fájlokat
ls -la assets/css/
ls -la assets/css/layouts/

# Ellenőrizd, nincs-e maradt placeholder
grep -r "{{ telefon }}" . --include="*.html"
grep -r "{{ companyName }}" . --include="*.html"
grep -r "mykirtemplate.top" . --include="*.html"

# Ha ezek 0 találatot adnak → minden OK! ✅
```

## GitHub Deployment

### 1. Commitáld és push-old a javított statikus oldalt

```bash
cd static
git add .
git commit -m "Update static site with fixes"
git push origin main
```

### 2. GitHub Pages automatikusan újraépíti az oldalt (30-60 másodperc)

Nézd meg a státuszt:
```bash
gh run list --limit 3
```

## Új Projekt Indítása

Az új projektek a `microsite_HUN_template` mappából indulnak, amely már tartalmazza:
- ✅ `post-generate-fixes.sh`
- ✅ Frissített `generate-static.php`

### Lépések új projektnél

1. Másold át a template-et:
```bash
cp -r microsite_HUN_template my-new-project
cd my-new-project
```

2. Állítsd be a saját értékeidet a `post-generate-fixes.sh`-ban:
```bash
nano post-generate-fixes.sh
# Állítsd be: DOMAIN, PHONE, COMPANY_NAME
```

3. Generáld a statikus oldalt:
```bash
/usr/local/opt/php@8.2/bin/php generate-static.php
```

4. Push-old GitHub-ra:
```bash
cd static
git init
git add .
git commit -m "Initial static site"
git remote add origin https://github.com/USERNAME/REPO.git
git push -u origin main
```

## Troubleshooting

### A CSS még mindig nem töltődik be

```bash
# Ellenőrizd a fájlneveket
cd static/assets/css
ls -la

# Biztosan .css kiterjesztésűek? (nem @v=*.css)
```

### Maradtak template változók

```bash
# Futtasd újra a fix scriptet
cd microsite_base
bash post-generate-fixes.sh
```

### GitHub Pages nem frissül

```bash
# Tisztítsd a cache-t
# A böngészőben: Ctrl+Shift+R (Windows) vagy Cmd+Shift+R (Mac)

# Ellenőrizd a GitHub Actions státuszt
cd static
gh run list --limit 1
gh run view
```

## Fájlok Helye

```
microsite_base/
├── generate-static.php          # Frissített generátor (automatikusan hívja a fix scriptet)
├── post-generate-fixes.sh       # Javítási script
└── static/                      # Generált statikus oldal (deployolásra kész)

microsite_HUN_template/
├── generate-static.php          # Template verzió
└── post-generate-fixes.sh       # Template verzió
```

## Következő Alkalommal

Ha legközelebb generálsz statikus oldalt:

```bash
cd microsite_base
/usr/local/opt/php@8.2/bin/php generate-static.php
# ↑ Ez automatikusan futtatja az összes javítást!

cd static
git add .
git commit -m "Update static site"
git push
```

## Összefoglalás

✅ **Automatizált**: A javítások automatikusan lefutnak minden generálás után
✅ **Megbízható**: 59 HTML fájl mindegyike helyesen kerül javításra
✅ **Konfigurálható**: Könnyen beállíthatod új projektekhez
✅ **Dokumentált**: Minden lépés le van írva

**A jövőben ezek a problémák nem fordulnak elő újra!** 🎉
