// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "QdbQueryPoint.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

struct zval_query_point_t
{
    zend_object std;
    zval* type;
    zval* value;
};

#define class_name QdbQueryPoint
#define class_storage struct zval_query_point_t

extern zend_class_entry* ce_QdbQueryPoint;

int init_query_point_types() {
    return zend_declare_class_constant_long(ce_QdbQueryPoint, "BLOB", 4, qdb_query_result_blob TSRMLS_DC) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "DOUBLE", 6, qdb_query_result_double TSRMLS_DC) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "INT64", 5, qdb_query_result_int64 TSRMLS_DC) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "COUNT", 5, qdb_query_result_count TSRMLS_DC) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "TIMESTAMP", 9, qdb_query_result_timestamp TSRMLS_DC) == SUCCESS;
}

void QdbQueryPoint_createInstance(zval* destination, qdb_point_result_t* point TSRMLS_DC)
{
    if (point->type < qdb_query_result_double || point->type > qdb_query_result_count)
        php_printf("BROKEN!\n"), throw_invalid_argument("Got invalid query point");

    object_init_ex(destination, ce_QdbQueryPoint);
    class_storage* this = (class_storage*) zend_object_store_get_object(destination TSRMLS_CC);

    MAKE_STD_ZVAL(this->type);
    ZVAL_LONG(this->type, point->type);

    switch (point->type)
    {
    case qdb_query_result_blob:
        MAKE_STD_ZVAL(this->value);
        ZVAL_STRING(this->value, point->payload.blob.content, 1);
        return;
    case qdb_query_result_double:
        MAKE_STD_ZVAL(this->value);
        ZVAL_DOUBLE(this->value, point->payload.double_.value);
        return;
    case qdb_query_result_int64:
        MAKE_STD_ZVAL(this->value);
        ZVAL_LONG(this->value, point->payload.int64_.value);
        return;
    case qdb_query_result_count:
        MAKE_STD_ZVAL(this->value);
        ZVAL_LONG(this->value, point->payload.count.value);
        return;
    case qdb_query_result_timestamp:
        this->value = QdbTimestamp_from_timespec(&point->payload.timestamp.value TSRMLS_CC);
        return;
    }
}

CLASS_METHOD_0(type)
{
    Z_ADDREF_P(this->type);
    *return_value = *this->type;
}

CLASS_METHOD_0(value)
{
    Z_ADDREF_P(this->value);
    *return_value = *this->value;
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(type)
    ADD_METHOD(value)
END_CLASS_MEMBERS()

#include "class_definition.i"
