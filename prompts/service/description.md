# Service Page - Description

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}}.

Írd meg a részletes leírást ehhez a szolgáltatáshoz: {{service.name}}.

Felhasználandó információ a szolgáltatásról:
{{service.description}}

{{service_description_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_service_description}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Detailed service description
- Benefits and features
- Why choose this service
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `service.name` - Current service name
- `service.description` - Brief service description from config
- `service_description_prompt` - Description instructions
- `master_prompt` - Master instructions
- `example_service_description` - Example description
