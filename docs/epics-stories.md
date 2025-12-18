# Epics & Stories: Claude Local Business Workflow

## Overview

This document breaks down the implementation into epics (major features) and stories (specific deliverables).

---

## Epic 1: Repository Foundation

**Goal:** Create a self-contained, clone-and-run repository structure

### Story 1.1: Directory Structure
**As a** developer
**I want** a well-organized repository
**So that** I can understand and extend the codebase

**Tasks:**
- Create `/prompts/` directory with all subdirectories
- Create `/templates/` directory structure
- Create `/assets/` directory with CSS, JS, images folders
- Create `/docs/` directory for documentation
- Add `.gitignore` for `.env`, `output/`, `generated/`

**Acceptance Criteria:**
- All directories exist after clone
- README explains directory purpose

### Story 1.2: Environment Configuration
**As a** developer
**I want** API keys in a `.env` file
**So that** secrets are never committed

**Tasks:**
- Create `.env.example` with all variables
- Document required vs optional variables
- Add validation for API key presence

**Acceptance Criteria:**
- `.env.example` includes all variables with comments
- Copying to `.env` and adding keys allows workflow to run

### Story 1.3: Prompt Extraction
**As a** developer
**I want** prompts extracted from Kirby
**So that** content quality matches production sites

**Tasks:**
- Extract Hungarian prompts from Kirby YAML files
- Convert to markdown format
- Map Kirby variables to config.json paths
- Create master-prompts.md configuration

**Acceptance Criteria:**
- All 25+ prompt files created
- Variables use `{{path.to.value}}` syntax
- Prompts are in Hungarian

---

## Epic 2: Phase 1 - Configuration Collection

**Goal:** Collect business information through Claude Code conversation

### Story 2.1: LLM Provider Selection
**As a** user
**I want to** choose OpenAI or Gemini
**So that** I use my preferred API

**Tasks:**
- Prompt user for provider choice
- Validate API key exists in `.env`
- Store choice in `config.json`

**Acceptance Criteria:**
- Clear question with options presented
- Error message if API key missing
- Choice persisted to config

### Story 2.2: Business Information
**As a** user
**I want to** enter business details conversationally
**So that** I don't edit config files directly

**Tasks:**
- Prompt for business name
- Prompt for industry (with slug generation)
- Prompt for phone number (with formatting)
- Prompt for email address
- Prompt for physical address

**Acceptance Criteria:**
- All fields collected and validated
- Industry slug auto-generated (e.g., "Asztalos" → "asztalos")
- Phone formatted consistently

### Story 2.3: Service Collection
**As a** user
**I want to** list my services
**So that** individual pages are created

**Tasks:**
- Prompt for services (minimum 4)
- Generate slug from each service name
- Store in config.json services array

**Acceptance Criteria:**
- User enters "Konyhabútor készítés"
- System generates slug "konyhabutor-keszites"
- Config stores both name and slug

### Story 2.4: Branding Configuration
**As a** user
**I want to** set brand colors and tagline
**So that** the site matches my brand

**Tasks:**
- Prompt for primary brand color (hex)
- Prompt for button color (hex)
- Prompt for tagline

**Acceptance Criteria:**
- Colors validated as hex format
- Tagline stored for site-wide use

### Story 2.5: Configuration Validation
**As a** user
**I want** validation before content generation
**So that** I catch errors early

**Tasks:**
- Display config summary
- Validate all required fields present
- Confirm with user before Phase 2

**Acceptance Criteria:**
- Summary shows all collected data
- User must confirm to proceed
- Clear error messages for missing fields

---

## Epic 3: Phase 2 - Content Generation

**Goal:** Generate all content using LLM with progress tracking

### Story 3.1: Progress Tracking System
**As a** user
**I want** progress indicators
**So that** I know generation status

**Tasks:**
- Count total content pieces to generate
- Display current/total progress
- Show progress bar
- Display estimated time remaining

**Acceptance Criteria:**
- Format: `[12/47] Generating: homepage leader... ✓`
- Progress bar: `████████░░░░░░░░░ 25%`
- Time estimate updates as generation progresses

### Story 3.2: Rate Limiting
**As a** system
**I want** rate-limited API calls
**So that** we don't hit API limits

**Tasks:**
- Read `API_RATE_LIMIT_MS` from `.env`
- Wait between API calls
- Implement retry logic with exponential backoff
- Log rate limit errors

**Acceptance Criteria:**
- Default 500ms between calls
- Retries up to 3 times on failure
- Exponential backoff on 429 errors

### Story 3.3: Prompt Loading
**As a** system
**I want** prompts loaded from files
**So that** content is customizable

**Tasks:**
- Read prompt files from `/prompts/`
- Parse markdown format
- Extract prompt text and requirements

**Acceptance Criteria:**
- All prompts load without error
- Missing prompts logged as warnings

### Story 3.4: Variable Substitution
**As a** system
**I want** variables replaced in prompts
**So that** content is business-specific

**Tasks:**
- Parse `{{variable.path}}` syntax
- Load values from config.json
- Handle missing variables gracefully

**Acceptance Criteria:**
- `{{business.name}}` becomes actual name
- Missing variables logged, not crash

### Story 3.5: Content Storage
**As a** system
**I want** generated content stored
**So that** templates can use it

**Tasks:**
- Create `/generated/` directory
- Store content as JSON files
- Organize by page type

**Acceptance Criteria:**
- `generated/home.json` contains homepage content
- `generated/services/[slug].json` for each service
- `generated/locations/[slug].json` for each location

---

## Epic 4: Phase 3 - Template Population

**Goal:** Merge generated content with HTML templates

### Story 4.1: Template System
**As a** system
**I want** HTML templates
**So that** content has consistent structure

**Tasks:**
- Create base layout template
- Create page-specific templates
- Define placeholder syntax

**Acceptance Criteria:**
- Templates in `/templates/` directory
- `{{content.section}}` placeholders work
- Base template handles common elements

### Story 4.2: Clean URL Generation
**As a** system
**I want** folder-based output
**So that** URLs are clean

**Tasks:**
- Create folder for each page
- Place `index.html` in each folder
- Update internal links to folder paths

**Acceptance Criteria:**
- `/szolgaltatasaink/index.html` for services page
- `/konyhabutor-keszites/index.html` for service page
- All internal links work

### Story 4.3: Asset Management
**As a** system
**I want** assets included in output
**So that** site renders correctly

**Tasks:**
- Copy CSS files to output
- Copy JS files to output
- Handle image placeholders

**Acceptance Criteria:**
- `/output/assets/css/` contains styles
- `/output/assets/js/` contains scripts
- Image paths reference placeholders

### Story 4.4: SEO Files
**As a** system
**I want** SEO files generated
**So that** search engines index properly

**Tasks:**
- Generate sitemap.xml with all URLs
- Generate robots.txt
- Ensure canonical URLs correct

**Acceptance Criteria:**
- sitemap.xml lists all pages
- robots.txt allows all crawling
- No duplicate URLs in sitemap

---

## Epic 5: Phase 4 - Output & Deployment

**Goal:** Produce deployment-ready output with instructions

### Story 5.1: Output Validation
**As a** user
**I want** output validated
**So that** I know site is complete

**Tasks:**
- Check all pages generated
- Verify no broken internal links
- Report any missing content

**Acceptance Criteria:**
- Summary shows page count
- Broken links reported
- Missing content flagged

### Story 5.2: Deployment Instructions
**As a** user
**I want** deployment guide
**So that** I can publish the site

**Tasks:**
- Create GitHub Pages instructions
- Include custom domain setup
- Provide DNS configuration

**Acceptance Criteria:**
- Step-by-step instructions
- Screenshots/examples where helpful
- Common issues documented

### Story 5.3: Post-Generation Report
**As a** user
**I want** a summary report
**So that** I know what was created

**Tasks:**
- Count generated pages
- List any warnings/errors
- Provide next steps

**Acceptance Criteria:**
- Report shows: X pages, Y warnings, Z errors
- Clear action items for user
- Report saved to `/output/generation-report.md`

---

## Epic 6: Image Handling

**Goal:** Handle images with fallback behavior

### Story 6.1: Placeholder System
**As a** system
**I want** image placeholders
**So that** templates render without real images

**Tasks:**
- Create placeholder image files
- Define placeholder paths in templates
- Document image replacement process

**Acceptance Criteria:**
- Placeholders in `/assets/images/placeholders/`
- Templates reference placeholders by default
- README explains image replacement

### Story 6.2: Image Fallback Chain
**As a** system
**I want** fallback image logic
**So that** site always has images

**Tasks:**
- Check for user-provided image
- Fall back to industry placeholder
- Fall back to generic placeholder
- Log missing images

**Acceptance Criteria:**
- User image used if exists
- Industry placeholder if no user image
- Generic placeholder as last resort
- Warning logged for manual replacement

---

## Implementation Priority

### Phase A: Foundation (Required First)
1. Story 1.1: Directory Structure
2. Story 1.2: Environment Configuration
3. Story 1.3: Prompt Extraction

### Phase B: Configuration
4. Story 2.1: LLM Provider Selection
5. Story 2.2: Business Information
6. Story 2.3: Service Collection
7. Story 2.4: Branding Configuration
8. Story 2.5: Configuration Validation

### Phase C: Generation
9. Story 3.1: Progress Tracking System
10. Story 3.2: Rate Limiting
11. Story 3.3: Prompt Loading
12. Story 3.4: Variable Substitution
13. Story 3.5: Content Storage

### Phase D: Output
14. Story 4.1: Template System
15. Story 4.2: Clean URL Generation
16. Story 4.3: Asset Management
17. Story 4.4: SEO Files

### Phase E: Finalization
18. Story 5.1: Output Validation
19. Story 5.2: Deployment Instructions
20. Story 5.3: Post-Generation Report
21. Story 6.1: Placeholder System
22. Story 6.2: Image Fallback Chain

---

## Version

- **Document Version:** 1.0
- **Last Updated:** 2024-12-18
- **Status:** Draft
