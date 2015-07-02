setlocal

set TEST_DIR=%~dp0..\test
set PATH=%PATH%;%QDB_DAEMON%\bin;%QDB_API%\bin
set PHPUNIT=%~dp0..\third-party\phpunit.phar

call "%VCVARS%"

pushd %PHP_SRC% || exit /b 1
call buildconf.bat "--add-modules-dir=%~dp0..\.." || exit /b 2
call configure.bat --disable-all %PHP_FLAGS% --enable-cli --enable-phar "--with-quasardb=%QDB_API%"  || exit /b 3
nmake || exit /b 4
popd

"%PHP_BUILD_DIR%\php" "-dextension_dir=%PHP_BUILD_DIR%" "-dextension=php_quasardb.dll" "%PHPUNIT%" --bootstrap "%TEST_DIR%\bootstrap.php" "%TEST_DIR%" || exit /b 5

"%PHP_BUILD_DIR%\php" "-dextension_dir=%PHP_BUILD_DIR%" "-dextension=php_quasardb.dll" --re quasardb > "%PHP_BUILD_DIR%\quasardb_extension_info.txt"
