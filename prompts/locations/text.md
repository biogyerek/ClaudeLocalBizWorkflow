# Locations Listing Page - Main Text

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}}.

Írd meg a fő szöveget a szolgáltatási területek oldalhoz.

{{service_areas_text_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_service_areas_page}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Overview of all service areas
- Organized by region if applicable
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `service_areas_text_prompt` - Text instructions
- `master_prompt` - Master instructions
- `example_service_areas_page` - Example page
