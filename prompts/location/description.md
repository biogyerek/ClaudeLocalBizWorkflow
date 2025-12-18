# Location Page - Description

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}}.

Írd meg a részletes leírást a szolgáltatásokról ezen a területen: {{location.name}}.

{{service_description_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_service_description}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Location-specific service description
- Why this location is served
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `location.name` - Location name
- `service_description_prompt` - Description instructions
- `master_prompt` - Master instructions
- `example_service_description` - Example
