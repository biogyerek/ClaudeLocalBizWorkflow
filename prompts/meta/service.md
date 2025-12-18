# Meta Description - Service Page

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta leírást a {{service.name}} szolgáltatás oldalához.

Cég: {{business.name}}
Iparág: {{business.industry}}
Hely: {{business.address.city}}

A meta leírás legyen:
- Pontosan 150-160 karakter
- Tartalmazza a szolgáltatás nevét: {{service.name}}
- Tartalmazza a helyet: {{business.address.city}}
- Egyedi (ne ismételje más oldalak meta leírását)
- Magyar nyelven

## Output Format

- Single line meta description
- 150-160 characters
- Service-specific keywords
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `business.address.city` - City
- `service.name` - Service name
