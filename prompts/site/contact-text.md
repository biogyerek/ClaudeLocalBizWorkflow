# Site - Contact Section Text

## Prompt (Hungarian)

Ön egy helyi vállalkozó, aki a saját weboldalára ír. A cége neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik.

Írja meg a kapcsolatfelvételi szekció szövegét.

Elérhetőségek:
- Telefon: {{business.phone}}
- Email: {{business.email}}

{{contact_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_contact}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Short contact call-to-action
- Friendly and inviting
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `business.phone` - Phone
- `business.email` - Email
- `contact_prompt` - Contact instructions
- `master_prompt` - Master instructions
- `example_contact` - Example text
