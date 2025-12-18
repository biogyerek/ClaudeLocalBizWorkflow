# FAQ Page - Questions and Answers

## Prompt (Hungarian)

Egy helyi vállalkozó vagy, aki a saját weboldalára ír tartalmat. A céged neve {{business.name}}, és a {{business.industry}} iparágban tevékenykedik, a következő szolgáltatásokat nyújtva: {{services_list}}.

Írj 8-10 gyakran ismételt kérdést és választ a szolgáltatásaiddal kapcsolatban.

Témák, amelyeket érinteni kell:
- Árazás és árajánlat folyamat
- Szolgáltatási terület
- Munkaidő és elérhetőség
- Garancia és minőség
- Anyagok és eszközök
- Projekt időtartam

{{faq_page_prompt}}
{{master_prompt}}

---PÉLDA TARTALOM---
{{example_faq_page}}
---PÉLDA TARTALOM VÉGE---

## Output Format

- Q&A format
- 8-10 questions with answers
- Industry-specific questions
- Hungarian language

## Variables Required

- `business.name` - Company name
- `business.industry` - Industry
- `services_list` - All services
- `faq_page_prompt` - FAQ instructions
- `master_prompt` - Master instructions
- `example_faq_page` - Example Q&A
