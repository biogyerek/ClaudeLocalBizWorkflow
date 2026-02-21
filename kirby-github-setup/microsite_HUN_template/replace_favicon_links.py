#!/usr/bin/env python3
"""
Replace old favicon links with new favicon.png
"""
import re
from pathlib import Path

HTML_DIR = Path("../melegburkolobudapest.hu")

def replace_favicon_links(filepath):
    """Replace old favicon links with new one"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content

    # Remove old favicon links
    # Pattern to match the entire favicon link tags
    pattern = r'<link href="https://melegburkolobudapest\.hu/media/assets/content/[^"]+/favicon-[^"]+\.png"[^>]+>'

    # Find all matches first
    matches = re.findall(pattern, content)

    if matches:
        # Remove all old favicon links
        for match in matches:
            content = content.replace(match, '')

        # Add new favicon link before </head>
        new_favicon = '<link rel="icon" type="image/png" href="https://melegburkolobudapest.hu/media/site/d086b175c8-1765289545/favicon.png">'
        content = content.replace('</head>', f'\t{new_favicon}\n</head>')

        # Save the file
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)

        print(f"  + Replaced {len(matches)} favicon links: {filepath.name}")
        return True
    else:
        print(f"  - No old favicon links found: {filepath.name}")
        return False

def main():
    """Process all HTML files"""
    html_files = list(HTML_DIR.glob("*.html"))
    print(f"Found {len(html_files)} HTML files\n")

    modified = 0
    for filepath in sorted(html_files):
        if replace_favicon_links(filepath):
            modified += 1

    print(f"\nDone! Modified {modified} files")

if __name__ == "__main__":
    main()
