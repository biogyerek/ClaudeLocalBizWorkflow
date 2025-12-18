# Service Page - Leader Text

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, amely a {{business.industry}} iparágban tevékenykedik, és a következő szolgáltatásokat nyújtja: {{services_list}} a következő területen: {{business.address.city}}.

Írd meg a bevezető szöveget ehhez a szolgáltatás oldalhoz, amelynek címe: {{service.name}}.

{{service_page_prompt}}
{{master_prompt}}

Kövesd az alábbi formázási szabályokat, és vedd figyelembe a példaszöveget az írási stílushoz.

---FORMÁZÁSI SZABÁLYOK---
{{format_service_leader}}
---FORMÁZÁSI SZABÁLYOK VÉGE---

---PÉLDATARTALOM---
{{example_service_leader}}
---PÉLDATARTALOM VÉGE---

A választ magyar nyelven add meg.

## Output Format

- 2-3 impactful sentences about this specific service
- No markdown formatting
- Plain text only
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry/trade
- `services_list` - All services offered
- `business.address.city` - Primary city
- `service.name` - Current service name
- `service_page_prompt` - Service page instructions
- `master_prompt` - Master instructions
- `format_service_leader` - Formatting rules
- `example_service_leader` - Example for style
