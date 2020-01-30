// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#include "QdbQueryPoint.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"
#include <qdb/ts.h>

typedef struct
{
    zval type;
    zval value;
} _zval_query_point_t;

#define class_name QdbQueryPoint
#define class_storage _zval_query_point_t

extern zend_class_entry* ce_QdbQueryPoint;

int init_query_point_types() {
    return zend_declare_class_constant_long(ce_QdbQueryPoint, "NONE", sizeof("NONE")-1, qdb_query_result_none) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "BLOB", sizeof("BLOB")-1, qdb_query_result_blob) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "STRING", sizeof("STRING")-1, qdb_query_result_string) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "COUNT", sizeof("COUNT")-1, qdb_query_result_count) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "DOUBLE", sizeof("DOUBLE")-1, qdb_query_result_double) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "INT64", sizeof("INT64")-1, qdb_query_result_int64) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbQueryPoint, "TIMESTAMP", sizeof("TIMESTAMP")-1, qdb_query_result_timestamp) == SUCCESS;
}

void QdbQueryPoint_createInstance(zval* destination, qdb_point_result_t* point)
{
    object_init_ex(destination, ce_QdbQueryPoint);
    class_storage* this = get_class_storage(destination);

    ZVAL_LONG(&this->type, point->type);
    switch (point->type)
    {
    case qdb_query_result_none: break;
    case qdb_query_result_blob:
        if (QDB_IS_NULL_BLOB(point->payload.blob)) break;
        ZVAL_STRINGL(&this->value, point->payload.blob.content, point->payload.blob.content_length);
        return;
    case qdb_query_result_string:
        if (QDB_IS_NULL_STRING(point->payload.string)) break;
        ZVAL_STRINGL(&this->value, point->payload.string.content, point->payload.string.content_length);
        return;
    case qdb_query_result_double:
        if (QDB_IS_NULL_DOUBLE(point->payload.double_)) break;
        ZVAL_DOUBLE(&this->value, point->payload.double_.value);
        return;
    case qdb_query_result_int64:
        if (QDB_IS_NULL_INT64(point->payload.int64_)) break;
        ZVAL_LONG(&this->value, point->payload.int64_.value);
        return;
    case qdb_query_result_count:
        if (point->payload.int64_.value == qdb_count_undefined) break;
        ZVAL_LONG(&this->value, point->payload.count.value);
        return;
    case qdb_query_result_timestamp: {
        if (QDB_IS_NULL_TIMESTAMP(point->payload.timestamp)) break;
        this->value = QdbTimestamp_from_timespec(&point->payload.timestamp.value);
        return;
    }
    default:
        throw_out_of_bounds("Wrong point type received from QuasarDB");
    }

    ZVAL_NULL(&this->value);
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
