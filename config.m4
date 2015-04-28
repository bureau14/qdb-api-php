PHP_ARG_WITH(qdb, for quasardb support,
[  --with-qdb=DIR          include quasardb support])

PHP_QDB_SOURCES="\
  php_qdb.c \
  src/class_definition.c \
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
PHP_QDB_CFLAGS="-Wall -Werror -fno-strict-aliasing"

if test "$PHP_QDB" != "no"; then

  if test -d "$PHP_QDB"; then

    AC_CHECK_HEADER($PHP_QDB/include/qdb/client.h,
      [PHP_ADD_INCLUDE($PHP_QDB/include)],
      [AC_MSG_ERROR(qdb/client.h not found in $PHP_QDB/include)])

    PHP_CHECK_LIBRARY(qdb_api, qdb_version,
      [PHP_ADD_LIBRARY_WITH_PATH(qdb_api, $PHP_QDB/lib, QDB_SHARED_LIBADD)],
      [AC_MSG_ERROR([qdb_api not found in $PHP_QDB/lib])],
      [-L$PHP_QDB/lib])

  else

    AC_CHECK_HEADER(qdb/client.h, ,
      AC_MSG_ERROR(qdb extension requires qdb_api),
      [#include <qdb/client.h>])

    PHP_CHECK_LIBRARY(qdb_api, qdb_version,
      [PHP_ADD_LIBRARY(qdb_api, 1, QDB_SHARED_LIBADD)],
      [PHP_CHECK_LIBRARY(qdb_apid, qdb_version,
        [PHP_ADD_LIBRARY(qdb_apid, 1, QDB_SHARED_LIBADD)],
        [AC_MSG_ERROR(qdb extension requires qdb_api)])])

  fi

  PHP_NEW_EXTENSION(qdb, $PHP_QDB_SOURCES, $ext_shared, , $PHP_QDB_CFLAGS)
  PHP_SUBST(QDB_SHARED_LIBADD)
  PHP_ADD_BUILD_DIR($ext_builddir/src)
fi
