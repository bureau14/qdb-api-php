@echo off

setlocal

set VCVARS=C:\Program Files (x86)\Microsoft Visual Studio 12.0\VC\bin\amd64\vcvars64.bat
set PHP_FLAGS=--enable-zts
set PHP_BUILD_DIR=%PHP_SRC%\x64\Release_TS
set QDB_API=%~dp0..\qdb\win64
set QDB_DAEMON=%~dp0..\qdb\win64

call "%~dp0/build-generic.bat"