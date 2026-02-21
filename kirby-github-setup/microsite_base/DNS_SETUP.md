# DNS Setup Guide for asztalosmesterbudapest.hu

## Current Configuration (Correct ✅)

### CNAME File
```
asztalosmesterbudapest.hu
```

**Important:** Only the non-www version is in the CNAME file. This is correct and prevents duplicate content issues.

## Required DNS Records at Domain Registrar

### For Non-WWW Domain (Primary)

**A Records** (Point to GitHub Pages):
```
@     A     185.199.108.153
@     A     185.199.109.153
@     A     185.199.110.153
@     A     185.199.111.153
```

### For WWW Subdomain (Redirect to non-WWW)

**CNAME Record**:
```
www   CNAME   joszaki.github.io.
```

**Important:** The trailing dot (`.`) is intentional.

## How WWW → Non-WWW Redirect Works

1. **DNS Level**: The `www` CNAME points to GitHub Pages
2. **GitHub Pages Level**: When GitHub Pages receives a request for `www.asztalosmesterbudapest.hu`, it:
   - Checks the CNAME file in the repository
   - Finds `asztalosmesterbudapest.hu` (without www)
   - Automatically issues a **301 Permanent Redirect** to the non-www version

## SEO Benefits

✅ **No Duplicate Content**: All versions point to canonical URL
✅ **301 Redirects**: Search engines consolidate ranking signals
✅ **Canonical Tags**: All pages declare `https://asztalosmesterbudapest.hu` as canonical
✅ **Consistent Sitemap**: Sitemap.xml uses non-www URLs only

## Verification

### Test the redirect:
```bash
# Should redirect to non-www with 301
curl -I https://www.asztalosmesterbudapest.hu

# Should return 200 OK
curl -I https://asztalosmesterbudapest.hu
```

### Check canonical tags:
```bash
# All pages should have canonical without www
grep -r "rel=\"canonical\"" . --include="*.html" | head -5
```

## Current Status

- ✅ CNAME file: non-www only
- ✅ Canonical tags: all non-www
- ✅ Open Graph URLs: all non-www
- ✅ Sitemap: all non-www
- ✅ robots.txt: sitemap URL is non-www

## GitHub Pages Settings

**Repository**: JoSzaki/asztalosmesterbudapest.hu
**Branch**: main
**Source**: / (root)
**Custom Domain**: asztalosmesterbudapest.hu
**Enforce HTTPS**: ✅ Enabled

## DNS Propagation

After updating DNS records:
- **Minimum**: 30 minutes
- **Typical**: 2-4 hours
- **Maximum**: 48 hours

Use https://dnschecker.org to verify DNS propagation globally.

## Troubleshooting

### WWW doesn't redirect
1. Check DNS CNAME record for `www`
2. Wait for DNS propagation (up to 48h)
3. Clear browser cache
4. Verify GitHub Pages custom domain setting

### SSL Certificate Issues
1. GitHub Pages auto-provisions SSL certificates
2. Can take 10-60 minutes after DNS setup
3. "Enforce HTTPS" option must be enabled in repo settings

### Mixed Content Warnings
All internal links use HTTPS (enforced in `<head>`):
```html
<meta http-equiv="Content-Security-Policy" content="block-all-mixed-content" />
```

## References

- [GitHub Pages Custom Domain Docs](https://docs.github.com/en/pages/configuring-a-custom-domain-for-your-github-pages-site)
- [Managing a custom domain for your GitHub Pages site](https://docs.github.com/en/pages/configuring-a-custom-domain-for-your-github-pages-site/managing-a-custom-domain-for-your-github-pages-site)
