import sys

main_dart_path = r"c:\laragon\www\AMIGA\Amiga-Travel\flutter_app\lib\main.dart"

try:
    with open(main_dart_path, 'r', encoding='utf-8') as f:
        lines = f.readlines()

    # Lines with initialValue -> value
    dropdown_lines = [2225, 2249, 2268, 5996, 8337, 8347, 8918, 8934]
    for idx in dropdown_lines:
        line_idx = idx - 1  # 0-indexed
        lines[line_idx] = lines[line_idx].replace('initialValue:', 'value:')

    # Lines with activeThumbColor -> activeColor
    switch_lines = [2425, 5466, 6919]
    for idx in switch_lines:
        line_idx = idx - 1
        lines[line_idx] = lines[line_idx].replace('activeThumbColor:', 'activeColor:')

    with open(main_dart_path, 'w', encoding='utf-8') as f:
        f.writelines(lines)
        
    print("Successfully patched main.dart again")
except Exception as e:
    print(f"Error: {e}")
    sys.exit(1)
