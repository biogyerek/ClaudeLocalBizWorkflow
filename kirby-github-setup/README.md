# Kirby Microsite Template

> Professzionális Kirby CMS alapú weboldal sablon helyi vállalkozások számára

[English documentation](README.en.md) | [Architektúra](ARCHITECTURE.md) | [Routing](ROUTING.md) | [Fejlesztés](DEVELOPMENT.md)

## 📋 Tartalomjegyzék

- [Áttekintés](#áttekintés)
- [Funkciók](#funkciók)
- [Követelmények](#követelmények)
- [Telepítés](#telepítés)
- [Konfiguráció](#konfiguráció)
- [Szerver Információk](#szerver-információk)
- [Dokumentáció](#dokumentáció)

## 🎯 Áttekintés

Ez a projekt egy **teljes körű Kirby CMS alapú weboldal sablon**, amely kifejezetten helyi szolgáltató vállalkozások számára lett tervezve.

### Jelenlegi Telepítések

- **Éles oldal**: [fahazkivitelezes.hu](https://fahazkivitelezes.hu)
- **Teszt template**: [mykirtemplate.top](https://mykirtemplate.top)

## ✨ Funkciók

### Tartalom Kezelés
- ✅ **Kirby 4.x CMS** - Modern, file-based CMS
- ✅ **Dinamikus oldalak** - CSV-alapú location generálás
- ✅ **AI tartalom generálás** - OpenAI GPT-4 integráció
- ✅ **Text Replacements** - Placeholder rendszer

### SEO & Marketing
- ✅ **SEO optimalizálás** - Meta tagek, Open Graph, Twitter Cards
- ✅ **Schema.org** structured data
- ✅ **Sitemap generálás**

### Fejlesztői Eszközök
- ✅ **Asset fingerprinting** - CSS/JS verzionálás
- ✅ **WebP képkonverzió** - Automatikus optimalizálás
- ✅ **Form handling** - Kirby Uniform integráció
- ✅ **Custom routing** - Tiszta URL-ek

## 💻 Követelmények

- PHP **8.2** vagy újabb
- Apache 2.4+ **mod_rewrite** engedélyezve
- SSH hozzáférés (ajánlott)

## 🚀 Telepítés

```bash
# 1. Klónozás
git clone https://github.com/your-username/kirby-github-setup.git
cd kirby-github-setup/microsite_base

# 2. Jogosultságok
chmod -R 755 .
chmod -R 777 site/sessions site/cache media

# 3. Panel elérése
# http://localhost/microsite_base/panel
```

## ⚙️ Konfiguráció

### CSV Helyszínek

Szerkeszd a `content/locations.csv` fájlt:

```csv
MainLocation;SubLocation
Budapest;I. kerület
Budapest;II. kerület
Érd;
```

## 🌐 Szerver Információk

### SSH Kapcsolódás

```bash
ssh -p 65002 u388646151@82.29.186.244
```

### Deployment

```bash
rsync -avz -e "ssh -p 65002" microsite_base/ u388646151@82.29.186.244:/home/u388646151/domains/DOMAIN/public_html/
```

## 📚 Dokumentáció

- [ARCHITECTURE.md](ARCHITECTURE.md) - Teljes rendszer felépítés
- [ROUTING.md](ROUTING.md) - URL routing részletek ⭐ **Frissítve 2024-12-03**
- [DEVELOPMENT.md](DEVELOPMENT.md) - Fejlesztési guide
- [SERVER_ACCESS.md](SERVER_ACCESS.md) - Szerver hozzáférés

## 📝 Változásnapló

### 2024-12-03: Location Oldal Tartalom Javítás

**Probléma megoldva:** A location oldalak (pl. `/asztalos-budapest`) mostantól **megjelenítik az AI-generált tartalmat** mentés után.

**Változások:**
- ✅ `locations.php` model: Fizikai és virtuális oldalak kombinálása (nincs felülírás)
- ✅ `location.php` controller: Saját oldal tartalmának használata ($template = $page)
- ✅ `location.php` template: showLeader ellenőrzés fallback-kel
- ✅ Slug formátum: `service-in-budapest` → `asztalos-budapest`

**Részletek:** [CHANGELOG_2024-12-03.md](CHANGELOG_2024-12-03.md)

## 🔧 Hibaelhárítás

### Locations nem jelennek meg
```bash
head -5 content/locations.csv
```

### Cache törlés
```bash
rm -rf site/cache/* site/sessions/*
```

## 📄 Licensz

Ez a projekt Kirby CMS-t használ: https://getkirby.com/license

---

**Verzió:** 1.0.0 | **Utolsó frissítés:** 2024-12-03
