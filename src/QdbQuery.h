// Copyright (c) 2009-2022, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_QUERY_H
#define QDB_QUERY_H

#include "php_include.h"
#include <qdb/query.h>

void QdbQuery_registerClass();

void QdbQuery_createInstance(zval* destination, qdb_handle_t handle, const char* query);

#endif /* QDB_QUERY_H */
