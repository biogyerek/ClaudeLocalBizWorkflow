#!/usr/bin/env python3
import paramiko
import os
import tarfile
import sys
from pathlib import Path

hostname = '82.29.186.244'
port = 65002
username = 'u388646151'
password = '3W1BcKHl#LtW!fCc'
remote_path = '/home/u388646151/domains/mykirtemplate.top/public_html'

# Files and directories to exclude
exclude_patterns = [
    '.git',
    '.github',
    '.claude',
    'node_modules',
    '.DS_Store',
    '__pycache__',
    '*.pyc',
    'deploy.py',
    'deploy_files.py',
    'check_server.py',
    'deploy.exp',
    '.gitignore'
]

def should_exclude(path):
    """Check if a path should be excluded"""
    path_str = str(path)
    for pattern in exclude_patterns:
        if pattern in path_str or path_str.endswith(pattern.replace('*', '')):
            return True
    return False

try:
    print("Creating archive of local files...")

    # Create tar archive
    archive_name = 'deployment.tar.gz'
    with tarfile.open(archive_name, 'w:gz') as tar:
        for root, dirs, files in os.walk('.'):
            # Filter out excluded directories
            dirs[:] = [d for d in dirs if not should_exclude(os.path.join(root, d))]

            for file in files:
                file_path = os.path.join(root, file)
                if not should_exclude(file_path):
                    # Add file to archive with relative path
                    arcname = file_path.lstrip('./')
                    tar.add(file_path, arcname=arcname)

    print(f"Archive created: {archive_name}")

    # Connect to server
    print(f"\nConnecting to {hostname}:{port}...")
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password)

    # Upload archive
    print(f"Uploading archive to server...")
    sftp = client.open_sftp()
    remote_archive = f'{remote_path}/{archive_name}'
    sftp.put(archive_name, remote_archive)
    sftp.close()
    print("Upload complete!")

    # Extract archive on server
    print("\nExtracting files on server...")
    commands = [
        f'cd {remote_path}',
        f'tar -xzf {archive_name}',
        f'rm {archive_name}',
        'ls -la'
    ]

    stdin, stdout, stderr = client.exec_command(' && '.join(commands))
    output = stdout.read().decode()
    error = stderr.read().decode()

    if output:
        print("\nServer directory contents:")
        print(output)

    if error and 'cannot' not in error.lower():
        print(f"\nInfo: {error}")

    # Clean up local archive
    os.remove(archive_name)
    print(f"\nLocal archive removed: {archive_name}")

    client.close()

    print("\n" + "="*50)
    print("Deployment successful!")
    print("="*50)

except Exception as e:
    print(f"Error: {e}")
    import traceback
    traceback.print_exc()
    sys.exit(1)
