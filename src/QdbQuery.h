// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_QUERY_H
#define QDB_QUERY_H

#include <zend.h>  // include first to avoid conflict with stdint.h
#include <qdb/query.h>

void QdbQuery_registerClass(TSRMLS_D);

void QdbQuery_createInstance(zval* destination, qdb_handle_t handle, const char* query);

#endif /* QDB_QUERY_H */
