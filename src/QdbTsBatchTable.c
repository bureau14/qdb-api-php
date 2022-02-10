// Copyright (c) 2009-2021, quasardb SAS. All rights reserved.
// All rights reserved.

#include "QdbTsBatchTable.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

typedef struct
{
    qdb_batch_table_t table;
    qdb_handle_t handle;
} _ts_batch_table_t;

#define class_name QdbTsBatchTable
#define class_storage _ts_batch_table_t

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
    class_storage* this = get_class_storage(destination);
    this->handle = handle;
    this->table  = table;
}

CLASS_METHOD_0(__destruct)
{
    qdb_release(this->handle, this->table);
}

CLASS_METHOD_1(startRow, OBJECT_ARG(QdbTimestamp, timestamp))
{
    qdb_timespec_t ts = QdbTimestamp_make_timespec(timestamp);

    qdb_error_t err = qdb_ts_batch_start_row(this->table, &ts);
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_2(setBlob, LONG_ARG(index), STRING_ARG(blob))
{
    qdb_error_t err = qdb_ts_batch_row_set_blob(this->table, Z_LVAL_P(index), Z_STRVAL_P(blob), Z_STRLEN_P(blob));
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_2(setString, LONG_ARG(index), STRING_ARG(string))
{
    qdb_error_t err = qdb_ts_batch_row_set_string(this->table, Z_LVAL_P(index), Z_STRVAL_P(string), Z_STRLEN_P(string));
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_2(setDouble, LONG_ARG(index), DOUBLE_ARG(num))
{
    qdb_error_t err = qdb_ts_batch_row_set_double(this->table, Z_LVAL_P(index), Z_DVAL_P(num));
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_2(setInt64, LONG_ARG(index), LONG_ARG(num))
{
    qdb_error_t err = qdb_ts_batch_row_set_int64(this->table, Z_LVAL_P(index), Z_LVAL_P(num));
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_2(setTimestamp, LONG_ARG(index), OBJECT_ARG(QdbTimestamp, timestamp))
{
    qdb_timespec_t ts = QdbTimestamp_make_timespec(timestamp);

    qdb_error_t err = qdb_ts_batch_row_set_timestamp(this->table, Z_LVAL_P(index), &ts);
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_0(pushValues)
{
    qdb_error_t err = qdb_ts_batch_push(this->table);
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

CLASS_METHOD_0(pushValuesAsync)
{
    qdb_error_t err = qdb_ts_batch_push_async(this->table);
    if (QDB_FAILURE(err)) throw_qdb_error(err);
}

BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(startRow)
    ADD_METHOD(setBlob)
    ADD_METHOD(setString)
    ADD_METHOD(setDouble)
    ADD_METHOD(setInt64)
    ADD_METHOD(setTimestamp)
    ADD_METHOD(pushValues)
    ADD_METHOD(pushValuesAsync)
END_CLASS_MEMBERS()

#include "class_definition.i"
