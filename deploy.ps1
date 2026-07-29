
cd flutter_app
Write-Host 'Building APK...'
flutter build apk --release
Write-Host 'Building Web...'
flutter build web --release --web-renderer canvaskit
cd ..
Write-Host 'Copying files...'
Copy-Item -Path flutter_app\build\app\outputs\flutter-apk\app-release.apk -Destination public\downloads\amiga-gracia.apk -Force
Copy-Item -Path flutter_app\build\web\* -Destination public\app\ -Recurse -Force
Write-Host 'Deployment finished.'

