// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_QUERY_TABLE_H
#define QDB_QUERY_TABLE_H

#include <zend.h>  // include first to avoid conflict with stdint.h
#include <qdb/query.h>

void QdbQueryTable_registerClass(TSRMLS_D);

void QdbQueryTable_createInstance(zval* destination, qdb_table_result_t* result TSRMLS_DC);

#endif /* QDB_QUERY_TABLE_H */
