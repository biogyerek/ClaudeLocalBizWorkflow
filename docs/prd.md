# Product Requirements Document: Claude Local Business Workflow

## Executive Summary

A self-contained workflow repository that enables generating SEO-optimized Hungarian local business websites through pure Claude Code conversation. No CMS dependency - generates static HTML directly from prompts and templates.

---

## Problem Statement

Creating local business websites in Hungary requires:
- Technical expertise in web development
- Understanding of local SEO best practices
- Content creation in Hungarian
- Managing multiple pages (services + 48+ locations)

Current solutions either require CMS setup (Kirby, WordPress) or expensive agencies.

---

## Solution

A clone-and-run repository that:
1. Collects business information through Claude Code conversation
2. Generates all content using LLM APIs (OpenAI/Gemini)
3. Outputs deployment-ready static HTML with clean URLs
4. Deploys to GitHub Pages (free hosting)

---

## Target Users

- Web developers building sites for Hungarian local businesses
- Small business owners with basic technical skills
- Digital marketing agencies handling multiple clients

---

## User Stories

### US-1: Repository Setup
**As a** developer
**I want to** clone the repo and start immediately
**So that** I can begin generating a website without complex setup

**Acceptance Criteria:**
- Clone repository works without errors
- Copy `.env.example` to `.env` and add API key
- Run Claude Code and start Phase 1

### US-2: LLM Provider Selection
**As a** user
**I want to** choose my LLM provider (OpenAI or Gemini)
**So that** I can use the API I have access to

**Acceptance Criteria:**
- Claude asks which provider to use in Phase 1
- System validates API key exists in `.env`
- Choice is stored in `config.json`

### US-3: Business Configuration
**As a** user
**I want to** input business details through conversation
**So that** I don't need to edit configuration files manually

**Acceptance Criteria:**
- Claude asks for: business name, industry, phone, email, address
- Claude asks for services (user enters names like "Konyhabútor készítés")
- Service slugs generated from user input (e.g., "konyhabutor-keszites")
- All data stored in `config.json`

### US-4: Location Areas
**As a** user
**I want to** specify service areas via CSV
**So that** location pages are generated for each area

**Acceptance Criteria:**
- Default `locations.csv` includes Budapest districts + surrounding towns
- User can edit CSV to customize areas
- Each location generates a separate page

### US-5: Content Generation with Progress
**As a** user
**I want to** see generation progress and estimated time
**So that** I know how long to wait

**Acceptance Criteria:**
- Progress shows: `[12/47] Generating: homepage leader... ✓`
- Progress bar: `████████░░░░░░░░░ 25%`
- Estimated remaining time displayed
- Rate limiting prevents API throttling

### US-6: Clean URLs
**As a** user
**I want** URLs without `.html` extensions
**So that** the site looks professional

**Acceptance Criteria:**
- All pages use folder structure: `page/index.html`
- URLs render as `/page/` not `/page.html`
- Works on GitHub Pages without `.htaccess`

### US-7: Static Output
**As a** user
**I want** deployment-ready static files
**So that** I can host anywhere for free

**Acceptance Criteria:**
- `output/` contains all HTML, CSS, JS, images
- `robots.txt` and `sitemap.xml` generated
- Files can be uploaded to any static host

---

## Functional Requirements

### FR-1: Configuration Management

| Requirement | Description |
|-------------|-------------|
| FR-1.1 | Read API keys from `.env` file |
| FR-1.2 | Store LLM choice in `config.json` |
| FR-1.3 | Generate service slugs from user input |
| FR-1.4 | Validate all required fields before Phase 2 |

### FR-2: Content Generation

| Requirement | Description |
|-------------|-------------|
| FR-2.1 | Load prompts from `/prompts/` directory |
| FR-2.2 | Substitute `{{variables}}` with config values |
| FR-2.3 | Call LLM API with rate limiting (default 500ms) |
| FR-2.4 | Retry failed calls up to 3 times |
| FR-2.5 | Display progress with completion percentage |
| FR-2.6 | Store generated content in `/generated/` |

### FR-3: Template Population

| Requirement | Description |
|-------------|-------------|
| FR-3.1 | Load HTML templates from `/templates/` |
| FR-3.2 | Inject generated content into templates |
| FR-3.3 | Create folder structure for clean URLs |
| FR-3.4 | Generate sitemap.xml with all pages |

### FR-4: Output Generation

| Requirement | Description |
|-------------|-------------|
| FR-4.1 | Output to `/output/` directory |
| FR-4.2 | Include all assets (CSS, JS, images) |
| FR-4.3 | Generate robots.txt |
| FR-4.4 | Provide deployment instructions |

---

## Non-Functional Requirements

### NFR-1: Performance

- API calls rate-limited to prevent throttling
- Generation of 50 pages completes in under 30 minutes
- Progress updates in real-time

### NFR-2: Usability

- Self-explanatory conversation flow
- Clear error messages
- Progress indicators for long operations

### NFR-3: Portability

- Works on Windows, macOS, Linux
- No dependencies beyond Claude Code
- Output hostable on any static server

### NFR-4: SEO Compliance

- Unique meta descriptions per page
- Clean URL structure
- Proper heading hierarchy
- Schema.org markup included

---

## Generated Page Types

| Page Type | Count | URL Pattern |
|-----------|-------|-------------|
| Homepage | 1 | `/` |
| Services List | 1 | `/szolgaltatasaink/` |
| Individual Services | 4+ | `/[service-slug]/` |
| Locations List | 1 | `/szolgaltatasi-teruletek/` |
| Location Pages | 48+ | `/[industry]-[location]/` |
| About | 1 | `/rolunk/` |
| Contact | 1 | `/kapcsolat/` |
| Quote Request | 1 | `/ajanlatkeres/` |
| FAQ | 1 | `/gyik/` |
| Privacy | 1 | `/adatvedelmi-nyilatkozat/` |

**Total: ~60+ pages**

---

## Prompt System

Prompts are stored in `/prompts/` with Hungarian instructions:

```
prompts/
├── master-prompts.md      # Master configuration
├── home/
│   ├── leader.md          # Hero section
│   └── text.md            # Main content
├── service/
│   ├── leader.md          # Service hero
│   ├── intro.md           # Introduction
│   ├── description.md     # Meta description
│   └── text.md            # Full content
├── location/
│   ├── leader.md          # Location hero
│   ├── intro.md           # Introduction
│   ├── description.md     # Meta description
│   └── text.md            # Full content
└── meta/
    ├── home.md            # Homepage meta
    ├── service.md         # Service meta
    └── location.md        # Location meta
```

---

## Success Metrics

| Metric | Target |
|--------|--------|
| Setup time | < 5 minutes |
| Phase 1 completion | < 15 minutes |
| Total generation time | < 60 minutes |
| Generated pages | 60+ unique pages |
| SEO score (Lighthouse) | > 90 |

---

## Risks and Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| API rate limiting | Generation fails | Built-in rate limiting + retries |
| API costs | Unexpected charges | Cost estimate shown before generation |
| Content quality | Poor SEO | Hungarian-native prompts from Kirby |
| URL issues | Broken links | Folder-based structure, no server config |

---

## Out of Scope

- Real-time content editing
- CMS admin panel
- Database storage
- Server-side forms (use webhooks)
- Multi-language support (Hungarian only)
- E-commerce functionality

---

## Dependencies

| Dependency | Version | Purpose |
|------------|---------|---------|
| Claude Code | Latest | Workflow execution |
| OpenAI API | GPT-4o | Content generation (option 1) |
| Gemini API | 2.0 Flash | Content generation (option 2) |
| GitHub Pages | - | Free hosting |

---

## Version

- **Document Version:** 1.0
- **Last Updated:** 2024-12-18
- **Status:** Draft
