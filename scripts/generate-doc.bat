setlocal

set TITLE=quasardb
set INPUT_DIR=%~dp0..\doc
set OUTPUT_DIR=%~dp0..\doc-out
set PHP=C:\Program Files (x86)\php-5.6.10-nts-Win32-VC11-x86\php.exe
set APIGEN=%~dp0..\third-party\apigen.phar
set TEMPLATE_CONFIG=%~dp0..\doc-theme\config.neon

rmdir /s /q "%OUTPUT_DIR%"
"%PHP%" -d"extension=php_mbstring.dll" "%APIGEN%" generate -s "%INPUT_DIR%" -d "%OUTPUT_DIR%" --title "%TITLE%" --template-config "%TEMPLATE_CONFIG%"