#!/usr/bin/env python3
"""
Fix service page CTA buttons to be click-to-call
"""

import os
import sys
import re
from pathlib import Path

def fix_service_cta(html_file, phone="+36703546606"):
    """Change service page leader CTA to click-to-call"""

    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Find the leader section
    leader_start = content.find('<section class="leader">')
    leader_end = content.find('</section>', leader_start)

    if leader_start == -1 or leader_end == -1:
        return False

    leader_section = content[leader_start:leader_end]

    # Pattern to find the CTA button in leader
    # Looking for: <a class="btn btn--offer" href="...">...</a>
    pattern = r'<a class="btn btn--offer" href="[^"]*">([^<]*)</a>'

    match = re.search(pattern, leader_section)

    if match:
        # Replace with click-to-call link
        replacement = f'<a class="btn btn--offer call-cta" href="tel:{phone}">Hívjon most: {phone}</a>'
        leader_section_new = re.sub(pattern, replacement, leader_section)

        # Replace leader section in original content
        content = content[:leader_start] + leader_section_new + content[leader_end:]

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

    # Service page files
    service_files = [
        'beepitett-szekreny-keszites.html',
        'butor-javitas.html',
        'egyedi-butor-keszites.html',
        'egyedi-konyhabutor-keszites.html'
    ]

    fixed_count = 0

    for service_file in service_files:
        html_path = Path(static_dir) / service_file

        if not html_path.exists():
            print(f"  ⚠️  Not found: {service_file}")
            continue

        try:
            if fix_service_cta(html_path):
                fixed_count += 1
                print(f"  ✓ Fixed CTA: {service_file}")
            else:
                print(f"  ⚠️  No CTA found: {service_file}")
        except Exception as e:
            print(f"  ❌ Error processing {service_file}: {e}")

    print(f"\n  📊 Fixed {fixed_count} service pages")

if __name__ == '__main__':
    main()
