# Meta Title - Locations List Page

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta title-t a {{business.name}} Szolgáltatási Területek oldalához.

Adatok:
- Cég: {{business.name}}
- Iparág: {{business.industry}}
- Fő város: {{business.address.city}}

A title legyen:
- Maximum 60 karakter
- Formátum: [Iparág] [Fő város] és Környéke | [Cégnév]
- Magyar nyelven

## Output Format

- Single line title
- 50-60 characters

## Example Outputs

```
Asztalos Budapest és Környéke | Asztalosmester
Szolgáltatási Területeink | Villám Elektro
Villanyszerelő Pest Megye - Elérhető Települések
```

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `business.address.city` - Main city
