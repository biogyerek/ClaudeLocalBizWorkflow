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

    # Upload the services blueprint
    print("Uploading services.yml...")
    sftp = client.open_sftp()
    sftp.put('site/blueprints/pages/services.yml', f'{remote_path}/site/blueprints/pages/services.yml')
    sftp.close()

    print("Services blueprint uploaded!")

    # Clear cache
    print("\nClearing cache...")
    stdin, stdout, stderr = client.exec_command(
        f'cd {remote_path} && rm -rf site/cache/*'
    )
    stdout.read()

    print("Cache cleared!")

    client.close()
    print("\n" + "="*50)
    print("KESZ! Most:")
    print("1. Jelentkezz KI es BE a Panel-be")
    print("2. Nyisd meg a Szolgaltatasaink oldalt")
    print("3. Most MAR magyar nyelvu prompt-ot kell latnod!")
    print("="*50)

except Exception as e:
    print(f"Error: {e}")
    import traceback
    traceback.print_exc()
