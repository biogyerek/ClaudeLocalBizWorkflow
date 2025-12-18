# Claude Local Business Website Workflow

> Generate SEO-optimized Hungarian local business websites through pure Claude Code conversation. No CMS required.

---

## Quick Start

```bash
# 1. Clone the repository
git clone https://github.com/YOUR-USERNAME/claudelocalbizworkflow.git
cd claudelocalbizworkflow

# 2. Set up environment
cp .env.example .env
# Edit .env and add your API key

# 3. Start Claude Code and begin
claude
```

---

## Table of Contents

1. [Overview](#1-overview)
2. [Prerequisites](#2-prerequisites)
3. [Setup](#3-setup)
4. [Phase 1: Configuration](#4-phase-1-configuration)
5. [Phase 2: Content Generation](#5-phase-2-content-generation)
6. [Phase 3: Template Population](#6-phase-3-template-population)
7. [Phase 4: Deployment](#7-phase-4-deployment)
8. [Customization](#8-customization)
9. [Troubleshooting](#9-troubleshooting)

---

## 1. Overview

### What This Creates

A complete local business website with:

| Page Type | Count | Example URL |
|-----------|-------|-------------|
| Homepage | 1 | `/` |
| Services List | 1 | `/szolgaltatasaink/` |
| Individual Services | 4+ | `/konyhabutor-keszites/` |
| Location Pages | 48+ | `/asztalos-budapest-2-kerulet/` |
| About | 1 | `/rolunk/` |
| Contact | 1 | `/kapcsolat/` |
| Quote Request | 1 | `/ajanlatkeres/` |

**Total: 60+ unique, SEO-optimized pages**

### How It Works

```
┌─────────────────────────────────────────────────────────────┐
│                    Claude Code Conversation                  │
├─────────────────────────────────────────────────────────────┤
│ Phase 1: Answer questions about your business               │
│          → config.json                                      │
├─────────────────────────────────────────────────────────────┤
│ Phase 2: Claude generates all content using LLM             │
│          → generated/*.json                                 │
├─────────────────────────────────────────────────────────────┤
│ Phase 3: Content merged with HTML templates                 │
│          → output/*/index.html                              │
├─────────────────────────────────────────────────────────────┤
│ Phase 4: Deploy to GitHub Pages                             │
│          → https://yourdomain.hu                            │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. Prerequisites

### Required

- **Claude Code** - Anthropic's CLI tool
- **LLM API Key** - One of:
  - OpenAI API key (recommended: ~$5 per site)
  - Google Gemini API key (alternative)
- **GitHub Account** - For free hosting

### Optional

- **Custom Domain** - Register at any registrar
- **Make.com Account** - For form handling (free tier)
- **Google Analytics** - For traffic tracking

---

## 3. Setup

### Step 3.1: Clone Repository

```bash
git clone https://github.com/YOUR-USERNAME/claudelocalbizworkflow.git
cd claudelocalbizworkflow
```

### Step 3.2: Configure Environment

```bash
# Copy example environment file
cp .env.example .env
```

Edit `.env` with your API key:

```bash
# Choose your LLM provider
LLM_PROVIDER=openai

# Add your API key
OPENAI_API_KEY=sk-your-key-here
# OR
GOOGLE_API_KEY=your-gemini-key-here

# Rate limiting (optional, defaults shown)
API_RATE_LIMIT_MS=500
API_MAX_RETRIES=3
```

### Step 3.3: Review Location Areas

Edit `locations.csv` if you need different service areas:

```csv
Location
Budapest 1. kerület
Budapest 2. kerület
Budapest 3. kerület
...
Érd
Budaörs
Törökbálint
```

Default includes all 23 Budapest districts + 25 surrounding towns.

---

## 4. Phase 1: Configuration

Start Claude Code and begin the conversation:

```bash
claude
```

Claude will ask you questions. Example conversation:

```
Claude: "Welcome! Let's set up your local business website.

         First, which LLM provider would you like to use?
         1. OpenAI (gpt-4o-mini) - Recommended
         2. Google Gemini (gemini-2.0-flash)"

You: "1"

Claude: "Great! I found your OpenAI API key in .env.

         What is your business name?"

You: "Asztalosmester Budapest"

Claude: "What industry are you in? (e.g., asztalos, villanyszerelő, térkövező)"

You: "padlóburkoló"

Claude: "Mi a fő kulcsszó, amire a weboldalt optimalizálni szeretnéd? (opcionális)
         Ez lehet specifikusabb, mint az iparág.
         Pl. iparág: 'padlóburkoló', fő kulcsszó: 'vinyl padló lerakás'
         Ha kihagyod, az iparágat használjuk."

You: "vinyl padló lerakás"
# VAGY üres enter, ha az iparág a fő kulcsszó

Claude: "What services do you offer? (Enter each service name)
         These will become individual pages on your site."

You: "Konyhabútor készítés"
You: "Beépített szekrény"
You: "Gardróbszekrény"
You: "Egyedi bútorok"

Claude: "What is your phone number?"

You: "+36301234567"

Claude: "What is your email address?"

You: "info@asztalosmesterbudapest.hu"

Claude: "What is your business address?"

You: "Budapest, Váci út 123., 1134"

Claude: "What is your primary brand color? (hex code)"

You: "#8B4513"

Claude: "What is your tagline/slogan?"

You: "Minőségi bútorok, megbízható kivitelezés"

Claude: "Van-e speciális szakterületi tudás, amit szeretnél megadni? (opcionális)
         Például: anyagok, technikák, márkák, szabványok.
         Ha kihagyod, az LLM a saját tudását használja az iparágról."

You: "Faanyagok: tölgy, bükk, fenyő, MDF. Vasalatok: Blum, Hettich."
# VAGY egyszerűen üres enter a kihagyáshoz
```

At the end of Phase 1, Claude shows your configuration:

```
=== Configuration Summary ===

Business: Asztalosmester Budapest
Industry: asztalos
Phone: +36301234567
Email: info@asztalosmesterbudapest.hu
Address: Budapest, Váci út 123., 1134

Services:
  1. Konyhabútor készítés (konyhabutor-keszites)
  2. Beépített szekrény (beepitett-szekreny)
  3. Gardróbszekrény (gardrobszekreny)
  4. Egyedi bútorok (egyedi-butorok)

Locations: 48 areas from locations.csv
Brand Color: #8B4513
Tagline: Minőségi bútorok, megbízható kivitelezés

Proceed to content generation? (yes/no)
```

---

## 5. Phase 2: Content Generation

After confirmation, Claude generates all content with progress tracking:

```
=== Content Generation ===
LLM Provider: OpenAI (gpt-4o-mini)
Rate Limit: 500ms between calls

[1/67] Homepage leader...              ✓ Complete
[2/67] Homepage text...                ✓ Complete
[3/67] Service: Konyhabútor leader...  ✓ Complete
[4/67] Service: Konyhabútor intro...   ⏳ Generating...
[5/67] Service: Konyhabútor text...    ○ Pending
...

Progress: ████████░░░░░░░░░░░░ 12%
Estimated remaining: 15 minutes
```

### What Gets Generated

| Content Type | Count | Source Prompt |
|--------------|-------|---------------|
| Homepage content | 2 | `prompts/home/` |
| Service pages | 16+ | `prompts/service/` |
| Location pages | 48+ | `prompts/location/` |
| Meta descriptions | 60+ | `prompts/meta/` |
| Site content | 4 | `prompts/site/` |
| About page | 3 | `prompts/about/` |
| Contact page | 2 | `prompts/contact/` |

---

## 6. Phase 3: Template Population

After content generation, Claude creates the output:

```
=== Building Static Site ===

Creating folder structure...
  ✓ output/
  ✓ output/szolgaltatasaink/
  ✓ output/konyhabutor-keszites/
  ✓ output/beepitett-szekreny/
  ...

Generating pages...
  ✓ output/index.html
  ✓ output/szolgaltatasaink/index.html
  ✓ output/konyhabutor-keszites/index.html
  ...

Generating SEO files...
  ✓ output/sitemap.xml (67 URLs)
  ✓ output/robots.txt

Copying assets...
  ✓ output/assets/css/
  ✓ output/assets/js/
  ✓ output/assets/images/

=== Build Complete ===
Total pages: 67
Output directory: output/
```

### Clean URL Structure

All pages use folder-based URLs (no `.html` in URLs):

```
output/
├── index.html                              → /
├── szolgaltatasaink/
│   └── index.html                          → /szolgaltatasaink/
├── konyhabutor-keszites/
│   └── index.html                          → /konyhabutor-keszites/
├── asztalos-budapest-2-kerulet/
│   └── index.html                          → /asztalos-budapest-2-kerulet/
└── ...
```

---

## 7. Phase 4: Deployment

### Step 7.1: Local Preview

```bash
cd output
python3 -m http.server 8080
```

Open `http://localhost:8080` and verify:
- All pages load
- Navigation works
- Phone numbers correct
- Content looks good

### Step 7.2: Deploy to GitHub Pages

```bash
cd output

# Initialize git
git init
git add .
git commit -m "Initial commit: Asztalosmester Budapest website"

# Create GitHub repo and push
gh repo create "asztalosmesterbudapest.hu" --public --source=. --remote=origin --push
```

### Step 7.3: Enable GitHub Pages

1. Go to repository on GitHub
2. **Settings** → **Pages**
3. Source: **Deploy from a branch**
4. Branch: **main** / **/ (root)**
5. Click **Save**

Site available at: `https://YOUR-USERNAME.github.io/asztalosmesterbudapest.hu/`

### Step 7.4: Custom Domain (Optional)

1. Create CNAME file:
```bash
echo "asztalosmesterbudapest.hu" > CNAME
git add CNAME && git commit -m "Add custom domain" && git push
```

2. Configure DNS at your registrar:
```
A Records:
@     A     185.199.108.153
@     A     185.199.109.153
@     A     185.199.110.153
@     A     185.199.111.153

CNAME Record:
www   CNAME   YOUR-USERNAME.github.io.
```

3. Enable HTTPS in GitHub Pages settings

---

## 8. Customization

### Modify Prompts

Edit files in `/prompts/` to change content style:

```
prompts/
├── master-prompts.md     # Master instructions
├── home/                 # Homepage prompts
├── service/              # Service page prompts
├── location/             # Location page prompts
└── ...
```

### Change Service Areas

Edit `locations.csv`:

```csv
Location
Debrecen 1. kerület
Debrecen 2. kerület
Hajdúszoboszló
Nyíregyháza
```

### Update Branding

Modify during Phase 1 or edit `config.json` directly:

```json
{
  "branding": {
    "colors": {
      "primary": "#27ae60",
      "button": "#f1c40f"
    }
  }
}
```

---

## 9. Troubleshooting

### API Key Not Found

```
Error: OPENAI_API_KEY not found in .env
```

**Solution:** Ensure `.env` file exists and contains your API key.

### Rate Limit Errors

```
Error: 429 Too Many Requests
```

**Solution:** Increase `API_RATE_LIMIT_MS` in `.env` (try 1000ms).

### Missing Content

```
Warning: Could not generate content for [page]
```

**Solution:** Check API key balance, retry generation for specific page.

### Broken Internal Links

**Solution:** Ensure all links use folder paths:
- Correct: `/szolgaltatasaink/`
- Wrong: `/szolgaltatasaink.html`

---

## File Reference

| File | Purpose |
|------|---------|
| `.env.example` | Environment template |
| `.env` | Your API keys (gitignored) |
| `config.json` | Business configuration |
| `locations.csv` | Service areas list |
| `prompts/` | Content generation prompts |
| `templates/` | HTML page templates |
| `output/` | Generated static site |
| `docs/` | Documentation |

---

## Support

- Check `docs/` for detailed documentation
- Review `docs/troubleshooting.md` for common issues
- Prompts are in Hungarian - review `prompts/master-prompts.md`

---

**Version:** 2.0
**Last Updated:** 2024-12-18
**Workflow:** Pure Claude Code (no CMS)
