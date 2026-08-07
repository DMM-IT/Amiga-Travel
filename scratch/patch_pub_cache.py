import os

path = r"C:\Users\aries\AppData\Local\Pub\Cache\hosted\pub.dev\package_info_plus-9.0.1\android\build.gradle"

if os.path.exists(path):
    with open(path, 'r') as f:
        content = f.read()
    
    content = content.replace('compileSdk = flutter.compileSdkVersion', 'compileSdkVersion 35')
    
    with open(path, 'w') as f:
        f.write(content)
    print("Patched package_info_plus build.gradle")
else:
    print("File not found.")
