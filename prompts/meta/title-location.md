# Meta Title - Location Page

## Prompt (Hungarian)

Írj egy SEO-optimalizált meta title-t a {{location.name}} település oldalához.

Adatok:
- Település: {{location.name}}
- Fő kulcsszó: {{seo.primary_keyword}}
- Iparág: {{business.industry}}
- Cég: {{business.name}}
- Szolgáltatások: {{services_list}}

A title legyen:
- Maximum 60 karakter
- Formátum: [Fő kulcsszó] [Település] | [Cégnév]
- A FŐ KULCSSZÓT használd, NE az iparágat (ha különbözik)
- A település neve legyen benne
- Egyedi minden település oldalhoz
- Magyar nyelven

## Output Format

- Single line title
- 50-60 characters
- Primary keyword + Location combination
- Unique per location

## Example Outputs

```
Vinyl Padló Lerakás Budaörs | PadlóMester
Konyhabútor Készítés Érd | Asztalosmester
Vinyl Padló Budapest 2. Kerület - PadlóMester
```

## Variables Required

- `location.name` - Location name
- `seo.primary_keyword` - Main keyword to optimize for
- `business.name` - Company name

## Note

Ha a `seo.primary_keyword` nincs megadva, használd a `business.industry`-t.
