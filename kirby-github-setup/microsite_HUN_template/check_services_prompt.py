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

    # Check services prompt file
    stdin, stdout, stderr = client.exec_command(
        'cat /home/u388646151/domains/mykirtemplate.top/public_html/site/blueprints/prompts/services/auto-intro.yml'
    )

    content = stdout.read().decode()
    error = stderr.read().decode()

    if content:
        print("Server file content:")
        print(content)
    else:
        print("File not found or error:")
        print(error)

    client.close()

except Exception as e:
    print(f"Error: {e}")
