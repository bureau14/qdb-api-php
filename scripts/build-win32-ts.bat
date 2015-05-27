@echo off

setlocal

set VCVARS=C:\Program Files (x86)\Microsoft Visual Studio 12.0\VC\bin\vcvars32.bat
set PHP_FLAGS=--enable-zts
set PHP_BUILD_DIR=%PHP_SRC%\Release_TS
set QDB_API=%~dp0..\qdb\win32
set QDB_DAEMON=%~dp0..\qdb\win32

call "%~dp0/build-generic.bat"