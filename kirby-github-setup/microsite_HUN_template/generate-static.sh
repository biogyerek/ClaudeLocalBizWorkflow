#!/bin/bash

# Static Site Generator using wget
# This script generates a static HTML site from a running Kirby instance

set -e  # Exit on error

# Load project configuration if exists
if [ -f "./project-config.sh" ]; then
    echo "📁 Loading project configuration..."
    source ./project-config.sh
fi

echo "🌐 Static Site Generator (wget method)"
echo "======================================"
echo ""

# Configuration (can be overridden by project-config.sh or command line)
SITE_URL="${SOURCE_URL:-${SITE_URL:-http://localhost:8000}}"
OUTPUT_DIR="${STATIC_DIR:-./static}"
TEMP_DIR="./temp-static-download"

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --url)
            SITE_URL="$2"
            shift 2
            ;;
        --output)
            OUTPUT_DIR="$2"
            shift 2
            ;;
        *)
            echo "Unknown option: $1"
            echo "Usage: $0 [--url URL] [--output DIR]"
            exit 1
            ;;
    esac
done

echo "📋 Configuration:"
echo "   Site URL: $SITE_URL"
echo "   Output directory: $OUTPUT_DIR"
echo ""

# Check if wget is installed
if ! command -v wget &> /dev/null; then
    echo "❌ Error: wget is not installed"
    echo "Install with: brew install wget"
    exit 1
fi

# Clean temp directory
echo "🧹 Cleaning temporary directory..."
rm -rf "$TEMP_DIR"
mkdir -p "$TEMP_DIR"

# Download site with wget
echo "📥 Downloading site from $SITE_URL..."
cd "$TEMP_DIR"

wget \
    --recursive \
    --level=inf \
    --no-clobber \
    --page-requisites \
    --html-extension \
    --convert-links \
    --restrict-file-names=windows \
    --domains $(echo "$SITE_URL" | sed 's|https\?://||' | cut -d/ -f1) \
    --no-parent \
    --reject "panel/*,api/*,*.php" \
    --no-check-certificate \
    "$SITE_URL" \
    2>&1 | grep -E "Downloaded:|FINISHED|Saving to" || true

cd ..

# Extract domain from URL
DOMAIN=$(echo "$SITE_URL" | sed 's|https\?://||' | cut -d/ -f1 | sed 's|:.*||')

# Check if download was successful
if [ ! -d "$TEMP_DIR/$DOMAIN" ]; then
    echo "❌ Error: Download failed or no files found in $TEMP_DIR/$DOMAIN"
    ls -la "$TEMP_DIR"
    exit 1
fi

# Preserve important files from existing output
echo "💾 Preserving important files..."
PRESERVE_FILES=(".git" ".gitignore" "CNAME" "README.md" ".nojekyll")
PRESERVE_DIR="./temp-preserve"
rm -rf "$PRESERVE_DIR"
mkdir -p "$PRESERVE_DIR"

for file in "${PRESERVE_FILES[@]}"; do
    if [ -e "$OUTPUT_DIR/$file" ]; then
        cp -r "$OUTPUT_DIR/$file" "$PRESERVE_DIR/"
        echo "   ✓ Preserved: $file"
    fi
done

# Remove old output
echo "🗑️  Removing old output directory..."
rm -rf "$OUTPUT_DIR"

# Move downloaded files to output
echo "📦 Moving files to output directory..."
mv "$TEMP_DIR/$DOMAIN" "$OUTPUT_DIR"

# Restore preserved files
echo "♻️  Restoring preserved files..."
for file in "${PRESERVE_FILES[@]}"; do
    if [ -e "$PRESERVE_DIR/$file" ]; then
        cp -r "$PRESERVE_DIR/$file" "$OUTPUT_DIR/"
        echo "   ✓ Restored: $file"
    fi
done

# Clean up
rm -rf "$TEMP_DIR" "$PRESERVE_DIR"

# Count generated files
HTML_COUNT=$(find "$OUTPUT_DIR" -name "*.html" | wc -l | tr -d ' ')
TOTAL_COUNT=$(find "$OUTPUT_DIR" -type f | wc -l | tr -d ' ')

echo ""
echo "✅ Static site generated successfully!"
echo "📁 $HTML_COUNT HTML files generated"
echo "📊 $TOTAL_COUNT total files"
echo "📂 Output directory: $OUTPUT_DIR"
echo ""

# Run post-generation fixes
FIX_SCRIPT="./post-generate-fixes.sh"
if [ -f "$FIX_SCRIPT" ]; then
    echo "🔧 Running post-generation fixes..."
    bash "$FIX_SCRIPT"
else
    echo "⚠️  Warning: post-generate-fixes.sh not found, skipping automatic fixes"
    echo ""
    echo "📝 Manual steps needed:"
    echo "   1. Replace old domain URLs with new domain"
    echo "   2. Replace template placeholders ({{ telefon }}, {{ companyName }})"
    echo "   3. Fix CSS file names if needed"
fi

echo ""
echo "🎉 Static site generation complete!"
echo ""
echo "📋 Next steps:"
echo "   1. Review generated files in: $OUTPUT_DIR"
echo "   2. Test locally: cd $OUTPUT_DIR && python3 -m http.server 8080"
echo "   3. Commit and push to GitHub for deployment"
echo ""
