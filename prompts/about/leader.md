# About Page - Leader Text

## Prompt (Hungarian)

Ön egy helyi vállalkozó, aki a saját weboldalára ír. A cége neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtja: {{services_list}} a következő területen: {{business.address.city}}.

Írja meg a vezető szöveget ehhez a rólunk oldalhoz, amelynek címe: Rólunk.

{{master_prompt}}

Kövesse az alábbi formázási szabályokat, és használja a példa szöveget az írási stílus referenciaként.

---FORMÁZÁSI SZABÁLYOK KEZDETE---
{{format_leader}}
---FORMÁZÁSI SZABÁLYOK VÉGE---

---PÉLDA TARTALOM KEZDETE---
{{example_leader}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- 2-3 sentences about the company
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `business.address.city` - City
- `master_prompt` - Master instructions
- `format_leader` - Formatting rules
- `example_leader` - Example content
