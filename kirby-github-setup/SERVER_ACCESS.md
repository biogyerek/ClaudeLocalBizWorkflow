# Server Access & Deployment Information

## SSH Connection Details

**Host:** 82.29.186.244
**Port:** 65002
**Username:** u388646151
**SSH Key:** ~/.ssh/id_ed25519

### Connection Command
```bash
ssh -p 65002 -i ~/.ssh/id_ed25519 u388646151@82.29.186.244
```

## Server Directory Structure

**Home Directory:** `/home/u388646151/`

**Domains Location:** `/home/u388646151/domains/`

### Available Domains

1. **fahazkivitelezes.hu** (Source site)
   - Path: `/home/u388646151/domains/fahazkivitelezes.hu/public_html/`
   - Status: Production site with Hungarian content

2. **konnyuszerkezeteshazepitese.hu**
   - Path: `/home/u388646151/domains/konnyuszerkezeteshazepitese.hu/public_html/`

3. **mykirtemplate.top** (Template site - copied from fahazkivitelezes.hu)
   - Path: `/home/u388646151/domains/mykirtemplate.top/public_html/`
   - Status: Template copy for testing
   - URL: http://mykirtemplate.top
   - Panel: http://mykirtemplate.top/panel

## File Transfer Commands

### Upload files via SCP
```bash
scp -P 65002 -i ~/.ssh/id_ed25519 -r /local/path/ u388646151@82.29.186.244:/home/u388646151/domains/DOMAIN/public_html/
```

### Download files via SCP
```bash
scp -P 65002 -i ~/.ssh/id_ed25519 -r u388646151@82.29.186.244:/home/u388646151/domains/DOMAIN/public_html/ /local/path/
```

### Sync with rsync
```bash
rsync -avz -e "ssh -p 65002 -i ~/.ssh/id_ed25519" /local/path/ u388646151@82.29.186.244:/home/u388646151/domains/DOMAIN/public_html/
```

## Kirby CMS Structure

### Important Directories
- `/site/` - Configuration, templates, blueprints, controllers, plugins
- `/content/` - All content files (text files with page data)
- `/media/` - Generated thumbnails and resized images
- `/kirby/` - Kirby CMS core files
- `/assets/` - CSS, JS, images

### Writable Directories (777 permissions)
- `/site/sessions/` - User sessions
- `/site/cache/` - Cache files
- `/media/` - Generated media files

## Completed Tasks

### 2024-12-02 - Server Setup
1. ✅ SSH connection established with u388646151@82.29.186.244:65002
2. ✅ Copied fahazkivitelezes.hu to mykirtemplate.top
3. ✅ Set proper file permissions (755 for files, 777 for cache/sessions/media)
4. ✅ Copied .htaccess file
5. ✅ Hungarian prompts configured for Kirby Copilot
6. ✅ Text replacements/placeholders system configured

### Kirby Configuration Updates (microsite_base)
1. ✅ Translated site-wide prompts to Hungarian:
   - auto-mission.yml (Misszió)
   - auto-tagline.yml (Jelmondat)
   - auto-backstory.yml (Háttértörténet)
   - auto-contact.yml (Kapcsolatfelvétel)

2. ✅ Fixed placeholder format in prompts:
   - Changed from `{{ site.companyName }}` to `{companyName}`
   - Now uses Text replacements fields correctly

## Next Steps

### To Deploy Updates to mykirtemplate.top:
```bash
# 1. Upload from local microsite_base to server
scp -P 65002 -i ~/.ssh/id_ed25519 -r microsite_base/* u388646151@82.29.186.244:/home/u388646151/domains/mykirtemplate.top/public_html/

# 2. Set permissions on server
ssh -p 65002 -i ~/.ssh/id_ed25519 u388646151@82.29.186.244 "chmod -R 755 domains/mykirtemplate.top/public_html && chmod -R 777 domains/mykirtemplate.top/public_html/site/sessions domains/mykirtemplate.top/public_html/site/cache domains/mykirtemplate.top/public_html/media"
```

### To Convert Kirby to Static HTML (When Ready):
```bash
# Option 1: Using wget
wget --mirror --convert-links --adjust-extension --page-requisites --no-parent http://mykirtemplate.top

# Option 2: Using HTTrack
httrack http://mykirtemplate.top -O /path/to/output

# Option 3: Install Kirby StaticBuilder plugin
# Then run from panel or command line to generate static files
```

## Troubleshooting

### If Panel shows "Unauthenticated" error:
1. Clear sessions: `rm -rf site/sessions/*`
2. Clear cache: `rm -rf site/cache/*`
3. Check permissions on sessions/cache directories (must be 777)
4. Verify config.php has correct settings

### If pages don't load:
1. Check .htaccess file exists
2. Verify mod_rewrite is enabled on server
3. Check file permissions (755 for directories, 644 for files)

## Notes

- Server appears to be shared hosting (Apache with cPanel/DirectAdmin)
- PHP version needs to be 8.2 or higher for Kirby 4.x
- Auto-detection of domain name works (uses environment variable)
- All three domains are on the same server under same account
