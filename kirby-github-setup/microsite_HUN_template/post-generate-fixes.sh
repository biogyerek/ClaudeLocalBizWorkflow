#!/bin/bash

# Post-Generate Fixes Script
# Automatically fixes common issues after static site generation

# Load project configuration if exists
if [ -f "./project-config.sh" ]; then
    source ./project-config.sh
fi

echo "🔧 Running post-generation fixes..."

# Configuration (can be overridden by project-config.sh or environment variables)
STATIC_DIR="${STATIC_DIR:-./static}"
DOMAIN="${DOMAIN:-asztalosmesterbudapest.hu}"
PHONE="${PHONE:-+36703546606}"
COMPANY_NAME="${COMPANY_NAME:-Asztalos Budapest}"
OLD_DOMAIN="${OLD_DOMAIN:-mykirtemplate.top}"

echo "📋 Configuration:"
echo "   Static directory: $STATIC_DIR"
echo "   Target domain: $DOMAIN"
echo "   Phone: $PHONE"
echo "   Company: $COMPANY_NAME"
echo "   Old domain to replace: $OLD_DOMAIN"
echo ""

# Check if static directory exists
if [ ! -d "$STATIC_DIR" ]; then
    echo "❌ Error: $STATIC_DIR directory not found!"
    exit 1
fi

cd "$STATIC_DIR" || exit 1

echo "📝 Step 1: Fixing CSS file names..."
# Rename CSS files from @v=version.css to .css
find ./assets/css -name "*@v=*.css" -type f 2>/dev/null | while read -r file; do
    newname=$(echo "$file" | sed 's/@v=[0-9]*\.css/.css/')
    if [ "$file" != "$newname" ]; then
        mv "$file" "$newname"
        echo "  ✓ Renamed: $(basename "$file") → $(basename "$newname")"
    fi
done

echo "📝 Step 2: Fixing CSS references in HTML files..."
# Fix CSS references in HTML: .css@v=123.css → .css?v=123
find . -name "*.html" -type f -exec sed -i '' 's/\.css@v=\([0-9]*\)\.css/.css?v=\1/g' {} \;
echo "  ✓ CSS references updated"

echo "📝 Step 3: Replacing template placeholders..."
# Replace {{ telefon }} placeholder
find . -name "*.html" -type f -exec sed -i '' "s/{{ telefon }}/$PHONE/g" {} \;
echo "  ✓ Phone numbers updated"

# Replace {{ companyName }} placeholder
find . -name "*.html" -type f -exec sed -i '' "s/{{ companyName }}/$COMPANY_NAME/g" {} \;
echo "  ✓ Company name updated"

echo "📝 Step 4: Fixing domain URLs..."
# Replace old domain with correct domain
find . -name "*.html" -type f -exec sed -i '' "s|https://$OLD_DOMAIN|https://$DOMAIN|g" {} \;
find . -name "*.html" -type f -exec sed -i '' "s|http://$OLD_DOMAIN|https://$DOMAIN|g" {} \;
find . -name "*.html" -type f -exec sed -i '' "s|$OLD_DOMAIN|$DOMAIN|g" {} \;
echo "  ✓ Domain URLs updated ($OLD_DOMAIN → $DOMAIN)"

echo "📝 Step 5: Fixing escaped URLs in JSON..."
# Fix escaped URLs in JSON-LD schemas
find . -name "*.html" -type f -exec sed -i '' "s|$OLD_DOMAIN|$DOMAIN|g" {} \;
echo "  ✓ JSON-LD schemas updated"

echo "📝 Step 6: Fixing footer menus..."
# Add footer menu to pages that are missing it
FOOTER_FIX_SCRIPT="$(dirname "$0")/fix-footer-menu.py"
if [ -f "$FOOTER_FIX_SCRIPT" ]; then
    python3 "$FOOTER_FIX_SCRIPT" "$STATIC_DIR"
else
    echo "  ⚠️  fix-footer-menu.py not found, skipping footer menu fix"
fi

cd ..

echo ""
echo "✅ All post-generation fixes completed successfully!"
echo "📊 Summary:"
echo "   - CSS files renamed and references fixed"
echo "   - Template placeholders replaced"
echo "   - Domain URLs updated to https://$DOMAIN"
echo "   - Phone: $PHONE"
echo "   - Company: $COMPANY_NAME"
echo ""
