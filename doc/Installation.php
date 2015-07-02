<?php
/**
 * ## Installation on Linux
 *
 * #### Prerequisites:
 *
 * The following packages must be be installed:
 *
 * 1. `php`, the main PHP package
 * 2. `php-pear`, the package manager
 * 3. `libpcre3-dev`, which is required to compile a PHP extension
 *
 * The following archive has been downloaded:
 * 1. `qdb-capi.tar.gz`, the quasardb C API
 *
 * #### Instructions:
 *
 * <code>
 * # Install quasardb C API
 * sudo tar -xvf qdb-c-api.tar.gz -C /usr/local/
 *
 * # Install PHP extension
 * sudo pecl install https://download.quasardb.net/quasardb/2.0.0/api/php/quasardb-2.0.0.tgz
 * </code>
 * ## Installation on Windows
 *
 * There is no quick installation on Windows. Please refer to compilation instructions.
 *
 * @see Compilation
 */
function Installation();
?>

