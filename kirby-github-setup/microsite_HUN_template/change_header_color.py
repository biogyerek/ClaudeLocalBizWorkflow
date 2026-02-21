#!/usr/bin/env python3
"""
Change header background color to soft pastel light brown
"""
from pathlib import Path

HTML_DIR = Path("../melegburkolobudapest.hu")

# Old color (green): #27ae60
# New color (soft pastel light brown): #E6D5C3
OLD_COLOR = "#27ae60"
NEW_COLOR = "#E6D5C3"

def change_header_color(filepath):
    """Change header background color in HTML file"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Check if the old color exists
    if OLD_COLOR in content:
        # Replace the color
        content = content.replace(
            f'--color-menu-bg: {OLD_COLOR};',
            f'--color-menu-bg: {NEW_COLOR};'
        )

        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)

        print(f"  + Changed header color: {filepath.name}")
        return True
    else:
        print(f"  - Color not found: {filepath.name}")
        return False

def main():
    """Process all HTML files"""
    html_files = list(HTML_DIR.glob("*.html"))
    print(f"Found {len(html_files)} HTML files\n")
    print(f"Changing color from {OLD_COLOR} to {NEW_COLOR}\n")

    modified = 0
    for filepath in sorted(html_files):
        if change_header_color(filepath):
            modified += 1

    print(f"\nDone! Modified {modified} files")

if __name__ == "__main__":
    main()
