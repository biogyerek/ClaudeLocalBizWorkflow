# Location Page - Main Text

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}}.

Írd meg a fő szöveget ehhez a település oldalhoz, amelynek címe {{location.name}}.

{{service_page_prompt}}
{{text_prompt}}
{{master_prompt}}
{{knowledge_prompt}}

Használd az alábbi példa tartalmat és formázást referenciaként.

---PÉLDA TARTALOM---
{{example_service_page}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Location-specific content
- Mention services available
- Contact information reference
- HTML formatting allowed
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `location.name` - Location name
- `service_page_prompt` - Page instructions
- `text_prompt` - Text formatting
- `master_prompt` - Master instructions
- `knowledge_prompt` - Domain knowledge
- `example_service_page` - Example content
