# Meta Title - Service Page

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta title-t a {{service.name}} szolgáltatás oldalához.

Adatok:
- Szolgáltatás: {{service.name}}
- Cég: {{business.name}}
- Város: {{business.address.city}}

A title legyen:
- Maximum 60 karakter
- Formátum: [Szolgáltatás] [Város] | [Cégnév]
- A szolgáltatás neve legyen elöl
- Ne ismételje a homepage title-t
- Magyar nyelven

## Output Format

- Single line title
- 50-60 characters
- Service name first
- Location included

## Example Outputs

```
Konyhabútor Készítés Budapest | Asztalosmester
Beépített Szekrény Rendelésre | Bútor Kft.
Gardróbszekrény Egyedi Méretben - Budapest
```

## Variables Required

- `service.name` - Service name
- `business.name` - Company name
- `business.address.city` - City
