// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_CONNECTION
#define QDB_CONNECTION

#include <php.h> // include first to avoid conflict with stdint.h
#include <qdb/client.h>

void connection_init(TSRMLS_D);
void connection_shutdown(TSRMLS_D);

qdb_handle_t connection_open(zval* uri TSRMLS_DC);
void connection_close(qdb_handle_t handle TSRMLS_DC);

#endif
