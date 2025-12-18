# Site - Company Backstory

## Prompt (Hungarian)

Ön egy helyi vállalkozó, aki a saját weboldalára ír. A cége neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtja: {{services_list}} a következő helyen: {{business.address.city}}.

Írja meg a cég háttértörténetét.

{{profile_prompt}}
{{master_prompt}}

## Output Format

- 2-3 paragraphs about company history
- Founding story
- Growth and development
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `business.address.city` - City
- `profile_prompt` - Profile instructions
- `master_prompt` - Master instructions
