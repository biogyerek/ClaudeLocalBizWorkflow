#!/usr/bin/env python3
"""
Remove Magunkról from main menu (not from footer)
"""

import os
import sys
import re
from pathlib import Path

def remove_magunkrol_from_menu(html_file):
    """Remove Magunkról menu item from main navigation"""

    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Find the nav menu section
    nav_start = content.find('<nav class="menu"')
    nav_end = content.find('</nav>', nav_start)

    if nav_start == -1 or nav_end == -1:
        return False

    nav_section = content[nav_start:nav_end + 6]

    # Pattern to find the Magunkról menu item
    # Looking for: <li class="menu-item">\n        <a class="" href="magunkrol.html">Magunkról</a>\n      </li>
    pattern = r'\s*<li class="menu-item">\s*<a[^>]*href="magunkrol\.html"[^>]*>.*?</a>\s*</li>\s*\n'

    # Check if pattern exists in nav section
    if re.search(pattern, nav_section, re.DOTALL):
        # Remove it from nav section only
        nav_section_new = re.sub(pattern, '', nav_section, flags=re.DOTALL)

        # Replace nav section in original content
        content = content[:nav_start] + nav_section_new + content[nav_end + 6:]

        with open(html_file, 'w', encoding='utf-8') as f:
            f.write(content)

        return True

    return False

def main():
    if len(sys.argv) > 1:
        static_dir = sys.argv[1]
    else:
        static_dir = './static'

    if not os.path.exists(static_dir):
        print(f"❌ Directory not found: {static_dir}")
        sys.exit(1)

    # Find all HTML files
    html_files = list(Path(static_dir).rglob('*.html'))

    fixed_count = 0
    skipped_count = 0

    for html_file in html_files:
        try:
            if remove_magunkrol_from_menu(html_file):
                fixed_count += 1
                print(f"  ✓ Removed from: {html_file.relative_to(static_dir)}")
            else:
                skipped_count += 1
        except Exception as e:
            print(f"  ❌ Error processing {html_file}: {e}")

    print(f"\n  📊 Removed from {fixed_count} files, skipped {skipped_count} files")

if __name__ == '__main__':
    main()
