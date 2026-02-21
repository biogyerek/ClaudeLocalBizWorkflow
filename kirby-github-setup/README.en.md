# Kirby Microsite Template

> Professional Kirby CMS-based website template for local service businesses

[Magyar dokumentáció](README.md) | [Architecture](ARCHITECTURE.en.md) | [Routing](ROUTING.en.md) | [Development](DEVELOPMENT.en.md)

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Server Information](#server-information)
- [Documentation](#documentation)

## 🎯 Overview

This project is a **comprehensive Kirby CMS-based website template** specifically designed for **Hungarian local service businesses**.

### Current Deployments

- **Production site**: [fahazkivitelezes.hu](https://fahazkivitelezes.hu) (Cabin construction)
- **Test template**: [mykirtemplate.top](https://mykirtemplate.top) (Carpentry - Asztalos)

### Language

- **Primary Language**: Hungarian (magyar)
- **Content**: Fully localized Hungarian UI and content
- **AI Prompts**: Hungarian language prompts for Kirby Copilot
- **Industry**: Currently configured for carpentry (asztalos), but easily adaptable to other industries

## ✨ Features

### Content Management
- ✅ **Kirby 4.x CMS** - Modern, file-based CMS
- ✅ **Dynamic pages** - CSV-based location generation
- ✅ **AI content generation** - OpenAI GPT-4 integration
- ✅ **Text Replacements** - Placeholder system

### SEO & Marketing
- ✅ **SEO optimization** - Meta tags, Open Graph, Twitter Cards
- ✅ **Schema.org** structured data
- ✅ **Sitemap generation**

### Developer Tools
- ✅ **Asset fingerprinting** - CSS/JS versioning
- ✅ **WebP image conversion** - Automatic optimization
- ✅ **Form handling** - Kirby Uniform integration
- ✅ **Custom routing** - Clean URLs

## 💻 Requirements

- PHP **8.2** or newer
- Apache 2.4+ with **mod_rewrite** enabled
- SSH access (recommended)

## 🚀 Installation

```bash
# 1. Clone
git clone https://github.com/your-username/kirby-github-setup.git
cd kirby-github-setup/microsite_base

# 2. Permissions
chmod -R 755 .
chmod -R 777 site/sessions site/cache media

# 3. Access panel
# http://localhost/microsite_base/panel
```

## ⚙️ Configuration

### CSV Locations

Edit the `content/locations.csv` file:

```csv
MainLocation;SubLocation
Budapest;I. district
Budapest;II. district
Érd;
```

## 🌐 Server Information

### SSH Connection

```bash
ssh -p 65002 u388646151@82.29.186.244
```

### Deployment

```bash
rsync -avz -e "ssh -p 65002" microsite_base/ u388646151@82.29.186.244:/home/u388646151/domains/DOMAIN/public_html/
```

## 📚 Documentation

### Getting Started
- [SYSTEM_ARCHITECTURE.md](SYSTEM_ARCHITECTURE.md) - 🆕 **Complete Hungarian microsite system guide** ⭐
- [README.en.md](README.en.md) - This file (English overview)
- [README.md](README.md) - Magyar összefoglaló

### Technical Documentation
- [ARCHITECTURE.en.md](ARCHITECTURE.en.md) - System architecture (English)
- [ROUTING.en.md](ROUTING.en.md) - URL routing details ⭐ **Updated 2024-12-04**
- [DEVELOPMENT.en.md](DEVELOPMENT.en.md) - Development guide
- [SERVER_ACCESS.md](SERVER_ACCESS.md) - Server access
- [WARP.md](WARP.md) - Cloudflare WARP configuration

### Creating New Hungarian Microsites
See [SYSTEM_ARCHITECTURE.md](SYSTEM_ARCHITECTURE.md) section "Új Magyar Mikrosite Létrehozása" for a complete step-by-step guide on adapting this template for different industries (paving, heating, cooling, etc.)

## 📝 Changelog

### 2024-12-03: Location Page Content Display Fix

**Problem Solved:** Location pages (e.g., `/asztalos-budapest`) now **display AI-generated content** after saving.

**Changes:**
- ✅ `locations.php` model: Combines physical and virtual pages (no overwriting)
- ✅ `location.php` controller: Uses own page content ($template = $page)
- ✅ `location.php` template: showLeader check with fallback
- ✅ Slug format: `service-in-budapest` → `asztalos-budapest`

**Details:** [CHANGELOG_2024-12-03.md](CHANGELOG_2024-12-03.md)

## 🔧 Troubleshooting

### Locations not showing
```bash
head -5 content/locations.csv
```

### Clear cache
```bash
rm -rf site/cache/* site/sessions/*
```

## 📄 License

This project uses Kirby CMS: https://getkirby.com/license

---

**Version:** 1.0.0 | **Last updated:** 2024-12-03
