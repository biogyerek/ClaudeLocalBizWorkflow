#!/usr/bin/env python3
"""
Add Google Tag Manager (GTM) to all HTML files
"""

import os
import sys
from pathlib import Path

def add_google_tag_manager(html_file, gtm_id="GTM-WC7Q2XGZ"):
    """Add Google Tag Manager code to HTML file"""

    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Check if GTM is already present
    if 'googletagmanager.com/gtm.js' in content or gtm_id in content:
        return False

    # Google Tag Manager head code
    gtm_head = f'''<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){{w[l]=w[l]||[];w[l].push({{'gtm.start':
new Date().getTime(),event:'gtm.js'}});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
}})(window,document,'script','dataLayer','{gtm_id}');</script>
<!-- End Google Tag Manager -->
'''

    # Google Tag Manager noscript code (goes right after <body>)
    gtm_body = f'''<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={gtm_id}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
'''

    modified = False

    # Insert head code before </head>
    if '</head>' in content:
        content = content.replace('</head>', f'{gtm_head}</head>')
        modified = True

    # Insert noscript code right after <body>
    if '<body>' in content:
        content = content.replace('<body>', f'<body>\n{gtm_body}')
        modified = True

    if modified:
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
            if add_google_tag_manager(html_file):
                added_count += 1
                print(f"  ✓ Added GTM to: {html_file.relative_to(static_dir)}")
            else:
                skipped_count += 1
                # print(f"  ⊘ Skipped: {html_file.relative_to(static_dir)}")
        except Exception as e:
            print(f"  ❌ Error processing {html_file}: {e}")

    print()
    print(f"📊 Summary:")
    print(f"  ✓ Google Tag Manager added to {added_count} files")
    print(f"  ⊘ Skipped {skipped_count} files (already have GTM)")

if __name__ == '__main__':
    main()
