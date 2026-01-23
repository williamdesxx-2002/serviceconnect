@echo off
echo ========================================
echo   ServiceConnect - Serveur de Dev
echo ========================================
echo.

echo [1/4] Verification de PHP...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo   ❌ PHP n'est pas installe ou pas dans le PATH
    echo   Veuillez installer PHP et l'ajouter au PATH
    pause
    exit /b 1
)
echo   ✅ PHP trouve

echo.
echo [2/4] Verification de Composer...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo   ❌ Composer n'est pas installe ou pas dans le PATH
    echo   Veuillez installer Composer
    pause
    exit /b 1
)
echo   ✅ Composer trouve

echo.
echo [3/4] Verification du projet Laravel...
if not exist "artisan" (
    echo   ❌ Fichier artisan non trouve
    echo   Veuillez vous assurer d'etre dans le repertoire du projet Laravel
    pause
    exit /b 1
)
echo   ✅ Projet Laravel trouve

echo.
echo [4/4] Demarrage du serveur de developpement...
echo   Serveur: http://127.0.0.1:8000
echo   Pour arreter: Ctrl+C
echo   ========================================
echo.

php artisan serve --host=127.0.0.1 --port=8000

echo.
echo   Serveur arrete
pause
