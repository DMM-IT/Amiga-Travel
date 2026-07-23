# How to Update the Android App (APK)

When you make changes to the Flutter app and want to push an update to your users, follow these exact steps. The system is designed to automatically detect your new version and force existing users to update.

### Step 1: Bump the Version Number

You need to update the version number in two places:

1. **`flutter_app/pubspec.yaml`**
   Find the `version:` line near the top and increment it.
   ```yaml
   # Example: Change from 1.0.1+2 to 1.0.2+3
   version: 1.0.2+3
   ```

2. **`flutter_app/lib/main.dart`**
   Find the `appVersion` constant (around line 45) and make sure it matches the new version exactly.
   ```dart
   // Example: Change from '1.0.1+2' to '1.0.2+3'
   static const String appVersion = '1.0.2+3';
   ```

### Step 2: Build the New APK

Open your terminal, navigate into the `flutter_app` folder, and run the build command.

**Terminal Commands (Run in PowerShell/Command Prompt):**
```bash
# 1. Navigate into the Flutter folder
cd flutter_app

# 2. Run the build command
flutter build apk --release
```
*(This process may take a few minutes to complete).*

### Step 3: Copy the New APK to the Web Server

Once the build is complete, you need to replace the old APK file in your Laravel `public/downloads` directory with the newly built one.

**Terminal Command (Run from the root `Amiga-Travel` folder):**
```powershell
   
```

*(Alternatively, you can just manually copy/paste the file using Windows File Explorer from `flutter_app\build\app\outputs\flutter-apk\app-release.apk` and rename/replace it in `public\downloads\amiga-travel.apk`)*.

---

### What happens automatically next?

Once you complete Step 3 and push your changes to your live server (Vercel/Railway), the system handles the rest dynamically:

- 🪄 **The Website Updates:** The download page will automatically read the new version from `pubspec.yaml` and recalculate the new APK file size.
- 🪄 **Users are Forced to Update:** Anyone who opens an older version of the app will hit the `/api/app-version` check, see that a new version exists, and receive an un-closeable popup forcing them to download the new APK.
