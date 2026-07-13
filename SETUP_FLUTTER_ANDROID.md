# Flutter & Android Studio Setup Guide

This guide provides step-by-step instructions for setting up the environment required to run, build, and debug the Flutter application in this repository.

---

## 📋 Prerequisites
* **Operating System:** Windows 10 or 11 (64-bit)
* **Disk Space:** At least 10 GB of free space
* **Administrator Privileges** on your machine

---

## 🚀 Step 1: Install the Flutter SDK

1. **Download the Flutter SDK:**
   * Go to the [Flutter Windows install page](https://docs.flutter.dev/get-started/install/windows/mobile?tab=download).
   * Download the latest stable version of the Flutter SDK zip file.

2. **Extract the SDK:**
   * Create a folder like `C:\src` (avoid paths like `C:\Program Files\` which require elevated admin permissions).
   * Extract the downloaded zip file into `C:\src\flutter`.

3. **Update Path Environment Variable:**
   * Search for "env" in the Windows Search bar and select **Edit the system environment variables**.
   * Click **Environment Variables...**.
   * Under **User variables**, select the variable named **Path** and click **Edit...**.
   * Click **New** and add the absolute path to Flutter's bin folder: `C:\src\flutter\bin`.
   * Click **OK** to close all dialogs.

4. **Verify the Installation:**
   * Open a new PowerShell or Command Prompt terminal.
   * Run the command:
     ```powershell
     flutter --version
     ```
   * It should successfully output the installed Flutter and Dart versions.

---

## 📱 Step 2: Install and Configure Android Studio

1. **Download Android Studio:**
   * Download the latest version of [Android Studio](https://developer.android.com/studio).
   * Run the installer and follow the wizard. Choose a standard installation.

2. **Install Required Android SDK Components:**
   * Open Android Studio.
   * On the Welcome screen, click **More Actions** (three dots icon) and choose **SDK Manager**.
   * Under the **SDK Platforms** tab, ensure the latest stable Android version (e.g., Android 13/14) is checked.
   * Switch to the **SDK Tools** tab and ensure the following are checked:
     * **Android SDK Build-Tools**
     * **Android SDK Command-line Tools (latest)** *(Crucial for Flutter!)*
     * **Android Emulator**
     * **Android SDK Platform-Tools**
   * Click **Apply** and let Android Studio download and install the selected components.

3. **Set Android Environment Variables:**
   * Open **Environment Variables** again.
   * Under **User variables**, click **New...**:
     * **Variable name:** `ANDROID_HOME`
     * **Variable value:** `C:\Users\<Your-Username>\AppData\Local\Android\Sdk` *(Replace `<Your-Username>` with your Windows username)*
   * In the **Path** user variable, add:
     * `%ANDROID_HOME%\platform-tools`
     * `%ANDROID_HOME%\emulator`

4. **Accept Android Licenses:**
   * Open your terminal and run:
     ```powershell
     flutter doctor --android-licenses
     ```
   * Press `y` to accept each license agreement when prompted.

---

## 🛠️ Step 3: Run Flutter Doctor

To ensure everything is installed and configured correctly:
1. Run:
   ```powershell
   flutter doctor
   ```
2. Review the output. If there are any missing components (indicated by `[✗]` or `[!]`), follow the on-screen instructions to resolve them.

---

## 💻 Step 4: Configure Your IDE

### Option A: VS Code (Recommended)
1. Open VS Code.
2. Go to **Extensions** (`Ctrl+Shift+X`).
3. Search for and install the **Flutter** extension (this will automatically install the **Dart** extension).
4. Restart VS Code.

### Option B: Android Studio
1. Open Android Studio.
2. Go to **Plugins** on the left menu.
3. Search for and install the **Flutter** plugin (choose to install the required **Dart** plugin as well).
4. Restart Android Studio.

---

## 🏃 Step 5: Run the Project

Once the setup is complete, you can run the mobile application from this repository:

1. **Navigate to the Flutter project directory:**
   ```powershell
   cd c:\laragon\www\amiga-travel\flutter_app
   ```

2. **Get project dependencies:**
   ```powershell
   flutter pub get
   ```

3. **Start an Android Emulator:**
   * In Android Studio, go to **Virtual Device Manager** (Device Manager).
   * Create a virtual device (e.g., Pixel 7) and click the green play button to launch the emulator.

4. **Run the App:**
   * In your terminal (within `flutter_app` folder), run:
     ```powershell
     flutter run
     ```
   * Alternatively, press `F5` in VS Code while having a Dart file open.
