// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_TS_QUERY_H
#define QDB_TS_QUERY_H

#include <zend.h>  // include first to avoid conflict with stdint.h
#include <qdb/query.h>

void QdbTsQuery_registerClass(TSRMLS_D);

void QdbTsQuery_createInstance(zval* destination, qdb_handle_t handle, const char* query TSRMLS_DC);

#endif /* QDB_TS_QUERY_H */
