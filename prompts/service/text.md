# Service Page - Main Text

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, amely a {{business.industry}} iparágban tevékenykedik, és a következő szolgáltatásokat nyújtja: {{services_list}}.

Írd meg a szolgáltatás oldal fő szövegét, amelynek címe: {{service.name}}.

{{service_page_prompt}}
{{text_prompt}}
{{master_prompt}}
{{knowledge_prompt}}

Vedd figyelembe a következő példatartalmat és formázást.

---PÉLDATARTALOM---
{{example_service_page}}
---PÉLDATARTALOM VÉGE---

A választ magyar nyelven add meg.

## Output Format

- Multiple sections with H2/H3 headings
- Comprehensive service information
- Call-to-action elements
- HTML formatting allowed
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `service.name` - Current service name
- `service_page_prompt` - Page instructions
- `text_prompt` - Text formatting rules
- `master_prompt` - Master instructions
- `knowledge_prompt` - Domain knowledge
- `example_service_page` - Example content
