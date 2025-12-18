# Master Prompts Configuration

These prompts are used across all content generation. Customize them for your business.

## master_prompt

The main instruction appended to all content generation:

```
Írj természetes, ember által írtnak tűnő tartalmat. Kerüld a túlzottan értékesítési hangnemet.
Használj változatos mondatszerkezeteket. Ne ismételj kulcsszavakat túl gyakran.
A tartalom legyen informatív és hasznos az olvasó számára.
Ne használj emoji-kat vagy túl sok felkiáltójelet.
```

## format_leader

Formatting rules for leader/intro text:

```
- Maximum 2-3 rövid mondat
- Ragadja meg az olvasó figyelmét
- Egyértelmű és lényegre törő
- Ne használjon túl sok jelzőt
```

## text_prompt

General text formatting rules:

```
- Használj H2 és H3 alcímeket a tartalom strukturálásához
- Minden bekezdés maximum 3-4 mondat
- Használj felsorolásokat ahol releváns
- A tartalom legyen könnyen átfutható (scannable)
```

## knowledge_prompt

Domain-specific knowledge to include (optional - asked in Phase 1):

```
{{business.domain_knowledge}}
```

**How it works:**
- Phase 1 asks: "Van-e speciális szakterületi tudás, amit szeretnél megadni? (opcionális)"
- If user provides info → used in content generation
- If user skips → LLM uses its own knowledge about the industry

**Example user input for carpenter:**
```
Faanyagok: tölgy, bükk, fenyő, MDF, laminált forgácslap
Felületkezelés: lakkozás, pácolás, olajozás, viaszolás
Vasalatok: Blum, Hettich, Grass márkák
Szabványok: ergonómiai magasságok, EN 1116 konyhabútor szabvány
```

**If empty, the prompt becomes:**
```
Használd a saját tudásodat a(z) {{business.industry}} iparágról,
beleértve a szakmai terminológiát, anyagokat, technikákat és bevált gyakorlatokat.
```

## Example Content Templates

### example_leader

```
Professzionális [szolgáltatás] szolgáltatásokat kínálunk [város] és környékén.
Tapasztalt csapatunk garantálja a minőségi munkát, kedvező áron.
```

### example_homepage

```
<h2>Szolgáltatásaink</h2>
<p>Cégünk széles körű [iparág] szolgáltatásokat nyújt...</p>

<h2>[Szolgáltatás 1]</h2>
<p>A [szolgáltatás 1] terén szerzett tapasztalatunk...</p>
```

### example_service_leader

```
Kiváló minőségű [szolgáltatás] szolgáltatás [város] területén.
Kérjen ingyenes árajánlatot még ma!
```

### example_service_intro

```
A [szolgáltatás] az egyik legkeresettebb szolgáltatásunk. Tapasztalt szakembereink
gondoskodnak arról, hogy minden projekt a legmagasabb színvonalon valósuljon meg.
```

### example_mission

```
Küldetésünk, hogy ügyfeleinket a lehető legjobb [iparág] szolgáltatásokkal lássuk el,
miközben fenntartjuk a minőség és a megbízhatóság legmagasabb szintjét.
```

### example_tagline

```
Minőség és megbízhatóság, amit megérdemel.
```

---

## How to Use

1. Customize the prompts above to match your brand voice
2. The prompts are automatically loaded during Phase 2
3. Variables like {{business.name}} are filled from config.json
4. Example content helps the LLM understand the desired style
