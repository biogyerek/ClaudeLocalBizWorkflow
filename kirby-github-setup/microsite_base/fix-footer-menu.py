#!/usr/bin/env python3
"""
Fix Footer Menu Script
Adds footer menu to HTML files that are missing it
"""

import os
import sys
import re
from pathlib import Path

def fix_footer_menu(html_file, domain="asztalosmesterbudapest.hu"):
    """Add footer menu sections to HTML file if missing"""

    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Check if already has both footer menus
    has_gyors = 'Gyors linkek' in content
    has_ceg = 'Cég</h3>' in content

    if has_gyors and has_ceg:
        return False

    # Footer menus to insert
    gyors_linkek_menu = '''
      <h3>Gyors linkek</h3>

      <ul class="footer-menu">
                <li class="footer-menu-item"><a href="magunkrol.html">Magunkr&oacute;l</a></li>
                <li class="footer-menu-item"><a href="adatvedelmi-nyilatkozat.html">Adatv&eacute;delmi Nyilatkozat</a></li>
                <li class="footer-menu-item"><a href="faq.html">FAQ</a></li>
              </ul>

      '''

    ceg_menu = '''
      <h3>Cég</h3>

      <ul class="footer-menu">
                <li class="footer-menu-item"><a href="beepitett-szekreny-keszites.html">Be&eacute;p&iacute;tett Szekr&eacute;ny K&eacute;sz&iacute;t&eacute;s</a></li>
                <li class="footer-menu-item"><a href="butor-javitas.html">B&uacute;tor Jav&iacute;t&aacute;s</a></li>
                <li class="footer-menu-item"><a href="egyedi-butor-keszites.html">Egyedi B&uacute;tor K&eacute;sz&iacute;t&eacute;s</a></li>
                <li class="footer-menu-item"><a href="egyedi-konyhabutor-keszites.html">Egyedi Konyhab&uacute;tor K&eacute;sz&iacute;t&eacute;s</a></li>
              </ul>

      '''

    # Find the footer section first
    footer_start = content.find('<div class="footer">')
    footer_end = content.find('</div>\n\n  <div class="copyright">', footer_start)

    if footer_start == -1 or footer_end == -1:
        return False

    footer_section = content[footer_start:footer_end]

    # Find empty divs in footer (with flexible whitespace between tags)
    # Pattern: <div >\n\n      \n\n    </div>
    pattern = r'<div\s*>[\s\n]*</div>'

    # Count empty divs and replace them
    modified = False

    # First empty div -> Gyors linkek (if not exists)
    if not has_gyors:
        replacement = f'''<div >
      <h3>Gyors linkek</h3>

      <ul class="footer-menu">
                <li class="footer-menu-item"><a href="magunkrol.html">Magunkr&oacute;l</a></li>
                <li class="footer-menu-item"><a href="adatvedelmi-nyilatkozat.html">Adatv&eacute;delmi Nyilatkozat</a></li>
                <li class="footer-menu-item"><a href="faq.html">FAQ</a></li>
              </ul>


    </div>'''
        footer_section = re.sub(pattern, replacement, footer_section, count=1)
        modified = True

    # Second empty div -> Cég (if not exists)
    if not has_ceg:
        replacement = f'''<div >
      <h3>Cég</h3>

      <ul class="footer-menu">
                <li class="footer-menu-item"><a href="beepitett-szekreny-keszites.html">Be&eacute;p&iacute;tett Szekr&eacute;ny K&eacute;sz&iacute;t&eacute;s</a></li>
                <li class="footer-menu-item"><a href="butor-javitas.html">B&uacute;tor Jav&iacute;t&aacute;s</a></li>
                <li class="footer-menu-item"><a href="egyedi-butor-keszites.html">Egyedi B&uacute;tor K&eacute;sz&iacute;t&eacute;s</a></li>
                <li class="footer-menu-item"><a href="egyedi-konyhabutor-keszites.html">Egyedi Konyhab&uacute;tor K&eacute;sz&iacute;t&eacute;s</a></li>
              </ul>


    </div>'''
        footer_section = re.sub(pattern, replacement, footer_section, count=1)
        modified = True

    if modified:
        # Replace the footer section in the original content
        content = content[:footer_start] + footer_section + content[footer_end:]

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
        # Skip index.html as it already has the menu
        if html_file.name == 'index.html' and html_file.parent == Path(static_dir):
            continue

        try:
            if fix_footer_menu(html_file):
                fixed_count += 1
                print(f"  ✓ Fixed: {html_file.relative_to(static_dir)}")
            else:
                skipped_count += 1
        except Exception as e:
            print(f"  ❌ Error fixing {html_file}: {e}")

    print(f"\n  📊 Fixed {fixed_count} files, skipped {skipped_count} files")

if __name__ == '__main__':
    main()
