// Copyright (c) 2009-2022, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_QUERY_POINT_H
#define QDB_QUERY_POINT_H

#include "php_include.h"
#include <qdb/query.h>

void QdbQueryPoint_registerClass();

int init_query_point_types();

void QdbQueryPoint_createInstance(zval* destination, qdb_point_result_t* point);

#endif /* QDB_QUERY_POINT_H */
