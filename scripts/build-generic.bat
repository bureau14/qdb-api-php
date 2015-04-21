set PHP_SRC=C:\Sources\php-5.5.20-src
set TEST_DIR=%~dp0..\test
set PATH=%PATH%;%QDB_DAEMON%\bin

call "%VCVARS%"

pushd %PHP_SRC% || exit /b 1
call buildconf.bat "--add-modules-dir=%~dp0..\.." || exit /b 2
call configure.bat --disable-all %PHP_FLAGS% --enable-cli --enable-phar "--with-qdb=%QDB_API%"  || exit /b 3
nmake || exit /b 4
popd

cd

"%PHP_BUILD_DIR%\php" "-dextension_dir=%PHP_BUILD_DIR%" "-dextension=php_qdb.dll" "%TEST_DIR%\phpunit.phar" --bootstrap "%TEST_DIR%\bootstrap.php" "%TEST_DIR%" || exit /b 5

"%PHP_BUILD_DIR%\php" "-dextension_dir=%PHP_BUILD_DIR%" "-dextension=php_qdb.dll" --re qdb > "%PHP_BUILD_DIR%\qdb_extension_info.txt"
