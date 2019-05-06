// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_QUERY_POINT_H
#define QDB_QUERY_POINT_H

#include <zend.h>  // include first to avoid conflict with stdint.h
#include <qdb/query.h>

void QdbQueryPoint_registerClass(TSRMLS_D);

int init_query_point_types();

void QdbQueryPoint_createInstance(zval* destination, qdb_point_result_t* point TSRMLS_DC);

#endif /* QDB_QUERY_POINT_H */
