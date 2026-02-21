#!/usr/bin/env python3
import os
import requests
from urllib.parse import urljoin, urlparse
from pathlib import Path
from bs4 import BeautifulSoup
import xml.etree.ElementTree as ET

BASE_URL = 'https://mykirtemplate.top'
OUTPUT_DIR = 'site_export_full'
downloaded_files = set()

def get_local_path(url):
    """Convert URL to local file path"""
    parsed = urlparse(url)
    path = parsed.path

    if path == '/' or path == '':
        return 'index.html'

    # Remove leading slash
    path = path.lstrip('/')

    # If it's a directory (ends with /), add index.html
    if path.endswith('/'):
        path += 'index.html'
    # If no extension, add .html
    elif '.' not in os.path.basename(path):
        path += '.html'

    return path

def download_file(url, local_path):
    """Download a file from URL to local path"""
    try:
        print(f"Downloading: {url}")
        response = requests.get(url, timeout=30)
        response.raise_for_status()

        # Create directory if needed
        os.makedirs(os.path.dirname(local_path) if os.path.dirname(local_path) else '.', exist_ok=True)

        # Save file
        with open(local_path, 'wb') as f:
            f.write(response.content)

        return response.content
    except Exception as e:
        print(f"Error downloading {url}: {e}")
        return None

def download_page_resources(url, content):
    """Download all resources referenced in a page"""
    try:
        soup = BeautifulSoup(content, 'html.parser')

        # Find all resource links
        for tag in soup.find_all(['link', 'script', 'img', 'source']):
            attr = None
            if tag.name == 'link':
                attr = 'href'
            elif tag.name in ['script', 'img', 'source']:
                attr = 'src'

            if attr and tag.get(attr):
                resource_url = urljoin(url, tag[attr])

                # Only download from same domain
                if urlparse(resource_url).netloc == urlparse(BASE_URL).netloc:
                    if resource_url not in downloaded_files:
                        downloaded_files.add(resource_url)
                        resource_path = os.path.join(OUTPUT_DIR, get_local_path(resource_url))
                        download_file(resource_url, resource_path)
    except Exception as e:
        print(f"Error parsing resources from {url}: {e}")

def get_sitemap_urls():
    """Get all URLs from sitemap"""
    sitemap_url = f"{BASE_URL}/sitemap.xml"
    try:
        print(f"Downloading sitemap from {sitemap_url}")
        response = requests.get(sitemap_url, timeout=30)
        response.raise_for_status()

        root = ET.fromstring(response.content)

        # Handle XML namespaces
        namespace = {'ns': 'http://www.sitemaps.org/schemas/sitemap/0.9'}

        urls = []
        for url in root.findall('.//ns:loc', namespace):
            urls.append(url.text)

        # Also add the homepage if not in sitemap
        if BASE_URL not in urls:
            urls.insert(0, BASE_URL)

        return urls
    except Exception as e:
        print(f"Error getting sitemap: {e}")
        return [BASE_URL]

if __name__ == '__main__':
    print(f"Starting full site export from {BASE_URL}")
    print(f"Output directory: {OUTPUT_DIR}")

    # Create output directory
    os.makedirs(OUTPUT_DIR, exist_ok=True)

    # Get all URLs from sitemap
    urls = get_sitemap_urls()
    print(f"\nFound {len(urls)} URLs in sitemap")

    # Download each page
    for i, url in enumerate(urls, 1):
        print(f"\n[{i}/{len(urls)}] Processing: {url}")
        local_path = os.path.join(OUTPUT_DIR, get_local_path(url))

        # Download the page
        content = download_file(url, local_path)

        if content:
            # Download all resources from the page
            download_page_resources(url, content)

    print(f"\n{'='*60}")
    print(f"Export complete!")
    print(f"Total URLs from sitemap: {len(urls)}")
    print(f"Total files downloaded: {len(downloaded_files) + len(urls)}")
    print(f"{'='*60}")
