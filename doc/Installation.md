## Installation on Linux

#### Assumptions:

1. `php`, `php-devel`, `git` and `gcc` are installed
2. `qdb-capi` is installed in `/usr/include` and `/usr/lib`
3. `qdb-php-api.tar.gz` has been downloaded

Please adapt to your configuration.

#### Instructions:

    git clone https://github.com/bureau14/qdb-api-php.git
    cd qdb-php-api
    phpize
    ./configure --with-qdb
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

##### `qdb.persitent`

Enables persistent connections.

Can be set to `0` to disable or to `1` to enable persistent connections.
The default is `1` (enabled).
