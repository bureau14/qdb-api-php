// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_TS_BATCH_TABLE_H
#define QDB_TS_BATCH_TABLE_H

#include <zend.h>  // include first to avoid conflict with stdint.h
#include <qdb/ts.h>

/*
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
    zend_object std;
    batch_operation_t* operations;
    size_t length;
    size_t capacity;
} batch_t;*/

void QdbTsBatchTable_registerClass(TSRMLS_D);

void QdbTsBatchTable_createInstance(zval* destination,
                                    qdb_handle_t handle,
                                    const qdb_ts_batch_column_info_t* columns,
                                    qdb_size_t column_count TSRMLS_DC);

extern zend_class_entry* ce_QdbTsBatchTable;

#endif /* QDB_TS_BATCH_TABLE_H */
