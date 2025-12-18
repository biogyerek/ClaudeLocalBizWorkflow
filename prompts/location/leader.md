# Location Page - Leader Text

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}} a {{business.address.city}} területén.

Írd meg a bevezető szöveget ehhez a település oldalhoz, amelynek címe {{location.name}}.

{{service_page_prompt}}
{{master_prompt}}

Kövesd az alábbi formázási szabályokat, és használd a példaszöveget a stílus iránymutatásaként.

---FORMÁZÁSI SZABÁLYOK---
{{format_service_leader}}
---FORMÁZÁSI SZABÁLYOK VÉGE---

---PÉLDA TARTALOM---
{{example_service_leader}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- 2-3 sentences specific to this location
- Mention the location name naturally
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry (used in slug: asztalos-budapest)
- `services_list` - All services
- `business.address.city` - Primary city
- `location.name` - Current location name
- `service_page_prompt` - Page instructions
- `master_prompt` - Master instructions
- `format_service_leader` - Formatting rules
- `example_service_leader` - Example content
