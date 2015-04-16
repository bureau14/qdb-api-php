set PHP_SRC=C:\Sources\php-5.5.20-src
set TEST_DIR=%~dp0..\test
set PATH=%PATH%;%QDB_DAEMON%\bin

call "%VCVARS%"

pushd %PHP_SRC% || exit /b 1
call buildconf.bat "--add-modules-dir=%~dp0..\.." || exit /b 2
call configure.bat --disable-all %PHP_FLAGS% --enable-cli --enable-phar "--with-qdb=%QDB_API%"  || exit /b 3

nmake || exit /b 4

cd %TEST_DIR%
"%PHP_BUILD_DIR%\php" "-dextension_dir=%PHP_BUILD_DIR%" "-dextension=php_qdb.dll" phpunit.phar --bootstrap bootstrap.php ../test || exit /b 5

popd