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

    # Check for site.txt
    stdin, stdout, stderr = client.exec_command(
        'cd /home/u388646151/domains/mykirtemplate.top/public_html && ls -la content/ | grep site.txt && echo "---FILE CONTENT---" && cat content/site.txt 2>/dev/null || echo "File not found"'
    )

    print(stdout.read().decode())
    error = stderr.read().decode()
    if error:
        print("Error:", error)

    client.close()

except Exception as e:
    print(f"Error: {e}")
