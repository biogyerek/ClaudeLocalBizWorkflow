# Meta Title - Homepage

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta title-t a {{business.name}} kezdőlapjához.

Adatok:
- Cég: {{business.name}}
- Fő kulcsszó: {{seo.primary_keyword}}
- Iparág: {{business.industry}}
- Város: {{business.address.city}}
- Szolgáltatások: {{services_list}}

A title legyen:
- Maximum 60 karakter (Google levágja utána)
- Formátum: [Fő kulcsszó] [Város] | [Cégnév]
- A FŐ KULCSSZÓT használd, NE az iparágat (ha különbözik)
- Magyar nyelven

## Output Format

- Single line title
- 50-60 characters
- Primary keyword at the beginning (NOT industry if different)
- Brand name at the end (after | or -)

## Example Outputs

```
Vinyl Padló Lerakás Budapest | PadlóMester
Konyhabútor Készítés Budapest | Asztalosmester
Tetőfedés és Javítás Budaörs | TetőPro
```

## Variables Required

- `seo.primary_keyword` - Main keyword to optimize for
- `business.name` - Company name
- `business.address.city` - City

## Note

Ha a `seo.primary_keyword` nincs megadva, használd a `business.industry`-t.
