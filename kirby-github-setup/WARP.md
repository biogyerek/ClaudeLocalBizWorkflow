# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a Kirby CMS GitHub setup project that provides a foundation for deploying and managing Kirby CMS projects with GitHub integration. The project is currently minimal, containing primarily configuration files for project structure.

**Tech Stack:**
- PHP 8.0+ (8.2+ recommended per README)
- Kirby CMS 4.0+
- Composer for dependency management

## Key Commands

### Installation
```bash
# Install Kirby CMS and dependencies
composer install
```

### Dependency Management
```bash
# Update dependencies
composer update

# Update specific package
composer update getkirby/cms
```

### PHP Version Check
```bash
# Verify PHP version (must be 8.0+)
php -v
```

## Architecture Notes

### Directory Structure (Expected)
Based on the `.gitignore` configuration, the expected Kirby CMS structure includes:
- `/site/` - Kirby site configuration, templates, blueprints, and controllers
  - `/site/cache/` - Cached files (gitignored)
  - `/site/sessions/` - Session data (gitignored)
  - `/site/accounts/` - User accounts (gitignored for security)
- `/content/` - Content files in Kirby's text format (gitignored, tracked separately)
- `/media/` - Generated thumbnails and resized images (gitignored)
- `/vendor/` - Composer dependencies (gitignored)

### Version Control Strategy
- Content files are gitignored by default, suitable for deployments where content is managed separately or synced via other means
- User accounts and sessions are excluded for security
- Only `/content/.gitkeep` is tracked to maintain directory structure

### Configuration
- Environment-specific settings should be stored in `.env` or `.env.local` files (gitignored)
- The project uses Composer's autoloader optimization for production deployments

## Development Workflow

Since this is a Kirby CMS project:
1. Kirby CMS has no build step - it's a flat-file CMS that runs directly with PHP
2. Content is typically managed through Kirby's Panel (admin interface) accessed at `/panel`
3. Templates and site logic are PHP-based and located in the `/site` directory
4. There are no frontend build tools configured in this setup (no npm scripts beyond package metadata)

## Important Considerations

- **No existing Kirby structure**: The repository currently contains only configuration files. The actual Kirby CMS directories (`/site`, `/content`, `/kirby`) will be created after running `composer install`
- **No CI/CD configured yet**: While the README mentions GitHub Actions workflows, these are not yet implemented
- **Content management**: The gitignore excludes content by default - consider your content versioning strategy based on your deployment needs
- **PHP version**: Composer requires PHP 8.0+, but README specifies 8.2+ - ensure consistency when deploying

## Future Development Areas

Based on README features that are not yet implemented:
- GitHub Actions workflows for CI/CD
- Environment configuration templates
- Deployment automation scripts
