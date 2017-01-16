<?php
/**
 * This page explains how to manually compile the extension. Most users don't need to follow this procedure and should use the simple  {@see Installation} instructions.
 *
 * ## Manual compilation from source on Linux
 *
 * #### Assumptions:
 *
 * 1. `php5`, `php5-dev`, `git`, `gcc` and `libpcre3-dev` are installed
 * 2. [qdb-capi](https://download.quasardb.net/quasardb/) is installed in `/usr/include` and `/usr/lib`
 *
 * This has been tested on Ubuntu 14.04 LTS, please adapt to your configuration.
 *
 * #### Instructions:
 * <code>
 * git clone https://github.com/bureau14/qdb-api-php.git
 * cd qdb-api-php
 * phpize
 * ./configure --with-quasardb
 * make
 * sudo make install
 * </code>
 *
 * #### Alternative:
 *
 * If you don't want to install `qdb_capi` in `/usr/include` and `/usr/lib`, you can specify the path like this:
 * <code>
 * ./configure --with-quasardb=/path/to/qdb_api
 * </code>
 *
 * However, this will stop working if you change the location of `qdb_capi` and you'll need to do the compilation again.
 * That is why the global installation in `/usr/include` and `/usr/lib` is recommended.
 *
 * ## Manual compilation from source on Windows
 *
 * #### Assumptions:
 *
 * 1. Visual Studio is installed
 * 2. [PHP source code](http://windows.php.net/download/) is decompressed in `C:\php-src\`
 * 3. `qdb-capi` is installed in `C:\qdb-capi`
 * 4. `qdb-php-api.tar.gz` has been decompressed in `C:\php-src\ext\qdb`
 *
 * Please adapt to your configuration.
 *
 * #### Instructions:
 *
 * Open a *Visual Studio Developer Command Prompt* (either x86 or x86) and type:
 * <code>
 * cd /d C:\php-src\
 * buildconf
 * configure --with-quasardb=C:\qdb-capi
 * nmake
 * nmake install
 * </code>
 *
 * You may want to customize `configure`'s flags, for instance `--enable-zts` or `--disable-zts` to control thread-safety.
 *
 * Also if `qdb_api.dll` is not available on the `PATH`, you'll need to copy it to `C:\php\`.
 */
function Compilation();
?>
