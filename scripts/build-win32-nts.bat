@echo off

setlocal

set VCVARS=C:\Program Files (x86)\Microsoft Visual Studio\2017\BuildTools\VC\Tools\MSVC\14.16.27023\bin\Hostx86\x86
set PHP_FLAGS=--disable-zts
set PHP_BUILD_DIR=%PHP_SRC%\Release
set QDB_API=%~dp0..\qdb\win32
set QDB_DAEMON=%~dp0..\qdb\win32

call "%~dp0/build-generic.bat"
