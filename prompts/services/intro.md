# Services Listing Page - Introduction

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik.

Írd meg a bevezető szöveget a szolgáltatások oldalhoz.

{{services_intro_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_services_intro}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Introduction paragraph
- Overview of services offered
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_intro_prompt` - Intro instructions
- `master_prompt` - Master instructions
- `example_services_intro` - Example intro
