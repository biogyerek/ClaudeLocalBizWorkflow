#!/usr/bin/env python3
import paramiko

hostname = '82.29.186.244'
port = 65002
username = 'u388646151'
password = '3W1BcKHl#LtW!fCc'

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password)

    print("Clearing Kirby cache...")

    # Clear the cache directory
    stdin, stdout, stderr = client.exec_command(
        'cd /home/u388646151/domains/mykirtemplate.top/public_html && rm -rf site/cache/* && echo "Cache cleared!"'
    )

    output = stdout.read().decode()
    error = stderr.read().decode()

    if output:
        print(output)
    if error:
        print("Info:", error)

    print("\nCache cleared successfully!")
    print("Now refresh the Kirby Panel (Ctrl+Shift+R or Cmd+Shift+R)")

    client.close()

except Exception as e:
    print(f"Error: {e}")
