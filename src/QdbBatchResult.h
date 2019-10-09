// Copyright (c) 2009-2019, quasardb SAS
// All rights reserved.

#ifndef QDB_BATCH_RESULT_H
#define QDB_BATCH_RESULT_H

#include "php_include.h"
#include <qdb/batch.h>

void QdbBatchResult_registerClass();

void QdbBatchResult_createInstance(
    zval* destination, qdb_handle_t handle, qdb_operation_t* operations, size_t operations_count);

#endif /* QDB_BATCH_RESULT_H */
