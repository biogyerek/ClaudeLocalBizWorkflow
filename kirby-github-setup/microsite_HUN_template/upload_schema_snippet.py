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

    # Create seo directory on server
    print("Creating seo directory...")
    stdin, stdout, stderr = client.exec_command(f'mkdir -p {remote_path}/site/snippets/seo')
    stdout.read()

    # Upload the schema snippet file
    print("Uploading schemas.php...")
    sftp = client.open_sftp()
    sftp.put('site/snippets/seo/schemas.php', f'{remote_path}/site/snippets/seo/schemas.php')
    sftp.close()

    print("\nSchema snippet uploaded successfully!")

    # Verify
    stdin, stdout, stderr = client.exec_command(f'cat {remote_path}/site/snippets/seo/schemas.php')
    content = stdout.read().decode()
    print("\nUploaded file content:")
    print(content)

    client.close()
    print("\n" + "="*50)
    print("Schema markup restored!")
    print("="*50)

except Exception as e:
    print(f"Error: {e}")
    import traceback
    traceback.print_exc()
