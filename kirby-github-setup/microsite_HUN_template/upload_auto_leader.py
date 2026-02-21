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

    # Upload the auto-leader file
    print("Uploading auto-leader.yml...")
    sftp = client.open_sftp()
    sftp.put('site/blueprints/sections/pages/auto-leader.yml', f'{remote_path}/site/blueprints/sections/pages/auto-leader.yml')
    sftp.close()

    print("\nAuto-leader prompt uploaded successfully!")

    client.close()
    print("\n" + "="*50)
    print("Prompt variables fixed!")
    print("="*50)

except Exception as e:
    print(f"Error: {e}")
    import traceback
    traceback.print_exc()
