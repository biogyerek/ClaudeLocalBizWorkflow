# Quote Request Page - Main Text

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}}.

Írd meg az árajánlat oldal fő szövegét.

{{estimate_page_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_estimate_page}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Why get a quote
- What to expect
- Form introduction
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `estimate_page_prompt` - Page instructions
- `master_prompt` - Master instructions
- `example_estimate_page` - Example content
