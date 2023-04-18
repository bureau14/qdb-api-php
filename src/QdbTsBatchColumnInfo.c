// Copyright (c) 2009-2023, quasardb SAS. All rights reserved.
// All rights reserved.

#include "QdbTsBatchColumnInfo.h"
#include "class_definition.h"

typedef struct
{
    zval timeseries;
    zval column;
} _batch_column_info_t;

#define class_name QdbTsBatchColumnInfo
#define class_storage _batch_column_info_t

CLASS_METHOD_2(__construct, STRING_ARG(timeseries), STRING_ARG(column))
{
    ZVAL_COPY(&this->timeseries, timeseries);
    ZVAL_COPY(&this->column,     column);
}

CLASS_METHOD_0(timeseries)
{
    ZVAL_COPY(return_value, &this->timeseries);
}

CLASS_METHOD_0(column)
{
    ZVAL_COPY(return_value, &this->column);
}

void QdbTsBatchColumnInfo_make_native_array(HashTable* src, qdb_ts_batch_column_info_t* dst)
{
    int i = 0;
    zval* value;
    ZEND_HASH_FOREACH_VAL(src, value)
    {
        CHECK_TYPE_OF_OBJECT_ARG(QdbTsBatchColumnInfo, value);
        class_storage* column = get_class_storage(value);

        qdb_ts_batch_column_info_t* col_copy = dst + i++;
        col_copy->timeseries = Z_STRVAL(column->timeseries);
        col_copy->column     = Z_STRVAL(column->column);
    } ZEND_HASH_FOREACH_END();
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_METHOD(timeseries)
    ADD_METHOD(column)
END_CLASS_MEMBERS()

#include "class_definition.i"
