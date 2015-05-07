PHP_ARG_WITH(quasardb, for quasardb support,
[  --with-quasardb=DIR     include quasardb support])

PHP_QUASARDB_SOURCES="\
  php_qdb.c \
  src/class_definition.c \
  src/connection.c \
  src/exceptions.c \
  src/globals.c \
  src/log.c \
  src/QdbBatch.c \
  src/QdbBatchResult.c \
  src/QdbBlob.c \
  src/QdbCluster.c \
  src/QdbEntry.c \
  src/QdbExpirableEntry.c \
  src/QdbHashSet.c \
  src/QdbInteger.c \
  src/QdbQueue.c \
  src/settings.c \
"
PHP_QUASARDB_CFLAGS="-Wall -fno-strict-aliasing"

if test "$PHP_QUASARDB" != "no"; then

  if test -d "$PHP_QUASARDB"; then

    AC_CHECK_HEADER($PHP_QUASARDB/include/qdb/client.h,
      [PHP_ADD_INCLUDE($PHP_QUASARDB/include)],
      [AC_MSG_ERROR(qdb/client.h not found in $PHP_QUASARDB/include)])

    PHP_CHECK_LIBRARY(qdb_api, quasardb_version,
      [PHP_ADD_LIBRARY_WITH_PATH(qdb_api, $PHP_QUASARDB/lib, QUASARDB_SHARED_LIBADD)],
      [AC_MSG_ERROR([qdb_api not found in $PHP_QUASARDB/lib])],
      [-L$PHP_QUASARDB/lib])

  else

    AC_CHECK_HEADER(qdb/client.h, ,
      AC_MSG_ERROR(quasardb extension requires quasardb C API),
      [#include <qdb/client.h>])

    PHP_CHECK_LIBRARY(qdb_api, qdb_version,
      [PHP_ADD_LIBRARY(qdb_api, 1, QUASARDB_SHARED_LIBADD)],
      [PHP_CHECK_LIBRARY(qdb_apid, qdb_version,
        [PHP_ADD_LIBRARY(qdb_apid, 1, QUASARDB_SHARED_LIBADD)],
        [AC_MSG_ERROR(quasardb extension requires quasardb C API)])])

  fi

  PHP_NEW_EXTENSION(quasardb, $PHP_QUASARDB_SOURCES, $ext_shared, , $PHP_QUASARDB_CFLAGS)
  PHP_SUBST(QUASARDB_SHARED_LIBADD)
  PHP_ADD_BUILD_DIR($ext_builddir/src)
fi
