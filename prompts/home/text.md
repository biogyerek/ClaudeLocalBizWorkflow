# Home Page - Main Text

## Prompt (Hungarian)

Ön egy helyi vállalkozó, aki a saját weboldalára ír. A cége neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtja: {{services_list}}.

Írja meg a fő szöveget ehhez a kezdőlap oldalhoz, amelynek címe "{{page.title}}".

Tartsa be a következő cégadatokat: {{backstory}}

{{homepage_prompt}}
{{text_prompt}}
{{master_prompt}}
{{knowledge_prompt}}

Használja az alábbi példa tartalmat és formázást referenciaként.

---PÉLDA TARTALOM KEZDETE---
{{example_homepage}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Multiple paragraphs
- Include H2 headings for each service (will be linked in Phase 3)
- HTML formatting allowed
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry/trade
- `services_list` - Comma-separated list of services
- `page.title` - Page title
- `backstory` - Company backstory
- `homepage_prompt` - Homepage-specific instructions
- `text_prompt` - General text formatting rules
- `master_prompt` - Master instructions
- `knowledge_prompt` - Domain knowledge
- `example_homepage` - Example homepage content
