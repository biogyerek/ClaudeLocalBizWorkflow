# Image Assets

## Required Images

### Logo & Favicon

| File | Size | Format | Description |
|------|------|--------|-------------|
| `logo.png` | ~200x60px | PNG (transparent) | Company logo |
| `favicon-16x16.png` | 16x16px | PNG | Browser tab icon |
| `favicon-32x32.png` | 32x32px | PNG | Browser tab icon |
| `apple-touch-icon.png` | 180x180px | PNG | iOS home screen icon |
| `og-image.jpg` | 1200x630px | JPG | Social media sharing |

### Service Images

Location: `services/`

| File | Size | Format | Description |
|------|------|--------|-------------|
| `[service-slug].jpg` | 600x400px | JPG/WebP | Service card image |

Example:
- `services/konyhabutor-keszites.jpg`
- `services/beepitett-szekreny.jpg`

### Team Images (Optional)

Location: `team/`

| File | Size | Format | Description |
|------|------|--------|-------------|
| `[person-slug].jpg` | 400x400px | JPG | Team member photo |

Example:
- `team/kovacs-janos.jpg`

### Placeholder Images

Location: `placeholders/`

These are used when actual images are not available:

| File | Size | Description |
|------|------|-------------|
| `placeholder-service.jpg` | 600x400px | Generic service placeholder |
| `placeholder-hero.jpg` | 1400x600px | Hero section placeholder |
| `placeholder-team.jpg` | 400x400px | Team member placeholder |

## Image Guidelines

### Quality
- Use high-quality, professional images
- Optimize for web (compress without visible quality loss)
- Prefer WebP format with JPG fallback

### Content
- Service images should clearly represent the service
- Avoid stock photos that look generic
- Include real work photos when possible

### SEO
- All images will get automatic alt text based on context
- File names should be descriptive (use slugs)

## Tools for Image Optimization

- [Squoosh](https://squoosh.app/) - Free, browser-based
- [TinyPNG](https://tinypng.com/) - PNG/JPG compression
- [ImageOptim](https://imageoptim.com/) - Mac app
