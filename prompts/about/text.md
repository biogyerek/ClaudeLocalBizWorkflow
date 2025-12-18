# About Page - Main Text

## Prompt (Hungarian)

Ön egy helyi vállalkozó, aki a saját weboldalára ír. A cége neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtja: {{services_list}}.

Írja meg a fő szöveget a rólunk oldalhoz.

Használja a következő háttértörténetet: {{backstory}}
Használja a következő küldetésnyilatkozatot: {{mission}}

{{about_page_prompt}}
{{text_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_about_page}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Company story
- Mission and values
- Why choose us
- HTML formatting allowed
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `backstory` - Company backstory
- `mission` - Mission statement
- `about_page_prompt` - Page instructions
- `text_prompt` - Text formatting
- `master_prompt` - Master instructions
- `example_about_page` - Example content
