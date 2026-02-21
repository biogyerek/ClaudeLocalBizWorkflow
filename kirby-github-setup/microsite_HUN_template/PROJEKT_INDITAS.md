# Új Projekt Indítása - Gyors Útmutató

## 1. Template Másolása

```bash
cd /Users/pro/Desktop/dev/kirby-github-setup
cp -r microsite_HUN_template ../my-new-project
cd ../my-new-project
```

## 2. Projekt Konfiguráció

Szerkeszd a `project-config.sh` fájlt:

```bash
nano project-config.sh
```

Töltsd ki a következő mezőket:

```bash
PROJECT_NAME="Asztalos Pécs"                  # Projekt neve
DOMAIN="asztalospecs.hu"                      # Végleges domain
SOURCE_URL="https://mykirtemplate.top"        # Forrás URL

COMPANY_NAME="Asztalos Pécs"                  # Cégnév
PHONE="+36701234567"                          # Telefonszám
EMAIL="info@asztalospecs.hu"                  # Email

GITHUB_USERNAME="yourname"                    # GitHub felhasználónév
GITHUB_REPO="asztalospecs.hu"                 # GitHub repo neve
```

Mentés: `Ctrl+X`, `Y`, `Enter`

## 3. Statikus Oldal Generálása

```bash
bash generate-static.sh
```

A script automatikusan:
- Használja a `project-config.sh` beállításait
- Letölti az oldalt a `SOURCE_URL`-ről
- Lecseréli az URL-eket `DOMAIN`-re
- Kicseréli a placeholdereket (telefon, cégnév)
- Javítja a CSS hivatkozásokat

## 4. Ellenőrzés

```bash
cd static

# Ellenőrizd hogy nincsenek maradt hibák
grep -r "mykirtemplate.top" . --include="*.html"  # 0 találat legyen
grep -r "{{ " . --include="*.html"                 # 0 találat legyen

# Ellenőrizd hogy a domain helyes
grep -r "$DOMAIN" . --include="*.html" | head -3   # Látszódjon az új domain
```

## 5. Helyi Teszt

```bash
python3 -m http.server 8080
```

Nyisd meg: http://localhost:8080

## 6. GitHub Repo Létrehozása

### Opció A: GitHub CLI (ajánlott)

```bash
# Inicializálás
git init
git add .
git commit -m "Initial commit: $PROJECT_NAME"

# Repo létrehozása és push
gh repo create "$GITHUB_REPO" --public --source=. --remote=origin --push
```

### Opció B: Manuális

```bash
# Inicializálás
git init
git add .
git commit -m "Initial commit: $PROJECT_NAME"

# GitHub-on hozz létre a repót kézzel, majd:
git remote add origin https://github.com/$GITHUB_USERNAME/$GITHUB_REPO.git
git branch -M main
git push -u origin main
```

## 7. GitHub Pages Beállítása

### GitHub UI-ban:

1. Menj a repo Settings-re
2. Bal oldali menü: **Pages**
3. Source: **Deploy from a branch**
4. Branch: **main** / **/ (root)**
5. Kattints **Save**

Kb. 2 perc múlva elérhető: `https://$GITHUB_USERNAME.github.io/$GITHUB_REPO/`

## 8. Custom Domain Beállítása

### A) CNAME fájl létrehozása

```bash
echo "$DOMAIN" > CNAME
git add CNAME
git commit -m "Add custom domain"
git push
```

### B) DNS Beállítások (Domain regisztrátornál)

**A Records** (vagy ALIAS/ANAME):
```
@     A     185.199.108.153
@     A     185.199.109.153
@     A     185.199.110.153
@     A     185.199.111.153
```

**CNAME Record** (www aldomain):
```
www   CNAME   $GITHUB_USERNAME.github.io.
```

### C) GitHub Pages Custom Domain

1. GitHub repo: Settings → Pages
2. **Custom domain** mezőbe írd: `$DOMAIN`
3. Kattints **Save**
4. Várd meg, amíg a DNS ellenőrzés sikeres lesz (kb. 10-60 perc)
5. Pipáld be: **Enforce HTTPS**

## 9. Jövőbeli Frissítések

Ha módosítod a Kirby oldalt és újra szeretnéd generálni:

```bash
# 1. Generálás
bash generate-static.sh

# 2. Ellenőrzés
cd static
python3 -m http.server 8080

# 3. Push
git add .
git commit -m "Update content"
git push
```

Kb. 2 perc múlva élesedik a változás!

## Tippek

### Gyors Parancsok

```bash
# Teljes újragenerálás és push egyetlen parancsban
bash generate-static.sh && cd static && git add . && git commit -m "Update $(date +%Y-%m-%d)" && git push && cd ..

# Ellenőrzés egy parancsban
cd static && grep -r "{{ " . && grep -r "mykirtemplate" . && echo "✅ Minden OK!" || echo "⚠️ Van hiba!"
```

### Gyakori Hibák

**"project-config.sh not found"**
- Másold át a template-ből: `cp microsite_HUN_template/project-config.sh .`

**"wget: command not found"**
- Telepítsd: `brew install wget`

**"CSS files not loading"**
- Ellenőrizd: `ls static/assets/css/`
- Futtasd újra: `bash post-generate-fixes.sh`

**"Wrong domain in HTML"**
- Ellenőrizd a `project-config.sh` `DOMAIN` értékét
- Futtasd újra: `bash generate-static.sh`

## Példa Projekt Szerkezet

```
my-new-project/
├── project-config.sh           # Projekt konfiguráció (SZERKESZTENDŐ!)
├── generate-static.sh          # Statikus generáló script
├── post-generate-fixes.sh      # Automatikus javítások
├── generate-static-wrapper.php # PHP wrapper (opcionális)
├── PROJEKT_INDITAS.md          # Ez az útmutató
├── STATIC_GENERATION_README.md # Részletes dokumentáció
└── static/                     # Generált statikus oldal (git repo)
    ├── .git/
    ├── index.html
    ├── assets/
    ├── media/
    └── CNAME
```

## Következő Lépések

1. ✅ Template átmásolva
2. ✅ `project-config.sh` szerkesztve
3. ✅ Statikus oldal generálva
4. ✅ Lokálisan tesztelve
5. ✅ GitHub repo létrehozva
6. ✅ GitHub Pages engedélyezve
7. ✅ Custom domain beállítva
8. ✅ HTTPS enabled

**Gratulálunk! Az oldal él! 🎉**

Látogatás: `https://$DOMAIN`
