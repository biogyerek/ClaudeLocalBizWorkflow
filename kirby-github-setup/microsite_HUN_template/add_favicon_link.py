#!/usr/bin/env python3
"""
Add favicon link to HTML files
"""
import re
from pathlib import Path

HTML_DIR = Path("../melegburkolobudapest.hu")

# Favicon link to add
FAVICON_LINK = '<link rel="icon" type="image/png" href="/media/site/d086b175c8-1765289545/favicon.png">'

def add_favicon_to_file(filepath):
    """Add favicon link to HTML file if not present"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Check if favicon link already exists (check for the actual link tag)
    if 'rel="icon"' in content and 'media/site/d086b175c8-1765289545/favicon.png' in content:
        print(f"  Already has new favicon: {filepath.name}")
        return False

    # Find the </head> tag and insert before it
    if '</head>' in content:
        # Insert the favicon link before </head>
        content = content.replace('</head>', f'\t{FAVICON_LINK}\n</head>')

        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)

        print(f"  + Added favicon link: {filepath.name}")
        return True
    else:
        print(f"  ! No </head> tag found: {filepath.name}")
        return False

def main():
    """Process all HTML files"""
    html_files = list(HTML_DIR.glob("*.html"))
    print(f"Found {len(html_files)} HTML files\n")

    modified = 0
    for filepath in sorted(html_files):
        if add_favicon_to_file(filepath):
            modified += 1

    print(f"\nDone! Modified {modified} files")

if __name__ == "__main__":
    main()
