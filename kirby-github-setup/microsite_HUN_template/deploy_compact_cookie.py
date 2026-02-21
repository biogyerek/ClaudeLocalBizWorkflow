#!/usr/bin/env python3
import paramiko

hostname = '82.29.186.244'
port = 65002
username = 'u388646151'
password = '3W1BcKHl#LtW!fCc'
remote_path = '/home/u388646151/domains/mykirtemplate.top/public_html'

try:
    print("Connecting to server...")
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password)

    sftp = client.open_sftp()

    print("Uploading compact cookie consent...")
    sftp.put('site/snippets/cookie-consent.php', f'{remote_path}/site/snippets/cookie-consent.php')
    print("Uploaded!")

    sftp.close()

    # Clear cache
    print("\nClearing cache...")
    stdin, stdout, stderr = client.exec_command(
        f'cd {remote_path} && rm -rf site/cache/*'
    )
    stdout.read()
    print("Cache cleared!")

    client.close()

    print("\n" + "="*50)
    print("KOMPAKT COOKIE DESIGN KESZ!")
    print("="*50)
    print("\nA suti ablak most mar sokkal kisebb:")
    print("- Kompakt popup bal also sarokban")
    print("- Max 320px szeles")
    print("- Rovid szoveg es inline checkboxok")
    print("- 3 kis gomb")
    print("\nFrissitsd az oldalt es nezd meg!")
    print("="*50)

except Exception as e:
    print(f"Error: {e}")
    import traceback
    traceback.print_exc()
