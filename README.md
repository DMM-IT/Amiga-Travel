# Amiga Gracia Travel Services 🚢✈️

A complete booking platform consisting of a **Laravel** backend (admin panel & API) and a **Flutter** mobile application for end users to book ferries, flights, and tour packages.

---

## 🛠️ System Requirements

Before getting started, make sure you have the following installed on your machine:

**Backend (Laravel):**
- PHP >= 8.1
- Composer
- MySQL / MariaDB (e.g., via XAMPP, Laragon, or Docker)
- Node.js & npm (for compiling frontend assets)

**Mobile App (Flutter):**
- Flutter SDK (latest stable)
- Android Studio (with Android SDK installed)
- An Android Emulator or physical device for testing

---

## 🚀 Part 1: Backend Setup (Laravel)

Follow these steps to set up the backend API and admin dashboard:

### 1. Clone the repository
Clone the repository to your local machine (e.g., inside your Laragon `www` or XAMPP `htdocs` folder):
```bash
git clone <repository_url> amiga-travel
cd amiga-travel
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node Dependencies
```bash
npm install
npm run build
```

### 4. Environment Configuration
Copy the example environment file and configure it:
```bash
cp .env.example .env
```
Open the `.env` file and update your database credentials. For example:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amiga_travel
DB_USERNAME=root
DB_PASSWORD=
```
*Make sure you create the `amiga_travel` database in your MySQL server before proceeding.*

### 5. Generate App Key
```bash
php artisan key:generate
```

### 6. Run Migrations & Seed Database
This will create the database tables and populate them with initial data (like admin user, test ferries, and promotions):
```bash
php artisan migrate:fresh --seed
```
*Note: The default admin credentials will be created by the seeders (usually `admin@test.com` / `password`, check `DatabaseSeeder.php` for exact details).*

### 7. Link Storage
Link the storage directory so uploaded images (proof of payment, promos) are publicly accessible:
```bash
php artisan storage:link
```

### 8. Start the Local Server
If you are using Laragon, you can access the app via `http://amiga-travel.test`. Otherwise, run:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
*(Running with `--host=0.0.0.0` or your local IP is recommended so your Flutter app can reach the API from an external device).*

---

## 📱 Part 2: Mobile App Setup (Flutter)

The Flutter app is located inside the `flutter_app` directory.

### 1. Navigate to the Flutter folder
```bash
cd flutter_app
```

### 2. Install Flutter Dependencies
```bash
flutter pub get
```

### 3. Update the API Endpoint
The Flutter app needs to know where your Laravel API is running.
Open `flutter_app/lib/main.dart` and locate the `UserSession` class. Update the IP address to match your computer's local IPv4 address (e.g., `192.168.x.x`):
```dart
class UserSession {
  static String getBaseUrl() {
    if (kIsWeb) return '';
    // CHANGE THIS IP TO YOUR PC'S LOCAL IP ADDRESS!
    return 'http://192.168.1.8:8000'; 
  }
}
```

### 4. Run the App
Connect your Android device or start an emulator, then run:
```bash
flutter run
```

---

## 📦 Building for Production

### Android APK
To compile an APK for Android users to install:
```bash
cd flutter_app
flutter build apk --release
```
The APK will be generated at `flutter_app/build/app/outputs/flutter-apk/app-release.apk`.

### Web / iOS
The app can also be built for Web or iOS if you have a Mac and Xcode installed:
```bash
flutter build web
flutter build ios
```

---

## 🔧 Troubleshooting

- **SocketException (Connection Refused/Timed Out) on Flutter:** 
  Ensure your phone/emulator is on the same WiFi network as your PC. Check your firewall settings to make sure port 8000 is open. Ensure you changed the IP in `main.dart` to your actual local IP (run `ipconfig` on Windows or `ifconfig` on Mac/Linux to find it).
- **Images not loading in App:**
  Make sure you ran `php artisan storage:link` on the backend and that the API IP address in `main.dart` is correct. Localhost (`127.0.0.1`) does not work on physical mobile devices.
- **ADB Error on physical device:**
  If you get `INSTALL_FAILED_USER_RESTRICTED` on Xiaomi/Redmi devices when running `flutter run`, you must build the APK using `flutter build apk --debug`, copy it to your phone, and install it manually.
