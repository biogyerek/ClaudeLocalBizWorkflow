#!/usr/bin/env python3
"""
Add Google Analytics (gtag.js) to all HTML files
"""

import os
import sys
from pathlib import Path

def add_google_analytics(html_file, ga_id="G-Q95C2CZXKV"):
    """Add Google Analytics tracking code to HTML file"""

    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Check if Google Analytics is already present
    if 'googletagmanager.com/gtag/js' in content or ga_id in content:
        return False

    # Google Analytics code
    ga_code = f'''<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={ga_id}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){{dataLayer.push(arguments);}}
  gtag('js', new Date());

  gtag('config', '{ga_id}');
</script>
'''

    # Find </head> tag and insert before it
    if '</head>' in content:
        content = content.replace('</head>', f'{ga_code}</head>')

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

    added_count = 0
    skipped_count = 0

    print(f"📊 Processing {len(html_files)} HTML files...")
    print()

    for html_file in html_files:
        try:
            if add_google_analytics(html_file):
                added_count += 1
                print(f"  ✓ Added GA to: {html_file.relative_to(static_dir)}")
            else:
                skipped_count += 1
                # print(f"  ⊘ Skipped: {html_file.relative_to(static_dir)}")
        except Exception as e:
            print(f"  ❌ Error processing {html_file}: {e}")

    print()
    print(f"📊 Summary:")
    print(f"  ✓ Google Analytics added to {added_count} files")
    print(f"  ⊘ Skipped {skipped_count} files (already have GA)")

if __name__ == '__main__':
    main()
