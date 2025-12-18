# Service Page - Introduction

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}}.

Írd meg a bevezető szöveget ehhez a szolgáltatás oldalhoz, amelynek címe {{service.name}}.

{{service_intro_prompt}}
{{master_prompt}}

Használd az alábbi példa tartalmat az írási stílus iránymutatásaként.

---PÉLDA TARTALOM---
{{example_service_intro}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- 1-2 paragraphs introducing the service
- Engaging and informative
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry/trade
- `services_list` - All services
- `service.name` - Current service name
- `service_intro_prompt` - Introduction instructions
- `master_prompt` - Master instructions
- `example_service_intro` - Example intro
