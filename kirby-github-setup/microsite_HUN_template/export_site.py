#!/usr/bin/env python3
import os
import re
import requests
from urllib.parse import urljoin, urlparse
from pathlib import Path
from bs4 import BeautifulSoup

BASE_URL = 'https://mykirtemplate.top'
OUTPUT_DIR = 'site_export'
visited_urls = set()
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
        os.makedirs(os.path.dirname(local_path), exist_ok=True)

        # Save file
        with open(local_path, 'wb') as f:
            f.write(response.content)

        return response.content
    except Exception as e:
        print(f"Error downloading {url}: {e}")
        return None

def crawl_page(url, depth=0, max_depth=3):
    """Crawl a page and download all resources"""
    if depth > max_depth:
        return

    if url in visited_urls:
        return

    visited_urls.add(url)

    # Get local path
    local_path = os.path.join(OUTPUT_DIR, get_local_path(url))

    # Download page
    content = download_file(url, local_path)
    if not content:
        return

    # Parse HTML
    try:
        soup = BeautifulSoup(content, 'html.parser')

        # Find all links
        for tag in soup.find_all(['a', 'link', 'script', 'img', 'source']):
            attr = None
            if tag.name == 'a' or tag.name == 'link':
                attr = 'href'
            elif tag.name in ['script', 'img', 'source']:
                attr = 'src'

            if attr and tag.get(attr):
                resource_url = urljoin(url, tag[attr])

                # Only download from same domain
                if urlparse(resource_url).netloc == urlparse(BASE_URL).netloc:
                    resource_path = os.path.join(OUTPUT_DIR, get_local_path(resource_url))

                    # Download resource if not already downloaded
                    if resource_url not in downloaded_files:
                        downloaded_files.add(resource_url)

                        # If it's a page link, crawl it
                        if tag.name == 'a' and not any(resource_url.endswith(ext) for ext in ['.css', '.js', '.jpg', '.png', '.gif', '.pdf', '.svg', '.webp']):
                            crawl_page(resource_url, depth + 1, max_depth)
                        else:
                            download_file(resource_url, resource_path)
    except Exception as e:
        print(f"Error parsing {url}: {e}")

if __name__ == '__main__':
    print(f"Starting site export from {BASE_URL}")
    print(f"Output directory: {OUTPUT_DIR}")

    # Create output directory
    os.makedirs(OUTPUT_DIR, exist_ok=True)

    # Start crawling from homepage
    crawl_page(BASE_URL)

    print(f"\nExport complete!")
    print(f"Total pages visited: {len(visited_urls)}")
    print(f"Total files downloaded: {len(downloaded_files)}")
