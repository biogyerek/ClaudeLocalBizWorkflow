# Meta Description - Location Page

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta leírást a {{location.name}} település oldalához.

Cég: {{business.name}}
Iparág: {{business.industry}}
Szolgáltatások: {{services_list}}

A meta leírás legyen:
- Pontosan 150-160 karakter
- Tartalmazza: {{business.industry}} {{location.name}}
- Egyedi minden település oldalhoz
- Cselekvésre ösztönző
- Magyar nyelven

## Output Format

- Single line meta description
- 150-160 characters
- Location + industry keywords (e.g., "asztalos Budapest")
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry (used in slug)
- `services_list` - All services
- `location.name` - Location name
