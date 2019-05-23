// Copyright (c) 2009-2019, quasardb SAS
// All rights reserved.

#ifndef QDB_QUERY_TABLE_H
#define QDB_QUERY_TABLE_H

#include "php_include.h"
#include <qdb/query.h>

void QdbQueryTable_registerClass();

void QdbQueryTable_createInstance(zval* destination, qdb_table_result_t* result);

#endif /* QDB_QUERY_TABLE_H */
