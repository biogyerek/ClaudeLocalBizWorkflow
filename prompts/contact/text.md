# Contact Page - Main Text

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik.

Írd meg a kapcsolatfelvétel oldal fő szövegét.

Elérhetőségi adatok:
- Telefon: {{business.phone}}
- Email: {{business.email}}
- Cím: {{business.address.street}}, {{business.address.city}}

{{contact_page_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_contact_page}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Contact information
- Contact form introduction
- Office hours if applicable
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `business.phone` - Phone number
- `business.email` - Email address
- `business.address` - Full address
- `contact_page_prompt` - Page instructions
- `master_prompt` - Master instructions
- `example_contact_page` - Example content
