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

    # Check for backups and compare content directories
    commands = [
        "cd /home/u388646151/domains/mykirtemplate.top/public_html",
        "ls -1 content/ | wc -l",
        "echo '---'",
        "find content/ -maxdepth 1 -type d | head -30"
    ]

    stdin, stdout, stderr = client.exec_command(' && '.join(commands))
    print(stdout.read().decode())

    client.close()

except Exception as e:
    print(f"Error: {e}")
