# Contact Page - Leader Text

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}} a {{business.address.city}} területén.

Írd meg a bevezető szöveget ehhez a kapcsolatfelvétel oldalhoz, amelynek címe: Kapcsolat.

{{master_prompt}}

Kövesd az alábbi formázási szabályokat, és használd a példaszöveget a stílus iránymutatásaként.

---FORMÁZÁSI SZABÁLYOK---
{{format_leader}}
---FORMÁZÁSI SZABÁLYOK VÉGE---

---PÉLDA TARTALOM---
{{example_leader}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- 2-3 welcoming sentences
- Encourage contact
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `business.address.city` - City
- `master_prompt` - Master instructions
- `format_leader` - Formatting rules
- `example_leader` - Example content
