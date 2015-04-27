// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_BATCH_RESULT_H
#define QDB_BATCH_RESULT_H

#include <zend.h> // include first to avoid conflict with stdint.h

#include <qdb/client.h>

void QdbBatchResult_registerClass(TSRMLS_D);

void QdbBatchResult_createInstance(zval* destination, qdb_handle_t handle, qdb_operation_t * operations, size_t operations_count TSRMLS_DC);

#endif /* QDB_BATCH_RESULT_H */
