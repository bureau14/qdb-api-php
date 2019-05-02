// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "QdbTsBatchTable.h"
#include "class_definition.h"
#include "exceptions.h"

typedef struct
{
    zend_object std;
    qdb_batch_table_t table;
    qdb_handle_t handle;
} ts_batch_table_t;

#define class_name QdbTsBatchTable
#define class_storage ts_batch_table_t

void QdbTsBatchTable_createInstance(zval* destination,
                                    qdb_handle_t handle,
                                    const qdb_ts_batch_column_info_t* columns,
                                    qdb_size_t column_count TSRMLS_DC)
{
    qdb_batch_table_t table;
    qdb_error_t err = qdb_ts_batch_table_init(handle, columns, &table);
    if (QDB_FAILURE(err)) throw_qdb_error(err);

    object_init_ex(destination, ce_QdbTsBatchTable);
    class_storage* this = (class_storage*) zend_object_store_get_object(destination TSRMLS_CC);
    this->handle = handle;
    this->table  = table;
}

CLASS_METHOD_0(__destruct)
{
    qdb_release(this->handle, this->table);
}

qdb_error_t
qdb_ts_batch_table_extra_columns(qdb_batch_table_t table,
                                    const qdb_ts_batch_column_info_t * columns,
                                    qdb_size_t column_count);

qdb_error_t
qdb_ts_batch_start_row(qdb_batch_table_t table,
                        const qdb_timespec_t * timestamp);

                        
qdb_error_t
qdb_ts_batch_row_set_blob(qdb_batch_table_t table,
                            qdb_size_t index,
                            const void * content,
                            qdb_size_t content_length);
                            
qdb_error_t
qdb_ts_batch_row_set_double(qdb_batch_table_t table,
                            qdb_size_t index,
                            double value);

qdb_error_t qdb_ts_batch_row_set_int64(qdb_batch_table_t table,
                                        qdb_size_t index,
                                        qdb_int_t value);

qdb_error_t qdb_ts_batch_row_set_timestamp(qdb_batch_table_t table,
                                            qdb_size_t index,
                                            const qdb_timespec_t * value);

CLASS_METHOD_0(push_values)
{
    qdb_error_t err = qdb_ts_batch_push(this->table);
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_0(push_values_async)
{
    qdb_error_t err = qdb_ts_batch_push_async(this->table);
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(push_values)
    ADD_METHOD(push_values_async)
END_CLASS_MEMBERS()

#include "class_definition.i"
