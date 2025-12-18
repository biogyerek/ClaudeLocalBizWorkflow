# Meta Title - Services List Page

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta title-t a {{business.name}} Szolgáltatások oldalához.

Adatok:
- Cég: {{business.name}}
- Iparág: {{business.industry}}
- Város: {{business.address.city}}
- Szolgáltatások: {{services_list}}

A title legyen:
- Maximum 60 karakter
- Formátum: [Iparág] Szolgáltatások [Város] | [Cégnév]
- Magyar nyelven

## Output Format

- Single line title
- 50-60 characters

## Example Outputs

```
Asztalos Szolgáltatások Budapest | Asztalosmester
Villanyszerelő Szolgáltatásaink | Villám Elektro
Bútorkészítés és Asztalos Munkák - Budapest
```

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `business.address.city` - City
- `services_list` - All services
