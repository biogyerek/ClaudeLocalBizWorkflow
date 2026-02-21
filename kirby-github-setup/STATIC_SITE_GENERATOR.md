# Kirby Static Site Generator Setup Guide

This guide walks you through installing and configuring the Kirby Static Site Generator plugin to convert your Kirby CMS installation into static HTML files.

## Prerequisites

- PHP 8.2 (already configured)
- Composer (composer.phar included in project root)
- Active internet connection for GitHub package downloads

## Installation

### 1. Install the Plugin

Navigate to your Kirby installation and install the static site generator:

```bash
cd microsite_base
/usr/local/opt/php@8.2/bin/php ../composer.phar require jreisdorf/kirby-static-site-generator
```

### 2. Configure the Plugin

Create or edit `microsite_base/site/config/config.php` and add:

```php
<?php

return [
    'jreisdorf.static-site-generator' => [

        // Output directory for static files
        'output_folder' => './static',

        // Base URL (change if not at domain root)
        'base_url' => '/',

        // Files/folders to preserve during regeneration
        'preserve' => [
            '.git',
            '.gitignore',
            'CNAME',
            'README.md'
        ],

        // Skip copying media files (if hosted on CDN)
        'skip_media' => false,

        // Skip pages with specific templates
        'skip_templates' => [
            'error'
        ],

        // For multilingual sites: skip untranslated pages
        'ignore_untranslated_pages' => false,

        // Custom index filename
        'index_file_name' => 'index.html',

        // Enable HTTP endpoint for generation
        'endpoint' => 'generate-static'
    ]
];
```

## Usage Methods

### Method 1: CLI Command (Recommended)

Generate static files from the command line:

```bash
cd microsite_base
php vendor/bin/kirby ssg:generate
```

The static files will be created in `microsite_base/static/` directory.

### Method 2: HTTP Endpoint

Visit this URL in your browser to trigger generation:

```
http://your-site.local/generate-static
```

### Method 3: Panel Field

Add a button to your Kirby Panel by editing `microsite_base/site/blueprints/site.yml`:

```yaml
fields:
  generateStatic:
    type: staticSiteGenerator
    label: Generate Static Site
    help: Click to generate static HTML files
    progress: Generating static site...
    success: Static site generated successfully!
    error: Generation failed. Check error logs.
```

Then generate from the Panel interface.

### Method 4: Programmatically

Use in hooks or custom controllers:

```php
use JReisdorf\StaticSiteGenerator;

$generator = new StaticSiteGenerator($kirby);
$generator->generate();
```

## Advanced Configuration

### Custom Routes

Add custom routes for dynamic content like pagination:

```php
'custom_routes' => [
    [
        'path' => '/blog/page/{page}',
        'page' => 'blog'
    ],
    [
        'path' => '/services/page/{page}',
        'page' => 'services'
    ]
]
```

### Page Filtering

Filter which pages get generated:

```php
'custom_filters' => [
    'template' => 'article',
    'status' => 'published',
    'date' => '>=2024-01-01'
]
```

## Deployment

### Option 1: Deploy to Netlify

1. Generate static files: `php vendor/bin/kirby ssg:generate`
2. Connect your GitHub repo to Netlify
3. Set build directory to `microsite_base/static`
4. Deploy

### Option 2: Deploy to Any CDN/Host

1. Generate static files
2. Upload contents of `microsite_base/static/` to your host
3. No PHP required on production server

### Option 3: GitHub Pages

1. Generate static files
2. Copy `static/` contents to a `gh-pages` branch
3. Enable GitHub Pages in repository settings

## Output Structure

After generation, your `static/` folder will contain:

```
static/
├── index.html              # Home page
├── services/
│   └── index.html
├── service-areas/
│   └── index.html
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
└── media/                  # If skip_media is false
```

## Troubleshooting

### Issue: GitHub Connection Timeout

If you see connection errors during installation:

1. Check your VPN/firewall settings
2. Try using a different network
3. Temporarily disable VPN (if applicable)

### Issue: PHP Version Mismatch

Ensure PHP 8.2 is active:

```bash
/usr/local/opt/php@8.2/bin/php -v
```

### Issue: Generated Files Missing

1. Check that pages are published (not drafts)
2. Verify templates aren't in `skip_templates` list
3. Check file permissions on output folder

### Issue: Links Broken in Static Site

Update `base_url` in config if site isn't at domain root:

```php
'base_url' => '/subdirectory/'
```

## Limitations

The static generator does NOT support:

- Dynamic PHP code execution
- Database queries at runtime
- Form submissions (without JavaScript)
- Query parameters (unless JS-processed)
- Kirby's built-in pagination (use custom routes)

## Best Practices

1. **Test locally first**: Generate and test static files locally before deploying
2. **Version control**: Add `static/` to `.gitignore` - don't commit generated files
3. **Automate**: Set up CI/CD to auto-generate on content changes
4. **Forms**: Use third-party services (Netlify Forms, Formspree) for contact forms
5. **Search**: Implement client-side search (Lunr.js, Algolia)

## Automation Example

Create a GitHub Action to auto-generate on push:

```yaml
name: Generate Static Site

on:
  push:
    branches: [ main ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    - name: Install dependencies
      run: cd microsite_base && composer install
    - name: Generate static site
      run: cd microsite_base && php vendor/bin/kirby ssg:generate
    - name: Deploy
      # Add deployment step here
```

## Resources

- [Kirby Static Site Generator Plugin](https://github.com/jonathan-reisdorf/kirby-static-site-generator)
- [Kirby CMS Documentation](https://getkirby.com/docs)
- [Kirby Forum](https://forum.getkirby.com)

## Support

For issues with:
- The plugin: https://github.com/jonathan-reisdorf/kirby-static-site-generator/issues
- Kirby CMS: https://forum.getkirby.com
- This project: Create an issue in this repository
