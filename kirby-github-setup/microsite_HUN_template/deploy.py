#!/usr/bin/env python3
import paramiko
import sys

hostname = '82.29.186.244'
port = 65002
username = 'u388646151'
password = '3W1BcKHl#LtW!fCc'

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

    print(f"Connecting to {hostname}:{port}...")
    client.connect(hostname, port=port, username=username, password=password)

    # Initialize git and set up remote
    commands = [
        "cd domains/mykirtemplate.top/public_html",
        "git init",
        "git remote add origin https://github.com/biogyerek/kirby-github-setup.git || git remote set-url origin https://github.com/biogyerek/kirby-github-setup.git",
        "git fetch origin",
        "git reset --hard origin/main",
        "git branch -M main",
        "git branch --set-upstream-to=origin/main main"
    ]

    for cmd in commands:
        print(f"\nExecuting: {cmd}")
        stdin, stdout, stderr = client.exec_command(f"cd domains/mykirtemplate.top/public_html && {cmd}")

        output = stdout.read().decode()
        error = stderr.read().decode()

        if output:
            print(output)
        if error and "already exists" not in error.lower():
            print(f"Info: {error}")

    print("\n✓ Git repository set up successfully!")
    print("✓ Files updated from GitHub!")

    client.close()

except Exception as e:
    print(f"Error: {e}")
    sys.exit(1)
