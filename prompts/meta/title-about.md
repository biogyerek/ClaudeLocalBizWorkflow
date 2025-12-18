# Meta Title - About Page

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta title-t a {{business.name}} Rólunk oldalához.

Adatok:
- Cég: {{business.name}}
- Iparág: {{business.industry}}
- Város: {{business.address.city}}

A title legyen:
- Maximum 60 karakter
- Formátum: Rólunk - [Cégnév] | [Iparág] [Város]
- Magyar nyelven

## Output Format

- Single line title
- 50-60 characters

## Example Outputs

```
Rólunk - Asztalosmester | Asztalos Budapest
Cégünkről - Villám Elektro Budapest
Bemutatkozás | Asztalosmester Kft.
```

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `business.address.city` - City
