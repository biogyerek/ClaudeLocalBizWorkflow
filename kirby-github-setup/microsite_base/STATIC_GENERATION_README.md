# Statikus Oldal Generálás - Használati Útmutató

## Áttekintés

Ez a projekt **wget-alapú** statikus oldal generálást használ, amely megbízhatóbb és egyszerűbb, mint a Kirby plugin-ok.

## Gyors Kezdés

### 1. Projekt Beállítása

Új projekt indításánál másold át a template-et:

```bash
cp -r microsite_HUN_template my-new-project
cd my-new-project
```

### 2. Konfiguráció

Szerkeszd a `post-generate-fixes.sh` fájlt:

```bash
nano post-generate-fixes.sh
```

Állítsd be a saját értékeidet:

```bash
DOMAIN="mywebsite.com"              # Céloldal domain
PHONE="+36701234567"                # Telefonszám
COMPANY_NAME="Cég Neve"             # Cégnév
OLD_DOMAIN="mykirtemplate.top"      # Honnan másolod (általában ez)
```

### 3. Statikus Oldal Generálása

#### Opció A: Bash script (Ajánlott)

```bash
# Helyi szerverről (ha Kirby fut lokálisan)
bash generate-static.sh --url http://localhost:8000

# Távoli szerverről
bash generate-static.sh --url https://mykirtemplate.top
```

#### Opció B: PHP wrapper

```bash
# Ugyanaz, mint a bash script, de PHP-ból hívva
php generate-static-wrapper.php --url https://mykirtemplate.top
```

#### Opció C: Környezeti változóval

```bash
# Állítsd be a környezeti változót
export SITE_URL=https://mykirtemplate.top

# Futtasd a scriptet
bash generate-static.sh
```

### 4. Ellenőrzés

```bash
# Ellenőrizd a generált fájlokat
cd static
ls -la

# Ellenőrizd, hogy nincsenek-e maradt placeholderek
grep -r "{{ telefon }}" . --include="*.html"
grep -r "{{ companyName }}" . --include="*.html"
grep -r "mykirtemplate.top" . --include="*.html"

# Ha mindhárom 0 találatot ad → minden OK! ✅
```

### 5. Helyi Teszt

```bash
cd static
python3 -m http.server 8080
# Nyisd meg: http://localhost:8080
```

### 6. GitHub Deployment

```bash
cd static

# Ha még nincs git repo inicializálva
git init
git add .
git commit -m "Initial static site"
git remote add origin https://github.com/USERNAME/REPO.git
git branch -M main
git push -u origin main

# Ha már létezik a repo
git add .
git commit -m "Update static site"
git push
```

## Hogyan Működik?

### Generálási Folyamat

1. **wget letöltés**:
   - Rekurzívan letölti az összes HTML oldalt
   - Letölti az összes asset-et (CSS, JS, képek)
   - Konvertálja a linkeket relatívvá
   - Hozzáadja a `.html` kiterjesztést az URL-ekhez

2. **Automatikus javítások** (`post-generate-fixes.sh`):
   - CSS fájlnevek javítása (`@v=123.css` → `.css`)
   - CSS hivatkozások javítása (`.css@v=123.css` → `.css?v=123`)
   - Template placeholderek cseréje (`{{ telefon }}` → `+36701234567`)
   - Domain URL-ek frissítése (`mykirtemplate.top` → `mywebsite.com`)

3. **Eredmény**:
   - Tiszta, deployolható statikus oldal
   - Minden URL helyesen beállítva
   - CSS-ek megfelelően hivatkozva
   - Kész a GitHub Pages-re vagy bármilyen statikus hosting-ra

## CSS Kezelés

A wget módszer **helyesen kezeli** a CSS fájlokat:

### Előtte (Kirby dinamikus):
```html
<link href="/assets/css/index.css?v=1764532195" rel="stylesheet">
```

### Utána (wget letöltés):
```html
<link href="assets/css/index.css?v=1764532195" rel="stylesheet">
```

✅ **Cache-busting query stringek megmaradnak**: `?v=1764532195`
✅ **Relatív útvonalak**: `assets/css/` helyesen működik
✅ **Fájlnevek helyesek**: `.css` (nem `@v=123.css`)

## Konfigurálható Opciók

### generate-static.sh

```bash
--url URL         # Forrás URL (default: http://localhost:8000)
--output DIR      # Kimeneti mappa (default: ./static)
```

### post-generate-fixes.sh

Környezeti változók:

```bash
STATIC_DIR        # Static mappa helye (default: ./static)
DOMAIN            # Cél domain (default: asztalosmesterbudapest.hu)
PHONE             # Telefonszám (default: +36703546606)
COMPANY_NAME      # Cégnév (default: Asztalos Budapest)
OLD_DOMAIN        # Lecserélendő domain (default: mykirtemplate.top)
```

Használat:

```bash
DOMAIN="mysite.com" PHONE="+36301234567" bash generate-static.sh --url https://source.com
```

## Példa: Teljes Munkafolyamat

### Új Projekt Létrehozása

```bash
# 1. Template másolása
cp -r microsite_HUN_template asztalos-pecs
cd asztalos-pecs

# 2. Konfiguráció szerkesztése
nano post-generate-fixes.sh
# Állítsd be:
#   DOMAIN="asztalospecs.hu"
#   PHONE="+36701234567"
#   COMPANY_NAME="Asztalos Pécs"

# 3. Statikus oldal generálása
bash generate-static.sh --url https://mykirtemplate.top

# 4. Ellenőrzés
cd static
grep -r "mykirtemplate.top" . --include="*.html"  # 0 találat legyen
grep -r "{{ " . --include="*.html"                 # 0 találat legyen

# 5. Helyi teszt
python3 -m http.server 8080

# 6. GitHub repo létrehozása
git init
git add .
git commit -m "Initial static site for Asztalos Pécs"
gh repo create asztalospecs.hu --public --source=. --remote=origin --push

# 7. GitHub Pages beállítása
# GitHub UI-ban: Settings → Pages → Source: main branch, root

# 8. Custom domain (opcionális)
echo "asztalospecs.hu" > CNAME
git add CNAME
git commit -m "Add custom domain"
git push
```

## Hibaelhárítás

### "wget: command not found"

```bash
# macOS
brew install wget

# Linux (Ubuntu/Debian)
sudo apt install wget
```

### "No files downloaded"

- Ellenőrizd, hogy a forrás URL elérhető-e
- Ellenőrizd, hogy nincs-e robots.txt blokkolás
- Próbáld meg HTTPS helyett HTTP-t (vagy fordítva)

### "CSS files not loading"

- Ellenőrizd a `static/assets/css/` mappát
- Fájloknak `.css` kiterjesztésűnek kell lenniük (nem `@v=123.css`)
- CSS hivatkozásokban `?v=123` query stringnek kell lennie

### "Template placeholders remaining"

```bash
# Futtasd újra a javításokat
cd ..
bash post-generate-fixes.sh
```

### "Wrong domain URLs"

```bash
# Állítsd be a helyes domaint
nano post-generate-fixes.sh
# Állítsd be DOMAIN és OLD_DOMAIN értékeket

# Futtasd újra
bash post-generate-fixes.sh
```

## Előnyök vs. Kirby Plugin

### ✅ Wget módszer előnyei:

1. **Megbízható**: Nem függ a Kirby verzióktól
2. **Egyszerű**: Csak bash és wget kell
3. **Gyors**: Párhuzamos letöltések
4. **Kompatibilis**: Bármilyen webszerverrel működik
5. **Átlátható**: Látod pontosan mi történik
6. **Tesztelhető**: Bármelyik URL-ről generálhatsz

### ❌ Kirby Plugin hátrányai:

1. Verzió-függőség (Kirby 3 vs 4)
2. PHP hibák kompatibilitási problémák miatt
3. Nehezen debuggolható
4. Bonyolult konfiguráció
5. Plugin frissítések eltörhetik

## További Források

- **wget dokumentáció**: https://www.gnu.org/software/wget/manual/
- **GitHub Pages setup**: https://docs.github.com/en/pages
- **Custom domain setup**: https://docs.github.com/en/pages/configuring-a-custom-domain-for-your-github-pages-site

## Gyakori Kérdések

**Q: Működik ez Windows-on?**
A: Igen, WSL-en (Windows Subsystem for Linux) vagy Git Bash-sel.

**Q: Szükséges a PHP?**
A: Nem, a bash script önmagában is működik. A PHP wrapper csak kényelmi funkció.

**Q: Mi történik a dinamikus tartalommal?**
A: Minden dinamikus tartalom statikussá válik. Form-ok nem működnek (kivéve külső service-ekkel, pl. Formspree).

**Q: Hány oldalt tud kezelni?**
A: Tesztelve 100+ oldalon, de elméletben korlátlan (csak a letöltési idő nő).

**Q: Frissíthetek egy már létező statikus oldalt?**
A: Igen! Futtasd újra a generálást, és push-old GitHub-ra. A fontos fájlok (.git, CNAME) megmaradnak.
