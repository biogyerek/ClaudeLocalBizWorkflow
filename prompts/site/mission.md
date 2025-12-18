# Site - Mission Statement

## Prompt (Hungarian)

Ön egy helyi vállalkozó, aki a saját weboldalára ír. A cége neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtja: {{services_list}} a következő területen: {{business.address.city}}.

Írja meg a küldetésnyilatkozat szövegét.

{{mission_prompt}}
{{master_prompt}}

Használja az alábbi példa tartalmat és formázást referenciaként.

---PÉLDA TARTALOM KEZDETE---
{{example_mission}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- 2-4 sentences mission statement
- Company values
- Customer commitment
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `business.address.city` - City
- `mission_prompt` - Mission instructions
- `master_prompt` - Master instructions
- `example_mission` - Example mission
