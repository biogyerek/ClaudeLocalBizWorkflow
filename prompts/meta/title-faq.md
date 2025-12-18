# Meta Title - FAQ Page

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta title-t a {{business.name}} GYIK oldalához.

Adatok:
- Cég: {{business.name}}
- Iparág: {{business.industry}}
- Város: {{business.address.city}}

A title legyen:
- Maximum 60 karakter
- Formátum: Gyakori Kérdések | [Iparág] [Város] - [Cégnév]
- Magyar nyelven

## Output Format

- Single line title
- 50-60 characters

## Example Outputs

```
Gyakori Kérdések | Asztalos Budapest - GYIK
GYIK - Asztalosmester | Bútorkészítés Kérdések
Kérdések és Válaszok | Villám Elektro
```

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `business.address.city` - City
