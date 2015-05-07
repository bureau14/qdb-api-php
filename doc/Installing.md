## Installation on Linux

#### Assumptions:

The following packages have be installed:
1. `php`, the main PHP package
2. `php-pear`, the package manager
3. `libpcre3-dev`, which is required to compile a PHP extension

The following archive has been downloaded:
1. `qdb-capi.tar.gz`, the quasardb C API

#### Instructions:

    # Install quasardb C API
    sudo tar -xvf qdb-c-api.tar.gz -C /usr/local/

    # Install PHP extension
    sudo pecl install https://download.quasardb.net/quasardb/2.0.0/api/php/quasardb-2.0.0.tgz

## Installation on Windows

Please see [Compilation Instructions](Compiling.md)