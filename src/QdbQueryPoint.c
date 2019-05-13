// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbQueryPoint.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

typedef struct
{
    zval type;
    zval value;
} _zval_query_point_t;

#define class_name QdbQueryPoint
#define class_storage _zval_query_point_t

extern zend_class_entry* ce_QdbQueryPoint;

int init_query_point_types() {
    return zend_declare_class_constant_long(ce_QdbQueryPoint, "BLOB", sizeof("BLOB")-1, qdb_query_result_blob) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "COUNT", sizeof("COUNT")-1, qdb_query_result_count) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "DOUBLE", sizeof("DOUBLE")-1, qdb_query_result_double) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "INT64", sizeof("INT64")-1, qdb_query_result_int64) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "TIMESTAMP", sizeof("TIMESTAMP")-1, qdb_query_result_timestamp) == SUCCESS;
}

void QdbQueryPoint_createInstance(zval* destination, qdb_point_result_t* point)
{
    if (point->type < qdb_query_result_double || point->type > qdb_query_result_count)
        throw_invalid_argument("Got invalid query point");

    object_init_ex(destination, ce_QdbQueryPoint);
    class_storage* this = get_class_storage(destination);

    ZVAL_LONG(&this->type, point->type);

    switch (point->type)
    {
    case qdb_query_result_blob:
        ZVAL_STRINGL(&this->value, point->payload.blob.content, point->payload.blob.content_length);
        return;
    case qdb_query_result_double:
        ZVAL_DOUBLE(&this->value, point->payload.double_.value);
        return;
    case qdb_query_result_int64:
        ZVAL_LONG(&this->value, point->payload.int64_.value);
        return;
    case qdb_query_result_count:
        ZVAL_LONG(&this->value, point->payload.count.value);
        return;
    case qdb_query_result_timestamp:
        this->value = QdbTimestamp_from_timespec(&point->payload.timestamp.value);
        return;
    default:
        throw_out_of_bounds("Wrong point type received from QuasarDB");
    }
}

CLASS_METHOD_0(type)
{
    ZVAL_COPY_VALUE(return_value, &this->type);
}

CLASS_METHOD_0(value)
{
    ZVAL_COPY(return_value, &this->value);
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(type)
    ADD_METHOD(value)
END_CLASS_MEMBERS()

#include "class_definition.i"
