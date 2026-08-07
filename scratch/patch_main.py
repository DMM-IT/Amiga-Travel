import re
import sys

main_dart_path = r"c:\laragon\www\AMIGA\Amiga-Travel\flutter_app\lib\main.dart"

try:
    with open(main_dart_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Replace .withValues(alpha: X) with .withOpacity(X)
    content = re.sub(r'\.withValues\(alpha:\s*([0-9.]+)\)', r'.withOpacity(\1)', content)

    # Replace initialValue: with value: in DropdownButtonFormField (around line 10251)
    content = content.replace('initialValue: _refundMethod,', 'value: _refundMethod,')

    with open(main_dart_path, 'w', encoding='utf-8') as f:
        f.write(content)
        
    print("Successfully patched main.dart")
except Exception as e:
    print(f"Error: {e}")
    sys.exit(1)
