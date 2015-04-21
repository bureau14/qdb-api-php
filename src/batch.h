// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_BATCH_H
#define QDB_BATCH_H

#include <zend.h>  // include first to avoid conflict with stdint.h

#include <qdb/client.h>

typedef struct {
    qdb_operation_type_t type;
    zval* alias;
    zval* content;
    zval* comparand;
    qdb_time_t expiry_time;
} batch_operation_t;

typedef struct {
    zend_object std;
    batch_operation_t * operations;
    size_t length;
    size_t capacity;
} batch_t;

void QdbBatch_registerClass(TSRMLS_D);

void QdbBatch_copyOperations(zval* zbatch, qdb_operation_t** operations, size_t* operation_count TSRMLS_DC);

extern zend_class_entry* ce_QdbBatch;

#endif /* QDB_BATCH_H */
