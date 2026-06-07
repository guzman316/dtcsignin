@echo off
cd /d "%~dp0"
"C:\xampp\php\php.exe" -S 0.0.0.0:8080 -t "%~dp0"
