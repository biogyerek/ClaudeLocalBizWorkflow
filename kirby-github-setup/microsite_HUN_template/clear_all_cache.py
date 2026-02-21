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

    print("Clearing ALL Kirby caches...")

    # Clear all possible cache directories
    commands = [
        'cd /home/u388646151/domains/mykirtemplate.top/public_html',
        'rm -rf site/cache/*',
        'rm -rf media/.jobs/*',
        'rm -rf media/.thumbs/*',
        'find . -name ".lock" -delete',
        'echo "All caches cleared!"'
    ]

    stdin, stdout, stderr = client.exec_command(' && '.join(commands))

    output = stdout.read().decode()
    error = stderr.read().decode()

    if output:
        print(output)
    if error:
        print("Info:", error)

    print("\nAll caches cleared!")
    print("\nMost:")
    print("1. Jelentkezz KI a Kirby Panel-ből")
    print("2. Zárj be MINDEN böngésző tabot")
    print("3. Nyisd meg újra a Panel-t és jelentkezz be")

    client.close()

except Exception as e:
    print(f"Error: {e}")
