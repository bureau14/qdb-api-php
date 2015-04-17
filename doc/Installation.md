## Installation on Linux

#### Assumptions:

1. `php` and `php-devel` are installed
2. `qdb-capi` is installed in `/path/to/qdb_capi`
3. `qdb-php-api.tar.gz` has been downloaded

Please adapt to your configuration.

#### Instructions:

    tar xvf qdb-php-api.tar.gz
    cd qdb-php-api
    phpize
    ./configure --with-qdb=/path/to/qdb_capi
    make
    make install    

## Installation on Windows

#### Assumptions:

1. Visual Studio is installed
2. [PHP source code](http://windows.php.net/download/) is decompressed in `C:\php-src\`
3. `qdb-capi` is installed in `C:\qdb-capi`
4. `qdb-php-api.tar.gz` has been decompressed in `C:\php-src\ext\qdb`

Please adapt to your configuration.

#### Instructions:

Open a *Visual Studio Developer Command Prompt* (either x86 or x86) and type:

    cd /d C:\php-src\
    buildconf
    configure --with-qdb=C:\qdb-capi
    nmake
    nmake install

You may want to customize `configure`'s flags, for instance `--enable-zts` or `--disable-zts` to control thread-safety.

Also if `qdb_api.dll` is not available on the `PATH`, you'll need to copy it to `C:\php\`. 

## Runtime configuration

The following settings can be changed in `php.ini`.

##### `qdb.log_level`

Specifies the log verbosity.

Allowed values are `detailed`, `debug`, `info`, `warning`, `error`, `panic`. The default is `panic`.