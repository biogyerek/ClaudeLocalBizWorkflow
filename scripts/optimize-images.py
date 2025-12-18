#!/usr/bin/env python3
"""
Image Optimization Script for Claude Local Business Workflow

This script automatically:
- Resizes images to multiple sizes for srcset
- Converts images to WebP format
- Creates optimized JPG fallbacks
- Maintains proper aspect ratios

Usage:
    python scripts/optimize-images.py

Requirements:
    pip install Pillow

Input:  assets/images/originals/
Output: assets/images/ (optimized versions)
"""

import os
import sys
from pathlib import Path

try:
    from PIL import Image
except ImportError:
    print("Error: Pillow is required. Install with: pip install Pillow")
    sys.exit(1)


# Configuration
CONFIG = {
    'input_dir': 'assets/images/originals',
    'output_dir': 'assets/images',

    # Image type configurations
    'types': {
        'hero': {
            'sizes': [400, 800, 1200, 1600, 2000],
            'aspect_ratio': (7, 3),  # 1400x600 approx
            'quality_webp': 80,
            'quality_jpg': 85,
        },
        'services': {
            'sizes': [300, 400, 600, 800],
            'aspect_ratio': (3, 2),  # 600x400
            'quality_webp': 80,
            'quality_jpg': 85,
        },
        'team': {
            'sizes': [150, 200, 300, 400],
            'aspect_ratio': (1, 1),  # Square
            'quality_webp': 85,
            'quality_jpg': 90,
        },
        'logo': {
            'sizes': [100, 150, 200, 300],
            'aspect_ratio': None,  # Keep original
            'quality_webp': 90,
            'quality_jpg': 95,
        },
        'og': {
            'sizes': [1200],  # Fixed for social media
            'aspect_ratio': (1200, 630),
            'quality_webp': 85,
            'quality_jpg': 90,
        },
    }
}


def get_image_type(filename):
    """Determine image type from filename or path."""
    name = filename.lower()
    if 'hero' in name:
        return 'hero'
    elif 'team' in name or 'person' in name:
        return 'team'
    elif 'logo' in name:
        return 'logo'
    elif 'og-' in name or 'og_' in name:
        return 'og'
    else:
        return 'services'  # Default


def crop_to_aspect(img, aspect_ratio):
    """Crop image to target aspect ratio from center."""
    if aspect_ratio is None:
        return img

    target_w, target_h = aspect_ratio
    target_ratio = target_w / target_h

    orig_w, orig_h = img.size
    orig_ratio = orig_w / orig_h

    if orig_ratio > target_ratio:
        # Image is wider, crop sides
        new_w = int(orig_h * target_ratio)
        left = (orig_w - new_w) // 2
        img = img.crop((left, 0, left + new_w, orig_h))
    elif orig_ratio < target_ratio:
        # Image is taller, crop top/bottom
        new_h = int(orig_w / target_ratio)
        top = (orig_h - new_h) // 2
        img = img.crop((0, top, orig_w, top + new_h))

    return img


def resize_image(img, width):
    """Resize image to width while maintaining aspect ratio."""
    orig_w, orig_h = img.size
    ratio = width / orig_w
    new_h = int(orig_h * ratio)
    return img.resize((width, new_h), Image.Resampling.LANCZOS)


def optimize_image(input_path, output_dir, img_type_config):
    """Process a single image into multiple optimized versions."""
    filename = os.path.basename(input_path)
    name, ext = os.path.splitext(filename)

    print(f"  Processing: {filename}")

    # Open image
    try:
        img = Image.open(input_path)
    except Exception as e:
        print(f"    Error opening image: {e}")
        return []

    # Convert to RGB if necessary (for WebP/JPG)
    if img.mode in ('RGBA', 'P'):
        # Keep alpha for PNG, convert for others
        if img.mode == 'P':
            img = img.convert('RGBA')
        background = Image.new('RGB', img.size, (255, 255, 255))
        if img.mode == 'RGBA':
            background.paste(img, mask=img.split()[3])
            img = background
    elif img.mode != 'RGB':
        img = img.convert('RGB')

    # Crop to aspect ratio
    img = crop_to_aspect(img, img_type_config['aspect_ratio'])

    generated_files = []

    for width in img_type_config['sizes']:
        # Skip if original is smaller
        if width > img.size[0]:
            continue

        resized = resize_image(img, width)

        # Save WebP
        webp_filename = f"{name}-{width}w.webp"
        webp_path = os.path.join(output_dir, webp_filename)
        resized.save(webp_path, 'WEBP', quality=img_type_config['quality_webp'])
        generated_files.append(webp_path)

        # Save JPG fallback
        jpg_filename = f"{name}-{width}w.jpg"
        jpg_path = os.path.join(output_dir, jpg_filename)
        resized.save(jpg_path, 'JPEG', quality=img_type_config['quality_jpg'], optimize=True)
        generated_files.append(jpg_path)

    # Also save default size (largest) without suffix for backwards compatibility
    if img_type_config['sizes']:
        default_size = max(s for s in img_type_config['sizes'] if s <= img.size[0])
        default_resized = resize_image(img, default_size)

        # Default WebP
        webp_default = os.path.join(output_dir, f"{name}.webp")
        default_resized.save(webp_default, 'WEBP', quality=img_type_config['quality_webp'])
        generated_files.append(webp_default)

        # Default JPG
        jpg_default = os.path.join(output_dir, f"{name}.jpg")
        default_resized.save(jpg_default, 'JPEG', quality=img_type_config['quality_jpg'], optimize=True)
        generated_files.append(jpg_default)

    return generated_files


def process_directory(input_dir, output_base_dir):
    """Process all images in directory."""
    if not os.path.exists(input_dir):
        print(f"Creating input directory: {input_dir}")
        os.makedirs(input_dir)
        print(f"\nPlease add your original images to: {input_dir}")
        print("Then run this script again.")
        return

    # Find all images
    image_extensions = {'.jpg', '.jpeg', '.png', '.webp', '.gif'}
    images = []

    for root, dirs, files in os.walk(input_dir):
        for file in files:
            if os.path.splitext(file)[1].lower() in image_extensions:
                images.append(os.path.join(root, file))

    if not images:
        print(f"No images found in {input_dir}")
        print("\nSupported formats: JPG, PNG, WebP, GIF")
        return

    print(f"Found {len(images)} images to process\n")

    total_generated = []

    for input_path in images:
        # Determine output subdirectory based on input structure
        rel_path = os.path.relpath(os.path.dirname(input_path), input_dir)
        if rel_path == '.':
            output_dir = output_base_dir
        else:
            output_dir = os.path.join(output_base_dir, rel_path)

        # Create output directory
        os.makedirs(output_dir, exist_ok=True)

        # Determine image type and get config
        img_type = get_image_type(input_path)
        img_config = CONFIG['types'].get(img_type, CONFIG['types']['services'])

        print(f"[{img_type.upper()}]")
        generated = optimize_image(input_path, output_dir, img_config)
        total_generated.extend(generated)

    print(f"\n{'='*50}")
    print(f"Optimization complete!")
    print(f"Generated {len(total_generated)} optimized images")

    # Calculate size savings
    if total_generated:
        total_size = sum(os.path.getsize(f) for f in total_generated)
        print(f"Total output size: {total_size / 1024 / 1024:.2f} MB")


def main():
    """Main entry point."""
    print("="*50)
    print("Image Optimization Script")
    print("="*50 + "\n")

    # Get script directory and project root
    script_dir = os.path.dirname(os.path.abspath(__file__))
    project_root = os.path.dirname(script_dir)

    input_dir = os.path.join(project_root, CONFIG['input_dir'])
    output_dir = os.path.join(project_root, CONFIG['output_dir'])

    print(f"Input:  {input_dir}")
    print(f"Output: {output_dir}\n")

    process_directory(input_dir, output_dir)

    print("\nNext steps:")
    print("1. Check the generated images in assets/images/")
    print("2. The templates will automatically use the optimized versions")
    print("3. Run the build process to generate the final site")


if __name__ == '__main__':
    main()
