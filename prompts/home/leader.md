# Home Page - Leader Text

## Prompt (Hungarian)

Ön egy helyi vállalkozó, aki a saját weboldalára ír. A cége neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtja: {{services_list}} a következő területen: {{business.address.city}}.

Írja meg a vezető szöveget ehhez a kezdőlap oldalhoz, amelynek címe "{{page.title}}".

{{master_prompt}}

Kövesse a formázási szabályokat és használja a példa szöveget az írási stílushoz.

---FORMÁZÁSI SZABÁLYOK KEZDETE---
{{format_leader}}
---FORMÁZÁSI SZABÁLYOK VÉGE---

---PÉLDA TARTALOM KEZDETE---
{{example_leader}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- 2-3 short, impactful sentences
- No markdown formatting
- Plain text only
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry/trade
- `services_list` - Comma-separated list of services
- `business.address.city` - Primary city
- `page.title` - Page title (e.g., "Főoldal")
- `master_prompt` - Master instructions (from config)
- `format_leader` - Formatting rules for leader text
- `example_leader` - Example leader text for style reference
