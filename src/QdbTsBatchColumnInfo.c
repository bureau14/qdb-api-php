// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbTsBatchColumnInfo.h"
#include "class_definition.h"

struct batch_column_info_t
{
    zend_object std;
    zval* timeseries;
    zval* column;
};

#define class_name QdbTsBatchColumnInfo
#define class_storage struct batch_column_info_t

CLASS_METHOD_2(__construct, STRING_ARG(timeseries), STRING_ARG(column))
{
    Z_ADDREF_P(timeseries);
    Z_ADDREF_P(column);

    this->timeseries = timeseries;
    this->column     = column;
}

CLASS_METHOD_0(__destruct)
{
    Z_DELREF_P(this->timeseries);
    Z_DELREF_P(this->column);
}

CLASS_METHOD_0(timeseries)
{
    Z_ADDREF_P(this->timeseries);
    *return_value = *this->timeseries;
}

CLASS_METHOD_0(column)
{
    Z_ADDREF_P(this->column);
    *return_value = *this->column;
}

void QdbTsBatchColumnInfo_make_native_array(HashTable* src, qdb_ts_batch_column_info_t* dst)
{
    int i = 0;
    zval* value;
    for (zend_hash_internal_pointer_reset(src);
         (value = zend_hash_get_current_data(src));
         zend_hash_move_forward(src))
    {
        CHECK_TYPE_OF_OBJECT_ARG(QdbTsBatchColumnInfo, value);
        class_storage* column = (class_storage*) Z_OBJ_P(value);

        qdb_ts_batch_column_info_t* col_copy = dst + i++;
        col_copy->timeseries = Z_STRVAL_P(column->timeseries);
        col_copy->column     = Z_STRVAL_P(column->column);
    }
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(timeseries)
    ADD_METHOD(column)
END_CLASS_MEMBERS()

#include "class_definition.i"
