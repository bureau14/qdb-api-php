// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

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
    Z_ADDREF_P(this->name);
    *return_value = *this->name;
}

CLASS_METHOD_0(type)
{
    Z_ADDREF_P(this->type);
    *return_value = *this->type;
}

void QdbTsColumnInfo_make_native_array(HashTable* src, qdb_ts_column_info_t* dst)
{
    int i = 0;
    zval* value;
    for (zend_hash_internal_pointer_reset(src);
         (value = zend_hash_get_current_data(src));
         zend_hash_move_forward(src))
    {
        CHECK_TYPE_OF_OBJECT_ARG(QdbTsColumnInfo, value);
        class_storage* column = (class_storage*) Z_OBJ_P(value);

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
