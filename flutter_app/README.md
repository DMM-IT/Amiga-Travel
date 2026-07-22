# Amiga Gracia Flutter App

The mobile app calls the Laravel API. Railway database credentials belong only in
the Laravel service environment and must never be passed to Flutter or included in
an APK.

## Railway build

From this directory:

```powershell
flutter pub get
flutter build apk --release --dart-define=API_BASE_URL=https://amiga-travel.up.railway.app
```

The APK is generated at `build/app/outputs/flutter-apk/app-release.apk`.

For local development, override the API endpoint without editing source code:

```powershell
flutter run --dart-define=API_BASE_URL=http://10.0.2.2:8000
```

A new Flutter project.

## Getting Started

This project is a starting point for a Flutter application.

A few resources to get you started if this is your first Flutter project:

- [Lab: Write your first Flutter app](https://docs.flutter.dev/get-started/codelab)
- [Cookbook: Useful Flutter samples](https://docs.flutter.dev/cookbook)

For help getting started with Flutter development, view the
[online documentation](https://docs.flutter.dev/), which offers tutorials,
samples, guidance on mobile development, and a full API reference.
