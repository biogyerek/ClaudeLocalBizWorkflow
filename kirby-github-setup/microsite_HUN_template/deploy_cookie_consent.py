#!/usr/bin/env python3
import paramiko

hostname = '82.29.186.244'
port = 65002
username = 'u388646151'
password = '3W1BcKHl#LtW!fCc'
remote_path = '/home/u388646151/domains/mykirtemplate.top/public_html'

files_to_upload = [
    ('site/snippets/cookie-consent.php', 'site/snippets/cookie-consent.php'),
    ('site/snippets/tag/google-analytics.php', 'site/snippets/tag/google-analytics.php'),
    ('site/snippets/tag/facebook-pixel.php', 'site/snippets/tag/facebook-pixel.php'),
    ('site/snippets/layouts/head.php', 'site/snippets/layouts/head.php'),
    ('site/snippets/layouts/footer.php', 'site/snippets/layouts/footer.php'),
    ('site/blueprints/sections/site/setup.yml', 'site/blueprints/sections/site/setup.yml'),
]

try:
    print("Connecting to server...")
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password)

    sftp = client.open_sftp()

    for local_file, remote_file in files_to_upload:
        print(f"Uploading {local_file}...")
        sftp.put(local_file, f'{remote_path}/{remote_file}')
        print(f"  -> Uploaded!")

    sftp.close()

    # Clear cache
    print("\nClearing cache...")
    stdin, stdout, stderr = client.exec_command(
        f'cd {remote_path} && rm -rf site/cache/*'
    )
    stdout.read()
    print("Cache cleared!")

    client.close()

    print("\n" + "="*60)
    print("COOKIE CONSENT DEPLOYMENT COMPLETE!")
    print("="*60)
    print("\nThe following features have been added:")
    print("1. Google Consent Mode v2 implementation")
    print("2. Facebook Pixel with consent support")
    print("3. Cookie consent banner with icon-only button")
    print("4. New fields in Site Settings for GA4 and FB Pixel IDs")
    print("\nNext steps:")
    print("1. Log into the Kirby Panel")
    print("2. Go to Site Settings -> Technical Setup")
    print("3. Add your Google Analytics 4 ID (e.g., G-XXXXXXXXXX)")
    print("4. Add your Facebook Pixel ID (numbers only)")
    print("5. Visit the website to see the cookie consent in action")
    print("\nThe cookie icon will appear in the bottom-left corner.")
    print("="*60)

except Exception as e:
    print(f"Error: {e}")
    import traceback
    traceback.print_exc()
