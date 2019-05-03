// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

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
    RETURN_ZVAL(this->timeseries, 0, 0);
}

CLASS_METHOD_0(column)
{
    RETURN_ZVAL(this->column, 0, 0);
}

void QdbTsBatchColumnInfo_make_native_array(HashTable* src, qdb_ts_batch_column_info_t* dst TSRMLS_CC)
{
    zval** pcolumn;
    int i = 0;
    for (zend_hash_internal_pointer_reset(src);
         zend_hash_get_current_data(src, (void**)&pcolumn) == SUCCESS;
         zend_hash_move_forward(src))
    {
        CHECK_TYPE_OF_OBJECT_ARG(QdbTsBatchColumnInfo, *pcolumn);
        class_storage* column = (class_storage*) zend_object_store_get_object(*pcolumn TSRMLS_CC);

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
