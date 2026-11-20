@echo off
cd /d "%~dp0"
echo Import de la base voyage_api dans MySQL...
"C:\xampp\mysql\bin\mysql.exe" -u root < "sql\voyage.sql"
if %errorlevel% equ 0 (
    echo OK - base importee.
) else (
    echo Erreur import.
)
pause