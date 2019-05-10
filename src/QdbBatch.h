// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_BATCH_H
#define QDB_BATCH_H

#include "php_include.h"
#include <qdb/batch.h>

typedef struct
{
    qdb_operation_type_t type;
    zval* alias;
    zval* content;
    zval* comparand;
    qdb_time_t expiry_time;
} batch_operation_t;

typedef struct
{
    batch_operation_t* operations;
    size_t length;
    size_t capacity;
} batch_t;

void QdbBatch_registerClass();

void QdbBatch_copyOperations(zval* zbatch, qdb_operation_t** operations, size_t* operation_count);

extern zend_class_entry* ce_QdbBatch;

#endif /* QDB_BATCH_H */
