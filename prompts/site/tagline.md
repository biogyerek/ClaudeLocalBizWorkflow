# Site - Tagline/Slogan

## Prompt (Hungarian)

Ön egy helyi vállalkozó, aki a saját weboldalára ír. A cége neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtja: {{services_list}} a következő helyen: {{business.address.city}}.

Írja meg a szlogen szövegét.

{{tagline_prompt}}
{{master_prompt}}

Használja az alábbi példa tartalmat és formázást referenciaként.

---PÉLDA TARTALOM KEZDETE---
{{example_tagline}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- 1 short, memorable tagline
- Maximum 10 words
- Captures company essence
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `business.address.city` - Location
- `tagline_prompt` - Tagline instructions
- `master_prompt` - Master instructions
- `example_tagline` - Example tagline
