# Location Page - Introduction

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}}.

Írd meg a bevezető szöveget ehhez a település oldalhoz, amelynek címe {{location.name}}.

{{service_intro_prompt}}
{{master_prompt}}

Használd az alábbi példa tartalmat az írási stílus iránymutatásaként.

---PÉLDA TARTALOM---
{{example_service_intro}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- 1-2 paragraphs introducing services in this location
- Location-specific content
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `location.name` - Location name
- `service_intro_prompt` - Introduction instructions
- `master_prompt` - Master instructions
- `example_service_intro` - Example intro
