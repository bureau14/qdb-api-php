// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbTsBatchTable.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

struct ts_batch_table_t
{
    zend_object std;
    qdb_batch_table_t table;
    qdb_handle_t handle;
};

#define class_name QdbTsBatchTable
#define class_storage struct ts_batch_table_t

extern zend_class_entry* ce_QdbTsBatchTable;

void QdbTsBatchTable_createInstance(zval* destination,
                                    qdb_handle_t handle,
                                    const qdb_ts_batch_column_info_t* columns,
                                    qdb_size_t column_cnt)
{
    qdb_batch_table_t table;
    qdb_error_t err = qdb_ts_batch_table_init(handle, columns, column_cnt, &table);
    if (QDB_FAILURE(err)) throw_qdb_error(err);

    object_init_ex(destination, ce_QdbTsBatchTable);
    class_storage* this = (class_storage*) Z_OBJ_P(destination);
    this->handle = handle;
    this->table  = table;
}

CLASS_METHOD_0(__destruct)
{
    qdb_release(this->handle, this->table);
}

CLASS_METHOD_1(start_row, OBJECT_ARG(QdbTimestamp, timestamp))
{
    qdb_timespec_t ts = QdbTimestamp_make_timespec(timestamp);

    qdb_error_t err = qdb_ts_batch_start_row(this->table, &ts);
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_2(set_blob, LONG_ARG(index), STRING_ARG(blob))
{
    qdb_error_t err = qdb_ts_batch_row_set_blob(this->table, Z_LVAL_P(index), Z_STRVAL_P(blob), Z_STRLEN_P(blob));
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_2(set_double, LONG_ARG(index), DOUBLE_ARG(num))
{
    qdb_error_t err = qdb_ts_batch_row_set_double(this->table, Z_LVAL_P(index), Z_DVAL_P(num));
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_2(set_int64, LONG_ARG(index), LONG_ARG(num))
{
    qdb_error_t err = qdb_ts_batch_row_set_int64(this->table, Z_LVAL_P(index), Z_LVAL_P(num));
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_2(set_timestamp, LONG_ARG(index), OBJECT_ARG(QdbTimestamp, timestamp))
{
    qdb_timespec_t ts = QdbTimestamp_make_timespec(timestamp);

    qdb_error_t err = qdb_ts_batch_row_set_timestamp(this->table, Z_LVAL_P(index), &ts);
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

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
    ADD_METHOD(start_row)
    ADD_METHOD(set_blob)
    ADD_METHOD(set_double)
    ADD_METHOD(set_int64)
    ADD_METHOD(set_timestamp)
    ADD_METHOD(push_values)
    ADD_METHOD(push_values_async)
END_CLASS_MEMBERS()

#include "class_definition.i"
