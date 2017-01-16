<?php
/**
 * ## Installation on Linux
 *
 * #### Prerequisites:
 *
 * The following packages must be be installed:
 *
 * 1. `php5-dev`
 * 2. `php-pear`
 * 3. `libpcre3-dev`
 *
 * #### Instructions:
 *
 * <code>
 * # Addresses of C and PHP APIs
 * QDB_C_URL='https://download.quasardb.net/quasardb/2.0/2.0.0-rc.9/api/c/qdb-2.0.0-linux-64bit-c-api.tar.gz'
 * QDB_PHP_URL='https://download.quasardb.net/quasardb/2.0/2.0.0-rc.9/api/php/quasardb-2.0.0.tgz'
 *
 * # Install quasardb C API
 * curl -sS $QDB_C_URL |
 *    sudo tar -xvz -C /usr/local/
 *
 * # Install PHP extension
 * sudo pecl install $QDB_PHP_URL
 *
 * # Add extension directive in PHP.ini
 * echo 'extension=quasardb.so' |
 *    sudo tee /etc/php5/cli/conf.d/quasardb.ini |
 *    sudo tee /etc/php5/apache2/conf.d/quasardb.ini
 *
 * # And restart apache
 * sudo apachectl graceful
 * </code>
 * ## Installation on Windows
 *
 * There is no quick installation on Windows. Please refer to {@see Compilation} instructions.
 *
 * @see Compilation
 */
function Installation();
?>
