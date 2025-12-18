# Architecture: Claude Local Business Workflow

## Overview

A pure Claude Code workflow for generating SEO-optimized Hungarian local business websites. No CMS dependency - generates static HTML files directly through conversation with Claude.

## Core Principles

1. **Self-Contained Repository** - Clone and run, all prompts and templates included
2. **No CMS Dependency** - Pure static HTML generation via Claude Code
3. **Clean URLs** - Folder-based structure, no `.html` extensions in URLs
4. **Environment-Based Configuration** - API keys in `.env` file
5. **Rate-Limited Generation** - Progress indicators and API call throttling

---

## Directory Structure

```
claudelocalbizworkflow/
├── .env.example              # Environment template (copy to .env)
├── .env                      # API keys (gitignored)
├── config.json               # Business configuration (generated Phase 1)
├── locations.csv             # Service areas (comma-separated)
├── prompts/                  # Content generation prompts
│   ├── master-prompts.md     # Master prompt configuration
│   ├── home/                 # Homepage prompts
│   ├── service/              # Service page prompts
│   ├── location/             # Location page prompts
│   ├── about/                # About page prompts
│   ├── contact/              # Contact page prompts
│   ├── faq/                  # FAQ page prompts
│   ├── meta/                 # Meta description prompts
│   └── site/                 # Site-wide content prompts
├── templates/                # HTML templates
│   ├── base.html             # Base layout
│   ├── home.html             # Homepage template
│   ├── service.html          # Service page template
│   ├── location.html         # Location page template
│   └── ...                   # Other page templates
├── assets/                   # Static assets
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript
│   └── images/               # Image placeholders
├── output/                   # Generated static site
│   ├── index.html            # Homepage
│   ├── szolgaltatasaink/     # Services listing
│   │   └── index.html
│   ├── [service-slug]/       # Individual service pages
│   │   └── index.html
│   ├── [industry]-[location]/  # Location pages
│   │   └── index.html
│   └── ...
└── docs/                     # Documentation
    ├── architecture.md       # This file
    ├── prd.md                # Product requirements
    └── epics-stories.md      # Implementation epics
```

---

## Clean URL Strategy

### Folder-Based Structure

All pages use folder structure with `index.html` files for clean URLs:

| Page Type | Folder Path | URL |
|-----------|-------------|-----|
| Homepage | `output/index.html` | `/` |
| Services List | `output/szolgaltatasaink/index.html` | `/szolgaltatasaink/` |
| Service Page | `output/[service-slug]/index.html` | `/[service-slug]/` |
| Location Page | `output/[industry]-[location]/index.html` | `/[industry]-[location]/` |
| About | `output/rolunk/index.html` | `/rolunk/` |
| Contact | `output/kapcsolat/index.html` | `/kapcsolat/` |
| Quote | `output/ajanlatkeres/index.html` | `/ajanlatkeres/` |

### Service Slug Generation

Service slugs are derived directly from user input:

```
User Input: "Konyhabútor készítés"
→ Slug: "konyhabutor-keszites"
→ Folder: output/konyhabutor-keszites/index.html
→ URL: /konyhabutor-keszites/
```

### Location Page Slugs

Location pages combine industry + location:

```
Industry: "asztalos"
Location: "Budapest 2. kerület"
→ Slug: "asztalos-budapest-2-kerulet"
→ Folder: output/asztalos-budapest-2-kerulet/index.html
→ URL: /asztalos-budapest-2-kerulet/
```

---

## Environment Configuration

### .env File

```bash
# LLM Provider Selection (Choose ONE)
LLM_PROVIDER=openai          # Options: openai, gemini

# API Keys
OPENAI_API_KEY=sk-your-key-here
GOOGLE_API_KEY=your-gemini-key-here

# Rate Limiting
API_RATE_LIMIT_MS=500        # Delay between API calls
API_MAX_RETRIES=3            # Retry failed calls

# Optional: Analytics
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
GTM_CONTAINER_ID=GTM-XXXXXXX

# Optional: Form Webhook
WEBHOOK_URL=https://hook.eu2.make.com/your-webhook-id
```

### Loading Environment

Phase 1 reads `.env` and validates:
1. LLM_PROVIDER is set to valid value
2. Corresponding API key exists
3. Rate limit values are reasonable

---

## Phase Structure

### Phase 1: Configuration Collection

**Purpose:** Gather all business information through conversation

**Collected Data → config.json:**

```json
{
  "llm": {
    "provider": "openai",
    "model": "gpt-4o-mini"
  },
  "business": {
    "name": "Asztalosmester Budapest",
    "industry": "asztalos",
    "industry_slug": "asztalos",
    "phone": "+36301234567",
    "email": "info@example.hu",
    "address": {
      "street": "Váci út 123.",
      "city": "Budapest",
      "zip": "1134"
    }
  },
  "services": [
    {
      "name": "Konyhabútor készítés",
      "slug": "konyhabutor-keszites"
    },
    {
      "name": "Beépített szekrény",
      "slug": "beepitett-szekreny"
    }
  ],
  "branding": {
    "colors": {
      "primary": "#8B4513",
      "button": "#f1c40f",
      "text": "#333333"
    },
    "tagline": "Minőségi bútorok, megbízható kivitelezés"
  },
  "seo": {
    "primary_keyword": "vinyl padló lerakás",
    "primary_keyword_slug": "vinyl-padlo-lerakas"
  },
  "domain_knowledge": "Faanyagok: tölgy, bükk, fenyő...",
  "locations_csv": "locations.csv"
}
```

**LLM Selection (Phase 1):**

```
Claude: "Which LLM provider would you like to use for content generation?
         1. OpenAI (gpt-4o-mini) - Recommended
         2. Google Gemini (gemini-2.0-flash)

         Note: Make sure your API key is set in .env file."

User: "1"

Claude: [Validates OPENAI_API_KEY exists in .env]
        [Stores choice in config.json]
```

### Phase 2: Content Generation

**Purpose:** Generate all page content using prompts from `/prompts/`

**Rate Limiting Implementation:**

```
For each content piece:
  1. Display progress: "[3/47] Generating: szolgaltatas/konyhabutor-keszites/leader..."
  2. Call LLM API
  3. Wait API_RATE_LIMIT_MS (default: 500ms)
  4. On failure: retry up to API_MAX_RETRIES times
  5. Display: "[3/47] ✓ Complete"
```

**Progress Indicator Format:**

```
=== Content Generation Progress ===

[1/47] Homepage leader...           ✓ Complete
[2/47] Homepage text...             ✓ Complete
[3/47] Service: Konyhabútor...      ⏳ Generating...
[4/47] Service: Beépített szekrény  ○ Pending
...
[47/47] Location: Érd               ○ Pending

Progress: ████████░░░░░░░░░░░░ 17%
Estimated remaining: 8 minutes
```

### Phase 3: Template Population

**Purpose:** Merge generated content with HTML templates

**Variable Substitution:**

```
{{business.name}}     → "Asztalosmester Budapest"
{{business.phone}}    → "+36301234567"
{{service.name}}      → "Konyhabútor készítés"
{{service.slug}}      → "konyhabutor-keszites"
{{location.name}}     → "Budapest 2. kerület"
{{location.slug}}     → "budapest-2-kerulet"
```

### Phase 4: Output & Deployment

**Purpose:** Generate final static files and deploy

**Output Structure:**

```
output/
├── index.html
├── szolgaltatasaink/
│   └── index.html
├── konyhabutor-keszites/
│   └── index.html
├── beepitett-szekreny/
│   └── index.html
├── asztalos-budapest-2-kerulet/
│   └── index.html
├── asztalos-erd/
│   └── index.html
├── rolunk/
│   └── index.html
├── kapcsolat/
│   └── index.html
├── ajanlatkeres/
│   └── index.html
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── robots.txt
└── sitemap.xml
```

---

## Image Handling

### Placeholder Strategy

Images use placeholder URLs during development:

```html
<img src="/assets/images/placeholder-hero.jpg"
     alt="{{business.name}} - {{service.name}}"
     data-replace="hero">
```

### Fallback Behavior

1. Check if user-provided image exists
2. If not, use industry-specific placeholder
3. If not available, use generic placeholder
4. Log warning for manual replacement

---

## API Rate Limiting

### Configuration

```bash
# .env
API_RATE_LIMIT_MS=500      # 500ms between calls
API_MAX_RETRIES=3          # Retry failed calls 3 times
```

### Implementation

```
For each LLM API call:
  1. Check time since last call
  2. If < API_RATE_LIMIT_MS, wait remaining time
  3. Make API call
  4. On rate limit error (429):
     - Wait exponential backoff (1s, 2s, 4s)
     - Retry up to API_MAX_RETRIES
  5. On success: continue to next
  6. On permanent failure: log and skip
```

---

## Prompt System

### Prompt File Structure

Each prompt file contains:

```markdown
# [Page Type] - [Content Section]

## Prompt (Hungarian)

[The actual prompt text with {{variables}}]

## Output Format

- Expected format
- Length constraints
- Language requirements

## Variables Required

- `variable.path` - Description
```

### Variable Substitution

Prompts use `{{variable.path}}` syntax:

- `{{business.name}}` - Company name
- `{{business.industry}}` - Industry name
- `{{service.name}}` - Current service name
- `{{location.name}}` - Current location name
- `{{services_list}}` - Comma-separated services

---

## Data Flow

```
┌─────────────────┐
│  .env           │ API keys, rate limits
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Phase 1        │ User conversation → config.json
│  Configuration  │ + LLM selection
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Phase 2        │ prompts/* + config.json
│  Generation     │ → generated/*.json
│  (rate-limited) │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Phase 3        │ templates/* + generated/*
│  Population     │ → output/*/index.html
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Phase 4        │ output/* → GitHub Pages
│  Deployment     │
└─────────────────┘
```

---

## Schema.org Markup

Minden oldal tartalmaz strukturált adatokat (JSON-LD formátum).

Részletes dokumentáció:
- **[docs/schema-mapping.md](schema-mapping.md)** - Strukturált adatok
- **[docs/seo-guide.md](seo-guide.md)** - Meta tags, Open Graph, Canonical
- **[docs/heading-structure.md](heading-structure.md)** - H1-H3 struktúra szabályok

| Oldal típus | Schema típus |
|-------------|--------------|
| Homepage | `LocalBusiness` |
| Service page | `Service` |
| Location page | `LocalBusiness` + `areaServed` |
| FAQ | `FAQPage` |
| Contact | `ContactPage` |

---

## Technology Decisions

| Component | Choice | Rationale |
|-----------|--------|-----------|
| Generation | Claude Code | Direct conversation, no build tools |
| LLM | OpenAI/Gemini | User choice, both support Hungarian |
| URLs | Folder structure | Clean URLs without server config |
| Config | JSON + .env | Readable, easy to validate |
| Templates | Static HTML | Simple, no framework needed |
| Hosting | GitHub Pages | Free, HTTPS, custom domain |
| Forms | Webhook | Static-site compatible |

---

## Security Considerations

1. **API Keys** - Never committed, stored in `.env` (gitignored)
2. **No Server Code** - Static files only, no XSS vectors
3. **Form Handling** - External webhook, no data storage
4. **Rate Limiting** - Prevents API abuse and runaway costs

---

## Version

- **Document Version:** 1.0
- **Last Updated:** 2024-12-18
- **Status:** Draft
