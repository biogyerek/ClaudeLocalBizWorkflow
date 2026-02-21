#!/usr/bin/env python3
"""
Add meta descriptions, Google Analytics and Google Tag Manager to all HTML files
"""
import os
import re
from pathlib import Path
from bs4 import BeautifulSoup

# Directory containing HTML files
HTML_DIR = Path("../melegburkolobudapest.hu")

# Google Analytics code
GA_CODE = '''<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9ZPNCZ43T6"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-9ZPNCZ43T6');
</script>
'''

# Google Tag Manager head code
GTM_HEAD = '''<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N89S7TWB');</script>
<!-- End Google Tag Manager -->
'''

# Google Tag Manager body code
GTM_BODY = '''<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N89S7TWB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
'''

def extract_text_for_description(soup):
    """Extract text from page to create a meta description"""
    # Try to find main content
    main_content = soup.find('main') or soup.find('article') or soup.find('body')

    if not main_content:
        return ""

    # Get first paragraph or heading
    first_p = main_content.find('p')
    if first_p:
        text = first_p.get_text(strip=True)
    else:
        # Try to get text from any element
        text = main_content.get_text(strip=True)

    # Clean up and truncate to ~160 characters
    text = ' '.join(text.split())  # Normalize whitespace
    if len(text) > 160:
        text = text[:157] + '...'

    return text

def process_html_file(filepath):
    """Process a single HTML file"""
    print(f"Processing: {filepath.name}")

    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    soup = BeautifulSoup(content, 'html.parser')
    head = soup.find('head')
    body = soup.find('body')

    if not head or not body:
        print(f"  ! Skipping - no head or body tag found")
        return False

    modified = False

    # 1. Add meta description if missing
    meta_desc = head.find('meta', attrs={'name': 'description'})
    if not meta_desc:
        # Generate description from page content
        description = extract_text_for_description(soup)

        if not description:
            # Use title as fallback
            title_tag = head.find('title')
            if title_tag:
                description = title_tag.get_text(strip=True)

        if description:
            # Find a good place to insert (after viewport or charset)
            viewport = head.find('meta', attrs={'name': 'viewport'})
            if viewport:
                new_meta = soup.new_tag('meta', attrs={'name': 'description', 'content': description})
                viewport.insert_after(new_meta)
                viewport.insert_after('\n\t')

                # Also add og:description
                og_title = head.find('meta', property='og:title')
                if og_title:
                    new_og_desc = soup.new_tag('meta', property='og:description', content=description)
                    og_title.insert_after(new_og_desc)
                    og_title.insert_after('\n\t')

                    # Add twitter:description
                    new_tw_desc = soup.new_tag('meta', attrs={'name': 'twitter:description', 'content': description})
                    new_og_desc.insert_after(new_tw_desc)
                    new_og_desc.insert_after('\n\t')

                print(f"  + Added meta description: {description[:50]}...")
                modified = True

    # 2. Add Google Analytics if missing
    if 'gtag.js' not in content:
        # Find the last meta tag or title
        last_meta = head.find_all(['meta', 'link'])[-1] if head.find_all(['meta', 'link']) else head.find('title')

        if last_meta:
            # Insert GA code
            ga_soup = BeautifulSoup(GA_CODE, 'html.parser')
            for element in ga_soup.contents:
                if element.name:
                    last_meta.insert_after(element)
                    last_meta.insert_after('\n\t')

            print(f"  + Added Google Analytics")
            modified = True

    # 3. Add Google Tag Manager to head if missing
    if 'GTM-N89S7TWB' not in content or 'Google Tag Manager' not in content:
        # Insert at the beginning of head
        gtm_soup = BeautifulSoup(GTM_HEAD, 'html.parser')
        first_element = head.find()

        if first_element:
            for element in reversed(list(gtm_soup.contents)):
                if element.name or (isinstance(element, str) and element.strip()):
                    first_element.insert_before(element)
                    first_element.insert_before('\n\t')

            print(f"  + Added Google Tag Manager (head)")
            modified = True

    # 4. Add Google Tag Manager noscript to body if missing
    if 'GTM-N89S7TWB' not in str(body) or 'noscript' not in str(body):
        # Insert right after body tag
        gtm_body_soup = BeautifulSoup(GTM_BODY, 'html.parser')
        first_element = body.find()

        if first_element:
            for element in reversed(list(gtm_body_soup.contents)):
                if element.name or (isinstance(element, str) and element.strip()):
                    first_element.insert_before(element)
                    first_element.insert_before('\n\t')

            print(f"  + Added Google Tag Manager (body)")
            modified = True

    # Save the file if modified
    if modified:
        with open(filepath, 'w', encoding='utf-8') as f:
            # Fix HTML entities
            html_str = str(soup)
            # Preserve existing entities
            html_str = html_str.replace('&oacute;', '&oacute;')
            html_str = html_str.replace('&aacute;', '&aacute;')
            html_str = html_str.replace('&eacute;', '&eacute;')
            html_str = html_str.replace('&uacute;', '&uacute;')
            html_str = html_str.replace('&iacute;', '&iacute;')
            html_str = html_str.replace('&ouml;', '&ouml;')
            html_str = html_str.replace('&uuml;', '&uuml;')
            html_str = html_str.replace('&odot;', '&odot;')
            f.write(html_str)

        return True

    return False

def main():
    """Process all HTML files"""
    html_files = list(HTML_DIR.glob("*.html"))

    print(f"Found {len(html_files)} HTML files\n")

    processed = 0
    for filepath in sorted(html_files):
        if process_html_file(filepath):
            processed += 1

    print(f"\nDone! Modified {processed} files out of {len(html_files)}")

if __name__ == "__main__":
    main()
