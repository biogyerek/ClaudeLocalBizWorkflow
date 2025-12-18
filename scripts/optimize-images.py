#!/usr/bin/env python3
"""
Image Optimization Script for Claude Local Business Workflow

This script automatically:
- Resizes images to multiple sizes for srcset
- Converts images to WebP format
- Creates optimized JPG/PNG fallbacks
- Generates favicons from logo
- Maintains proper aspect ratios

Usage:
    # Batch process all images in originals/
    python scripts/optimize-images.py

    # Optimize single image
    python scripts/optimize-images.py path/to/image.jpg

    # Optimize single image with specific type
    python scripts/optimize-images.py path/to/image.jpg --type services

Requirements:
    pip install Pillow

Input:  assets/images/originals/
Output: assets/images/ (optimized versions)
"""

import os
import sys
import argparse
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
            'format': 'both',  # webp + jpg
        },
        'services': {
            'sizes': [300, 400, 600, 800],
            'aspect_ratio': (3, 2),  # 600x400
            'quality_webp': 80,
            'quality_jpg': 85,
            'format': 'both',
        },
        'team': {
            'sizes': [150, 200, 300, 400],
            'aspect_ratio': (1, 1),  # Square
            'quality_webp': 85,
            'quality_jpg': 90,
            'format': 'both',
        },
        'logo': {
            'sizes': [100, 150, 200, 300],
            'aspect_ratio': None,  # Keep original
            'quality_webp': 90,
            'quality_png': 95,
            'format': 'webp+png',  # Keep PNG for transparency
        },
        'favicon': {
            'sizes': [16, 32, 48, 64, 128, 180, 192, 512],
            'aspect_ratio': (1, 1),  # Square
            'quality_png': 100,
            'format': 'png',  # PNG only for favicons
            'special_outputs': {
                16: 'favicon-16x16.png',
                32: 'favicon-32x32.png',
                180: 'apple-touch-icon.png',
                192: 'android-chrome-192x192.png',
                512: 'android-chrome-512x512.png',
            }
        },
        'og': {
            'sizes': [1200],  # Fixed for social media
            'aspect_ratio': (1200, 630),
            'quality_webp': 85,
            'quality_jpg': 90,
            'format': 'jpg',  # JPG only for OG (wider support)
        },
    }
}


def get_image_type(filename):
    """Determine image type from filename or path."""
    name = filename.lower()
    path_lower = str(filename).lower()

    if 'favicon' in name or 'icon' in name:
        return 'favicon'
    elif 'hero' in name:
        return 'hero'
    elif 'team' in path_lower or 'person' in name:
        return 'team'
    elif 'logo' in name:
        return 'logo'
    elif 'og-' in name or 'og_' in name or 'og.' in name:
        return 'og'
    elif 'services' in path_lower or 'szolg' in path_lower:
        return 'services'
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


def resize_image(img, width, height=None):
    """Resize image to width (and optional height) while maintaining aspect ratio."""
    if height:
        return img.resize((width, height), Image.Resampling.LANCZOS)

    orig_w, orig_h = img.size
    ratio = width / orig_w
    new_h = int(orig_h * ratio)
    return img.resize((width, new_h), Image.Resampling.LANCZOS)


def convert_to_rgb(img):
    """Convert image to RGB, handling transparency."""
    if img.mode == 'RGBA':
        background = Image.new('RGB', img.size, (255, 255, 255))
        background.paste(img, mask=img.split()[3])
        return background
    elif img.mode == 'P':
        img = img.convert('RGBA')
        background = Image.new('RGB', img.size, (255, 255, 255))
        if 'A' in img.getbands():
            background.paste(img, mask=img.split()[3])
        else:
            background.paste(img)
        return background
    elif img.mode != 'RGB':
        return img.convert('RGB')
    return img


def optimize_image(input_path, output_dir, img_type_config, img_type='services'):
    """Process a single image into multiple optimized versions."""
    filename = os.path.basename(input_path)
    name, ext = os.path.splitext(filename)

    print(f"  Processing: {filename}")

    # Open image
    try:
        img = Image.open(input_path)
        original_mode = img.mode
    except Exception as e:
        print(f"    Error opening image: {e}")
        return []

    # Crop to aspect ratio (before any conversion)
    img = crop_to_aspect(img, img_type_config.get('aspect_ratio'))

    generated_files = []
    output_format = img_type_config.get('format', 'both')
    special_outputs = img_type_config.get('special_outputs', {})

    for width in img_type_config['sizes']:
        # Skip if original is smaller
        if width > img.size[0]:
            continue

        # For square favicon sizes, use same width and height
        if img_type == 'favicon':
            resized = resize_image(img, width, width)
        else:
            resized = resize_image(img, width)

        # Check for special output filename (favicons)
        if width in special_outputs:
            special_name = special_outputs[width]
            special_path = os.path.join(output_dir, special_name)

            # Favicons need RGBA for transparency support
            if resized.mode != 'RGBA' and original_mode == 'RGBA':
                resized = img.resize((width, width), Image.Resampling.LANCZOS)

            resized.save(special_path, 'PNG', optimize=True)
            generated_files.append(special_path)
            print(f"    → {special_name}")
            continue

        # Save in requested formats
        if output_format in ('both', 'webp', 'webp+png'):
            # Convert to RGB for WebP
            rgb_img = convert_to_rgb(resized) if resized.mode != 'RGB' else resized
            webp_filename = f"{name}-{width}w.webp"
            webp_path = os.path.join(output_dir, webp_filename)
            rgb_img.save(webp_path, 'WEBP', quality=img_type_config.get('quality_webp', 85))
            generated_files.append(webp_path)

        if output_format in ('both', 'jpg'):
            rgb_img = convert_to_rgb(resized) if resized.mode != 'RGB' else resized
            jpg_filename = f"{name}-{width}w.jpg"
            jpg_path = os.path.join(output_dir, jpg_filename)
            rgb_img.save(jpg_path, 'JPEG', quality=img_type_config.get('quality_jpg', 85), optimize=True)
            generated_files.append(jpg_path)

        if output_format in ('png', 'webp+png'):
            png_filename = f"{name}-{width}w.png"
            png_path = os.path.join(output_dir, png_filename)
            resized.save(png_path, 'PNG', optimize=True)
            generated_files.append(png_path)

    # Also save default size (largest) without suffix for backwards compatibility
    valid_sizes = [s for s in img_type_config['sizes'] if s <= img.size[0]]
    if valid_sizes:
        default_size = max(valid_sizes)

        if img_type == 'favicon':
            default_resized = resize_image(img, default_size, default_size)
        else:
            default_resized = resize_image(img, default_size)

        if output_format in ('both', 'webp', 'webp+png'):
            rgb_img = convert_to_rgb(default_resized) if default_resized.mode != 'RGB' else default_resized
            webp_default = os.path.join(output_dir, f"{name}.webp")
            rgb_img.save(webp_default, 'WEBP', quality=img_type_config.get('quality_webp', 85))
            generated_files.append(webp_default)

        if output_format in ('both', 'jpg'):
            rgb_img = convert_to_rgb(default_resized) if default_resized.mode != 'RGB' else default_resized
            jpg_default = os.path.join(output_dir, f"{name}.jpg")
            rgb_img.save(jpg_default, 'JPEG', quality=img_type_config.get('quality_jpg', 85), optimize=True)
            generated_files.append(jpg_default)

        if output_format in ('png', 'webp+png'):
            png_default = os.path.join(output_dir, f"{name}.png")
            default_resized.save(png_default, 'PNG', optimize=True)
            generated_files.append(png_default)

    return generated_files


def generate_favicon_from_logo(logo_path, output_dir):
    """Generate all favicon sizes from a logo image."""
    print("\n[FAVICON] Generating favicons from logo...")

    favicon_config = CONFIG['types']['favicon']
    return optimize_image(logo_path, output_dir, favicon_config, 'favicon')


def optimize_single_image(image_path, output_dir=None, image_type=None):
    """
    Optimize a single image file.

    Args:
        image_path: Path to the image file
        output_dir: Output directory (default: same as input or assets/images/)
        image_type: Force specific type (hero, services, team, logo, favicon, og)

    Returns:
        List of generated file paths
    """
    if not os.path.exists(image_path):
        print(f"Error: Image not found: {image_path}")
        return []

    # Determine output directory
    if output_dir is None:
        # Use assets/images/ + relative subdirectory
        if 'originals' in image_path:
            rel_path = os.path.relpath(os.path.dirname(image_path),
                                        image_path.split('originals')[0] + 'originals')
            base_output = image_path.split('originals')[0]
            output_dir = os.path.join(base_output, rel_path) if rel_path != '.' else base_output
        else:
            output_dir = os.path.dirname(image_path)

    os.makedirs(output_dir, exist_ok=True)

    # Determine image type
    if image_type is None:
        image_type = get_image_type(image_path)

    img_config = CONFIG['types'].get(image_type, CONFIG['types']['services'])

    print(f"[{image_type.upper()}]")
    generated = optimize_image(image_path, output_dir, img_config, image_type)

    # If it's a logo, also generate favicons
    if image_type == 'logo':
        favicon_files = generate_favicon_from_logo(image_path, output_dir)
        generated.extend(favicon_files)

    return generated


def process_directory(input_dir, output_base_dir):
    """Process all images in directory."""
    if not os.path.exists(input_dir):
        print(f"Creating input directory: {input_dir}")
        os.makedirs(input_dir, exist_ok=True)
        print(f"\nPlease add your original images to: {input_dir}")
        print("Then run this script again.")
        return []

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
        return []

    print(f"Found {len(images)} images to process\n")

    total_generated = []
    logo_path = None

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
        generated = optimize_image(input_path, output_dir, img_config, img_type)
        total_generated.extend(generated)

        # Remember logo path for favicon generation
        if img_type == 'logo':
            logo_path = input_path

    # Generate favicons from logo if found
    if logo_path:
        favicon_files = generate_favicon_from_logo(logo_path, output_base_dir)
        total_generated.extend(favicon_files)

    return total_generated


def main():
    """Main entry point."""
    parser = argparse.ArgumentParser(
        description='Optimize images for web with WebP, srcset, and favicon support'
    )
    parser.add_argument('image', nargs='?', help='Single image to optimize (optional)')
    parser.add_argument('--type', '-t', choices=['hero', 'services', 'team', 'logo', 'favicon', 'og'],
                        help='Force specific image type')
    parser.add_argument('--output', '-o', help='Output directory')

    args = parser.parse_args()

    print("=" * 50)
    print("Image Optimization Script")
    print("=" * 50 + "\n")

    # Get script directory and project root
    script_dir = os.path.dirname(os.path.abspath(__file__))
    project_root = os.path.dirname(script_dir)

    if args.image:
        # Single image mode
        image_path = args.image if os.path.isabs(args.image) else os.path.join(os.getcwd(), args.image)
        output_dir = args.output

        if output_dir and not os.path.isabs(output_dir):
            output_dir = os.path.join(os.getcwd(), output_dir)

        generated = optimize_single_image(image_path, output_dir, args.type)

        print(f"\n{'=' * 50}")
        print(f"Generated {len(generated)} optimized images")
    else:
        # Batch mode
        input_dir = os.path.join(project_root, CONFIG['input_dir'])
        output_dir = os.path.join(project_root, CONFIG['output_dir'])

        print(f"Input:  {input_dir}")
        print(f"Output: {output_dir}\n")

        generated = process_directory(input_dir, output_dir)

        print(f"\n{'=' * 50}")
        print(f"Optimization complete!")
        print(f"Generated {len(generated)} optimized images")

        # Calculate size savings
        if generated:
            total_size = sum(os.path.getsize(f) for f in generated if os.path.exists(f))
            print(f"Total output size: {total_size / 1024 / 1024:.2f} MB")

    print("\nNext steps:")
    print("1. Check the generated images in assets/images/")
    print("2. The templates will automatically use the optimized versions")


if __name__ == '__main__':
    main()


# ============================================
# Module API for build process integration
# ============================================

def optimize_for_build(project_root, verbose=True):
    """
    Called during build process to optimize all images.

    Args:
        project_root: Root directory of the project
        verbose: Print progress messages

    Returns:
        List of generated file paths
    """
    input_dir = os.path.join(project_root, CONFIG['input_dir'])
    output_dir = os.path.join(project_root, CONFIG['output_dir'])

    if verbose:
        print("\n=== Image Optimization ===")
        print(f"Input:  {input_dir}")
        print(f"Output: {output_dir}\n")

    generated = process_directory(input_dir, output_dir)

    if verbose:
        print(f"\nOptimized {len(generated)} images")

    return generated


def optimize_service_image(image_path, service_slug, output_dir):
    """
    Optimize a single service image with the correct slug name.

    Args:
        image_path: Path to source image
        service_slug: Service slug for output filename
        output_dir: Output directory (typically assets/images/services/)

    Returns:
        List of generated file paths
    """
    if not os.path.exists(image_path):
        print(f"Warning: Image not found: {image_path}")
        return []

    os.makedirs(output_dir, exist_ok=True)

    # Open and process image
    try:
        img = Image.open(image_path)
    except Exception as e:
        print(f"Error opening image: {e}")
        return []

    config = CONFIG['types']['services']
    img = crop_to_aspect(img, config['aspect_ratio'])

    generated = []

    for width in config['sizes']:
        if width > img.size[0]:
            continue

        resized = resize_image(img, width)
        rgb_img = convert_to_rgb(resized)

        # WebP
        webp_path = os.path.join(output_dir, f"{service_slug}-{width}w.webp")
        rgb_img.save(webp_path, 'WEBP', quality=config['quality_webp'])
        generated.append(webp_path)

        # JPG
        jpg_path = os.path.join(output_dir, f"{service_slug}-{width}w.jpg")
        rgb_img.save(jpg_path, 'JPEG', quality=config['quality_jpg'], optimize=True)
        generated.append(jpg_path)

    # Default size
    valid_sizes = [s for s in config['sizes'] if s <= img.size[0]]
    if valid_sizes:
        default_resized = resize_image(img, max(valid_sizes))
        rgb_img = convert_to_rgb(default_resized)

        webp_path = os.path.join(output_dir, f"{service_slug}.webp")
        rgb_img.save(webp_path, 'WEBP', quality=config['quality_webp'])
        generated.append(webp_path)

        jpg_path = os.path.join(output_dir, f"{service_slug}.jpg")
        rgb_img.save(jpg_path, 'JPEG', quality=config['quality_jpg'], optimize=True)
        generated.append(jpg_path)

    print(f"  Optimized: {service_slug} ({len(generated)} files)")
    return generated
