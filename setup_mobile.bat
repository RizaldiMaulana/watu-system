@echo off
echo ==========================================
echo   Watu System - Mobile App Setup Script
echo ==========================================
echo.
echo [1/3] Installing Capacitor Dependencies...
call npm install @capacitor/core @capacitor/cli @capacitor/android @capacitor/ios
if %errorlevel% neq 0 (
    echo [ERROR] NPM Install failed. Make sure Node.js is installed.
    pause
    exit /b %errorlevel%
)

echo.
echo [2/3] Initializing Capacitor Project...
echo (If prompted, keep defaults or enter: Name="Watu System", ID="com.watu.system")
call npx cap init "Watu System" com.watu.system --web-dir public

echo.
echo [3/3] Adding Android Platform...
call npx cap add android

echo.
echo ==========================================
echo   SETUP COMPLETE!
echo ==========================================
echo Now you can open the project in Android Studio:
echo Run: npx cap open android
echo.
pause
