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

    # Check for seo folder and schemas
    stdin, stdout, stderr = client.exec_command(
        'cd /home/u388646151/domains/mykirtemplate.top/public_html && ls -la site/snippets/ && echo "---SEO FOLDER---" && ls -la site/snippets/seo/ 2>/dev/null || echo "SEO folder not found"'
    )

    print(stdout.read().decode())
    error = stderr.read().decode()
    if error:
        print("Error:", error)

    client.close()

except Exception as e:
    print(f"Error: {e}")
