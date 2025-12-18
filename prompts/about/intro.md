# About Page - Introduction

## Prompt (Hungarian)

Ön egy helyi vállalkozó, aki a saját weboldalára ír. A cége neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik.

Írja meg a bemutatkozó szöveget a rólunk oldalhoz.

{{about_page_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_about_intro}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Company introduction
- Company story/history
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `about_page_prompt` - About page instructions
- `master_prompt` - Master instructions
- `example_about_intro` - Example intro
