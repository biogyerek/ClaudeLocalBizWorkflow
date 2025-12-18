# Meta Title - Contact Page

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta title-t a {{business.name}} Kapcsolat oldalához.

Adatok:
- Cég: {{business.name}}
- Iparág: {{business.industry}}
- Város: {{business.address.city}}
- Telefon: {{business.phone}}

A title legyen:
- Maximum 60 karakter
- Formátum: Kapcsolat | [Cégnév] - [Iparág] [Város]
- Magyar nyelven

## Output Format

- Single line title
- 50-60 characters

## Example Outputs

```
Kapcsolat | Asztalosmester - Budapest
Elérhetőségek - Villám Elektro Budapest
Kapcsolatfelvétel | Asztalosmester Kft.
```

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `business.address.city` - City
