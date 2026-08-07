import os
import glob

# Find all flutter plugin directories in pub cache
cache_dir = r"C:\Users\aries\AppData\Local\Pub\Cache\hosted\pub.dev"
if os.path.exists(cache_dir):
    for plugin_dir in os.listdir(cache_dir):
        gradle_path = os.path.join(cache_dir, plugin_dir, "android", "build.gradle")
        if os.path.exists(gradle_path):
            try:
                with open(gradle_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                if 'compileSdk = flutter.compileSdkVersion' in content:
                    content = content.replace('compileSdk = flutter.compileSdkVersion', 'compileSdkVersion 35')
                    with open(gradle_path, 'w', encoding='utf-8') as f:
                        f.write(content)
                    print(f"Patched {plugin_dir}")
                
                if 'compileSdk = 34' in content:
                    content = content.replace('compileSdk = 34', 'compileSdkVersion 35')
                    with open(gradle_path, 'w', encoding='utf-8') as f:
                        f.write(content)
                    print(f"Patched compileSdk=34 to 35 in {plugin_dir}")
                    
                if 'compileSdkVersion 34' in content:
                    content = content.replace('compileSdkVersion 34', 'compileSdkVersion 35')
                    with open(gradle_path, 'w', encoding='utf-8') as f:
                        f.write(content)
                    print(f"Patched compileSdkVersion 34 to 35 in {plugin_dir}")
            except Exception as e:
                pass
