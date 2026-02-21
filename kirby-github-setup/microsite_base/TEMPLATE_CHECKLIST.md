# Microsite Template Checklist

Ez a checklist segít az új mikrooldal projektek indításakor és az éles környezetbe történő telepítéskor.

## 1. Projekt Inicializálás

- [ ] Kirby telepítése és konfiguráció
- [ ] Panel felhasználó létrehozása
- [ ] Alapvető beállítások (site.txt) kitöltése:
  - [ ] Email cím megadása (Email field)
  - [ ] Emailfrom cím megadása
  - [ ] Colorbrand, Colortext színek beállítása
  - [ ] Logo feltöltése és beállítása

## 2. Tartalom Létrehozása

- [ ] Főoldal tartalom elkészítése
- [ ] Szolgáltatás oldalak létrehozása (minimum 4)
- [ ] Kapcsolat/Árajánlat oldal elkészítése
- [ ] FAQ oldal létrehozása
- [ ] Adatvédelmi nyilatkozat oldal létrehozása
- [ ] Szolgáltatási területek oldal létrehozása

## 3. Statikus Oldal Generálás

- [ ] `generate-static.sh` script futtatása
- [ ] Statikus fájlok ellenőrzése a `static/` mappában
- [ ] Belső linkek ellenőrzése (relatív linkek működnek)

## 4. Footer Menü Javítás

- [ ] `fix-footer-menu.py` futtatása
- [ ] "Gyors linkek" menü ellenőrzése minden oldalon:
  - [ ] Magunkról link
  - [ ] Adatvédelmi Nyilatkozat link
  - [ ] FAQ link
- [ ] "Cég" menü ellenőrzése minden oldalon:
  - [ ] 4 szolgáltatás link (Beépített Szekrény, Bútor Javítás, Egyedi Bútor, Egyedi Konyhabútor)

## 5. Főoldal Szolgáltatás Linkek

- [ ] **FONTOS**: Főoldal szolgáltatás h3 címsorok linkelése
- [ ] `static/index.html` szerkesztése:
  - [ ] "Beépített Szekrény Készítés" h3 → `<a href="beepitett-szekreny-keszites.html">`
  - [ ] "Bútor Javítás" h3 → `<a href="butor-javitas.html">`
  - [ ] "Egyedi Bútor Készítés" h3 → `<a href="egyedi-butor-keszites.html">`
  - [ ] "Egyedi Konyhabútor Készítés" h3 → `<a href="egyedi-konyhabutor-keszites.html">`
- [ ] **FONTOS**: "Tovább olvasom" gombok eltávolítása (redundáns linkek)
- [ ] CSS frissítése (`assets/css/layouts/home.css`):
  - [ ] `.service-box` grid-template-rows: `auto 1fr` (volt: `1fr 3fr 1fr`)

**Példa**:
```html
<!-- Előtte: -->
<h3 class="service-heading">Beépített Szekrény Készítés</h3>
<span class="service-description">...</span>
<a class="btn btn--filled" href="beepitett-szekreny-keszites.html">Tovább olvasom</a>

<!-- Utána: -->
<h3 class="service-heading"><a href="beepitett-szekreny-keszites.html">Beépített Szekrény Készítés</a></h3>
<span class="service-description">...</span>
<!-- Tovább olvasom gomb törölve -->
```

## 6. Navigáció Testreszabás (Opcionális)

- [ ] `remove-magunkrol-menu.py` futtatása (ha szükséges)
- [ ] Főmenü linkek ellenőrzése
- [ ] Footer menü linkek ellenőrzése (nem törlődnek)

## 7. Szolgáltatás Oldalak CTA Gombok (Opcionális)

- [ ] `fix-service-cta.py` futtatása (ha click-to-call kell)
- [ ] Telefonszám beállítása a scriptben
- [ ] Leader section CTA gombok ellenőrzése szolgáltatás oldalakon

## 8. Kapcsolati Űrlap Konfiguráció

- [ ] Formspree integráció beállítása:
  - [ ] Email cím megadása az űrlap action-ben
  - [ ] `_subject` mező testreszabása
  - [ ] `_next` (köszönő oldal) URL beállítása
- [ ] Űrlap mezők magyar fordításának ellenőrzése
- [ ] Kötelező mezők jelölésének ellenőrzése

## 9. SEO Beállítások

- [ ] CNAME fájl létrehozása (csak non-www domain)
- [ ] `check-seo-duplicates.sh` futtatása
- [ ] Ellenőrzések:
  - [ ] Canonical URL-ek (non-www)
  - [ ] Open Graph URL-ek (non-www)
  - [ ] Sitemap URL-ek (non-www)
  - [ ] robots.txt sitemap URL (non-www)
  - [ ] Nincs www. link a HTML fájlokban

## 10. DNS Konfiguráció

- [ ] A rekordok beállítása (GitHub Pages IP-k):
  - [ ] 185.199.108.153
  - [ ] 185.199.109.153
  - [ ] 185.199.110.153
  - [ ] 185.199.111.153
- [ ] CNAME rekord beállítása www → joszaki.github.io.
- [ ] DNS propagáció ellenőrzése (dnschecker.org)

## 11. GitHub Pages Telepítés

- [ ] GitHub repository létrehozása (domain névvel)
- [ ] Statikus fájlok feltöltése
- [ ] GitHub Pages engedélyezése
- [ ] Custom domain beállítása
- [ ] Enforce HTTPS engedélyezése
- [ ] SSL certificate ellenőrzése (10-60 perc)

## 12. Élő Oldal Tesztelés

- [ ] WWW → non-www redirect tesztelése (301)
- [ ] HTTPS működés ellenőrzése
- [ ] Űrlap tesztelése (Formspree)
- [ ] Mobilos megjelenítés ellenőrzése
- [ ] Minden belső link működésének ellenőrzése
- [ ] Footer linkek működésének ellenőrzése
- [ ] **Főoldal szolgáltatás h3 linkek működésének ellenőrzése**
- [ ] Call-to-action gombok működésének ellenőrzése

## 13. Analytics és Tracking

- [ ] Google Analytics beállítása:
  - [ ] GA tracking ID beszerzése (pl. G-XXXXXXXXXX)
  - [ ] `add-google-analytics.py` script futtatása
  - [ ] Ellenőrzés: gtag.js minden oldalon betöltődik
- [ ] Google Tag Manager beállítása:
  - [ ] GTM container ID beszerzése (pl. GTM-XXXXXXX)
  - [ ] `add-google-tag-manager.py` script futtatása
  - [ ] Ellenőrzés: GTM script a `<head>`-ben, noscript a `<body>`-ban
- [ ] Cookie consent banner ellenőrzése és működésének tesztelése
- [ ] GDPR megfelelőség ellenőrzése

**Google Analytics hozzáadása:**
```bash
python3 add-google-analytics.py static-site
```

**Google Tag Manager hozzáadása:**
```bash
python3 add-google-tag-manager.py static-site
```

A scriptek automatikusan hozzáadják a tracking kódokat:
- GA: gtag.js script a `</head>` elé
- GTM: container script a `</head>` elé + noscript iframe a `<body>` után

## 14. Dokumentáció

- [ ] README.md frissítése projekt-specifikus információkkal
- [ ] DNS_SETUP.md ellenőrzése és frissítése
- [ ] Telepítési jegyzetek készítése

## Gyakori Hibák Elkerülése

- ⚠️ Ne felejtsd el az Email mezőt kitölteni a Kirby Panel-ben!
- ⚠️ Footer menük mindig az utolsó lépések egyike (statikus generálás után)
- ⚠️ **Főoldal szolgáltatás h3 linkek kézi szerkesztése kötelező!**
- ⚠️ CNAME fájl csak non-www domaint tartalmazzon
- ⚠️ Formspree email cím helyes beállítása
- ⚠️ Szolgáltatás oldalak CTA gombjai egyedi igények szerint

## Hasznos Scriptek

- `generate-static.sh` - Statikus oldal generálás
- `fix-footer-menu.py` - Footer menük hozzáadása
- `remove-magunkrol-menu.py` - Magunkról eltávolítása főmenüből
- `fix-service-cta.py` - Szolgáltatás CTA click-to-call átalakítás
- `check-seo-duplicates.sh` - SEO duplikáció ellenőrzés
- `add-google-analytics.py` - Google Analytics tracking kód hozzáadása minden oldalhoz
- `add-google-tag-manager.py` - Google Tag Manager container kód hozzáadása minden oldalhoz

## Referenciák

- [DNS_SETUP.md](DNS_SETUP.md) - DNS konfiguráció részletes leírása
- [GitHub Pages Docs](https://docs.github.com/en/pages)
- [Formspree Docs](https://formspree.io/help/)
