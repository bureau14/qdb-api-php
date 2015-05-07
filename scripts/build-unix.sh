#!/bin/bash

set -eu

cd $(dirname $0)/..

export LD_LIBRARY_PATH="$(pwd)/qdb/lib"
export PATH="$(pwd)/qdb/bin:$PATH"

phpize
./configure CFLAGS="-Iqdb/include" LDFLAGS="-Lqdb/lib"
make build-modules

php "-dextension=modules/quasardb.so" "test/phpunit.phar" --bootstrap "test/bootstrap.php" "test"