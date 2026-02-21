#!/bin/bash

# SEO Duplicate Content Checker
# Verifies that www redirects are properly configured

echo "🔍 SEO Duplicate Content Checker"
echo "=================================="
echo ""

STATIC_DIR="${1:-./static}"
DOMAIN="${2:-asztalosmesterbudapest.hu}"

if [ ! -d "$STATIC_DIR" ]; then
    echo "❌ Error: $STATIC_DIR not found"
    exit 1
fi

cd "$STATIC_DIR" || exit 1

echo "📋 Checking domain: $DOMAIN"
echo ""

# Check 1: CNAME file
echo "1️⃣ CNAME File Check"
if [ -f "CNAME" ]; then
    CNAME_CONTENT=$(cat CNAME)
    if [[ "$CNAME_CONTENT" == *"www."* ]]; then
        echo "   ❌ CNAME contains 'www.' - should be non-www only"
        echo "   Found: $CNAME_CONTENT"
    else
        echo "   ✅ CNAME correct: $CNAME_CONTENT (non-www)"
    fi
else
    echo "   ⚠️  CNAME file not found"
fi
echo ""

# Check 2: WWW links in HTML
echo "2️⃣ WWW Links in HTML"
WWW_COUNT=$(grep -r "www\.$DOMAIN" . --include="*.html" 2>/dev/null | wc -l | tr -d ' ')
if [ "$WWW_COUNT" -eq 0 ]; then
    echo "   ✅ No www links found in HTML files"
else
    echo "   ❌ Found $WWW_COUNT www links in HTML files:"
    grep -r "www\.$DOMAIN" . --include="*.html" | head -5
fi
echo ""

# Check 3: Canonical tags
echo "3️⃣ Canonical Tags"
WWW_CANONICAL=$(grep -r "rel=\"canonical\"" . --include="*.html" | grep "www\." | wc -l | tr -d ' ')
if [ "$WWW_CANONICAL" -eq 0 ]; then
    echo "   ✅ All canonical tags use non-www URLs"
else
    echo "   ❌ Found $WWW_CANONICAL canonical tags with www:"
    grep -r "rel=\"canonical\"" . --include="*.html" | grep "www\." | head -5
fi
echo ""

# Check 4: Open Graph URLs
echo "4️⃣ Open Graph URLs"
WWW_OG=$(grep -r "og:url" . --include="*.html" | grep "www\." | wc -l | tr -d ' ')
if [ "$WWW_OG" -eq 0 ]; then
    echo "   ✅ All OG URLs use non-www"
else
    echo "   ❌ Found $WWW_OG OG URLs with www:"
    grep -r "og:url" . --include="*.html" | grep "www\." | head -5
fi
echo ""

# Check 5: Sitemap
echo "5️⃣ Sitemap URLs"
if [ -f "sitemap.xml" ]; then
    WWW_SITEMAP=$(grep "<loc>" sitemap.xml | grep "www\." | wc -l | tr -d ' ')
    if [ "$WWW_SITEMAP" -eq 0 ]; then
        echo "   ✅ All sitemap URLs use non-www"
    else
        echo "   ❌ Found $WWW_SITEMAP sitemap URLs with www:"
        grep "<loc>" sitemap.xml | grep "www\." | head -5
    fi
else
    echo "   ⚠️  sitemap.xml not found"
fi
echo ""

# Check 6: robots.txt
echo "6️⃣ robots.txt Sitemap URL"
if [ -f "robots.txt" ]; then
    if grep -q "www\." robots.txt; then
        echo "   ❌ robots.txt contains www URLs:"
        grep "Sitemap:" robots.txt
    else
        echo "   ✅ robots.txt uses non-www URLs"
        grep "Sitemap:" robots.txt
    fi
else
    echo "   ⚠️  robots.txt not found"
fi
echo ""

# Summary
echo "📊 Summary"
echo "=========="
TOTAL_ISSUES=$((WWW_COUNT + WWW_CANONICAL + WWW_OG + WWW_SITEMAP))

if [ "$TOTAL_ISSUES" -eq 0 ] && [[ "$CNAME_CONTENT" != *"www."* ]]; then
    echo "✅ All checks passed! No duplicate content issues detected."
    echo ""
    echo "🔒 SEO Configuration:"
    echo "   - Canonical domain: https://$DOMAIN"
    echo "   - WWW redirect: www.$DOMAIN → $DOMAIN (via GitHub Pages)"
    echo "   - Redirect type: 301 Permanent"
else
    echo "❌ Found $TOTAL_ISSUES potential duplicate content issues"
    echo "   Please review the issues above and fix them."
fi
echo ""

# Live redirect test (optional)
if command -v curl &> /dev/null; then
    echo "🌐 Testing Live Redirect (optional)"
    echo "===================================="
    echo "Testing: https://www.$DOMAIN"

    REDIRECT=$(curl -sI "https://www.$DOMAIN" 2>/dev/null | grep -i "location:" | cut -d' ' -f2 | tr -d '\r')
    STATUS=$(curl -sI "https://www.$DOMAIN" 2>/dev/null | head -1 | cut -d' ' -f2)

    if [ "$STATUS" == "301" ] && [[ "$REDIRECT" == "https://$DOMAIN"* ]]; then
        echo "✅ WWW redirect working correctly!"
        echo "   Status: $STATUS"
        echo "   Redirects to: $REDIRECT"
    elif [ -z "$STATUS" ]; then
        echo "⚠️  Could not test live redirect (DNS not configured or site not live)"
    else
        echo "❌ Redirect not working as expected"
        echo "   Status: $STATUS"
        echo "   Location: $REDIRECT"
    fi
fi

echo ""
