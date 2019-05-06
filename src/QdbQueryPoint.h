// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_TS_QUERY_POINT_H
#define QDB_TS_QUERY_POINT_H

#include <zend.h>  // include first to avoid conflict with stdint.h
#include <qdb/query.h>

void QdbTsQueryPoint_registerClass(TSRMLS_D);

int init_query_point_types();

void QdbTsQueryPoint_createInstance(zval* destination, qdb_point_result_t* point TSRMLS_DC);

#endif /* QDB_TS_QUERY_POINT_H */
