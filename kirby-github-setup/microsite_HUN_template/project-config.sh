#!/bin/bash

# Project Configuration
# =====================
# Ez a fájl tartalmazza a projekt-specifikus beállításokat.
# Új projekt indításakor töltsd ki ezeket az értékeket!

# Projekt információk
PROJECT_NAME="Asztalos Budapest"              # A projekt neve
DOMAIN="asztalosmesterbudapest.hu"            # Végleges domain (ahová deplolyolod)
SOURCE_URL="https://mykirtemplate.top"        # Honnan generálod a statikus oldalt

# Cégadatok (ezek kerülnek a placeholderek helyére)
COMPANY_NAME="Asztalos Budapest"              # Cégnév
PHONE="+36703546606"                          # Telefonszám
EMAIL="info@asztalosmesterbudapest.hu"        # Email cím (opcionális)

# Generálási beállítások
OLD_DOMAIN="mykirtemplate.top"                # Lecserélendő domain a forrásban
STATIC_DIR="./static"                         # Hová generáljuk a statikus oldalt

# GitHub beállítások (opcionális)
GITHUB_USERNAME="JoSzaki"                     # GitHub felhasználónév
GITHUB_REPO="asztalosmesterbudapest.hu"       # GitHub repo neve

# =====================
# FONTOS: Új projekt indításakor másold át a template-et és szerkeszd ezt a fájlt!
#
# 1. cp -r microsite_HUN_template my-new-project
# 2. cd my-new-project
# 3. nano project-config.sh
# 4. Állítsd be a fenti értékeket
# 5. bash generate-static.sh
# =====================

# Validáció - ne módosítsd ezt a részt
if [ "$DOMAIN" == "asztalosmesterbudapest.hu" ] && [ "$(pwd)" != *"asztalosmesterbudapest"* ]; then
    echo "⚠️  Figyelem: A project-config.sh még az alapértelmezett értékeket tartalmazza!"
    echo ""
    echo "Új projekt esetén kérlek szerkeszd a következő értékeket:"
    echo "  - PROJECT_NAME"
    echo "  - DOMAIN"
    echo "  - SOURCE_URL"
    echo "  - COMPANY_NAME"
    echo "  - PHONE"
    echo ""
    echo "Folytatod az alapértelmezett értékekkel? (y/n)"
    read -r response
    if [ "$response" != "y" ]; then
        echo "Megszakítva. Szerkeszd a project-config.sh fájlt és próbáld újra."
        exit 1
    fi
fi

# Export változók a child script-eknek
export PROJECT_NAME
export DOMAIN
export SOURCE_URL
export COMPANY_NAME
export PHONE
export EMAIL
export OLD_DOMAIN
export STATIC_DIR
export GITHUB_USERNAME
export GITHUB_REPO

# Debug output (opcionális)
if [ "$DEBUG" == "1" ]; then
    echo "📋 Project Configuration Loaded:"
    echo "   Project: $PROJECT_NAME"
    echo "   Domain: $DOMAIN"
    echo "   Source: $SOURCE_URL"
    echo "   Company: $COMPANY_NAME"
    echo "   Phone: $PHONE"
    echo ""
fi
