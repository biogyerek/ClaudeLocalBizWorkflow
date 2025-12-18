# Services Listing Page - Main Text

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}}.

Írd meg a szolgáltatások oldal fő szövegét.

{{services_page_prompt}}
{{text_prompt}}
{{master_prompt}}

## Output Format

- Brief description for each service
- Services will be listed with links
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `services_page_prompt` - Page instructions
- `text_prompt` - Text formatting
- `master_prompt` - Master instructions
