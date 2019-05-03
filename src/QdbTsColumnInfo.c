// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "QdbTsColumnInfo.h"
#include "class_definition.h"

struct zval_column_info_t
{
    zend_object std;
    zval* name;
    zval* type;
};

#define class_name QdbTsColumnInfo
#define class_storage struct zval_column_info_t

CLASS_METHOD_2(__construct, STRING_ARG(name), LONG_ARG(type))
{
    Z_ADDREF_P(name);
    Z_ADDREF_P(type);

    this->name = name;
    this->type = type;
}

CLASS_METHOD_0(__destruct)
{
    Z_DELREF_P(this->name);
    Z_DELREF_P(this->type);
}

CLASS_METHOD_0(name)
{
    RETURN_ZVAL(this->name, 0, 0);
}

CLASS_METHOD_0(type)
{
    RETURN_ZVAL(this->type, 0, 0);
}

void QdbTsColumnInfo_make_native_array(HashTable* src, qdb_ts_column_info_t* dst TSRMLS_CC)
{
    zval** pcolumn;
    int i = 0;
    for (zend_hash_internal_pointer_reset(src);
         zend_hash_get_current_data(src, (void**)&pcolumn) == SUCCESS;
         zend_hash_move_forward(src))
    {
        CHECK_TYPE_OF_OBJECT_ARG(QdbTsColumnInfo, *pcolumn);
        class_storage* column = (class_storage*) zend_object_store_get_object(*pcolumn TSRMLS_CC);

        qdb_ts_column_info_t* col_copy = dst + i++;
        col_copy->name = Z_STRVAL_P(column->name);
        col_copy->type = Z_LVAL_P(column->type);
    }
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(name)
    ADD_METHOD(type)
END_CLASS_MEMBERS()

#include "class_definition.i"
