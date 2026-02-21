#!/bin/bash
# Server Cleanup Script for mykirtemplate.top
# Run this when SSH connection works again

echo "🔧 Kirby Microsite - Server Cleanup Script"
echo "=========================================="
echo ""

# Server details
SERVER="u388646151@82.29.186.244"
PORT="65002"
SSH_KEY="~/.ssh/id_ed25519"
DOMAIN_PATH="/home/u388646151/domains/mykirtemplate.top/public_html"

echo "📡 Connecting to server..."
ssh -p $PORT -i $SSH_KEY $SERVER << 'ENDSSH'
    cd /home/u388646151/domains/mykirtemplate.top/public_html

    echo ""
    echo "📂 Current location:"
    pwd

    echo ""
    echo "🗑️  Deleting old American location folders..."
    cd content/2_szolgaltatasi-teruletek

    # List folders before deletion
    echo "Folders to delete:"
    ls -1d 0_asztalos-anne-arundel-county 2>/dev/null || echo "  - asztalos-anne-arundel-county (not found)"
    ls -1d 0_asztalos-baltimore-* 2>/dev/null || echo "  - baltimore folders (not found)"
    ls -1d 0_asztalos-berkeley-county-wv 2>/dev/null || echo "  - berkeley-county-wv (not found)"
    ls -1d 0_asztalos-carroll-county 2>/dev/null || echo "  - carroll-county (not found)"
    ls -1d 0_asztalos-clearwater 2>/dev/null || echo "  - clearwater (not found)"
    ls -1d 0_asztalos-frederick-county 2>/dev/null || echo "  - frederick-county (not found)"

    # Delete all old American location folders
    rm -rf 0_asztalos-anne-arundel-county
    rm -rf 0_asztalos-baltimore-city
    rm -rf 0_asztalos-baltimore-county
    rm -rf 0_asztalos-berkeley-county-wv
    rm -rf 0_asztalos-carroll-county
    rm -rf 0_asztalos-clearwater
    rm -rf 0_asztalos-frederick-county
    rm -rf 0_asztalos-harford-county
    rm -rf 0_asztalos-hillsborough-county
    rm -rf 0_asztalos-howard-county
    rm -rf 0_asztalos-largo
    rm -rf 0_asztalos-montgomery-county
    rm -rf 0_asztalos-new-castle-county-de
    rm -rf 0_asztalos-northern-virginia
    rm -rf 0_asztalos-pasco-county
    rm -rf 0_asztalos-pinellas-county
    rm -rf 0_asztalos-prince-george-s-county
    rm -rf 0_asztalos-st-petersburg
    rm -rf 0_asztalos-tampa
    rm -rf 0_asztalos-washington-county
    rm -rf 0_asztalos-washington-d-c
    rm -rf 0_asztalos-york-county-pa

    echo "✅ Old folders deleted"

    echo ""
    echo "📂 Remaining folders:"
    ls -1

    echo ""
    echo "🧹 Clearing cache..."
    cd ../../site
    rm -rf cache/*
    rm -rf sessions/*

    echo "✅ Cache cleared"

    echo ""
    echo "🔐 Setting permissions..."
    cd ../..
    chmod -R 777 site/sessions site/cache media

    echo "✅ Permissions set"

    echo ""
    echo "🎉 Cleanup complete!"
    echo ""
    echo "📊 Final structure:"
    ls -la content/2_szolgaltatasi-teruletek/
ENDSSH

echo ""
echo "✅ Server cleanup completed successfully!"
echo ""
echo "🌐 Now refresh: https://mykirtemplate.top/panel/site?tab=locations"
