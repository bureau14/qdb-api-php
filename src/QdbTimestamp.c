// Copyright (c) 2009-2021, quasardb SAS. All rights reserved.
// All rights reserved.

#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

typedef struct {
    zval seconds;
    zval nanoseconds;
} _zval_timestamp_t;

#define class_name QdbTimestamp
#define class_storage _zval_timestamp_t

extern zend_class_entry* ce_QdbTimestamp;

CLASS_METHOD_2(__construct, LONG_ARG(seconds), LONG_ARG(nanoseconds))
{
    ZVAL_COPY_VALUE(&this->seconds,     seconds);
    ZVAL_COPY_VALUE(&this->nanoseconds, nanoseconds);
}

CLASS_METHOD_0(seconds)
{
    ZVAL_COPY_VALUE(return_value, &this->seconds);
}

CLASS_METHOD_0(nanoseconds)
{
    ZVAL_COPY_VALUE(return_value, &this->nanoseconds);
}

qdb_timespec_t QdbTimestamp_make_timespec(const zval* timestamp)
{
    CHECK_TYPE_OF_OBJECT_ARG(QdbTimestamp, timestamp);
    class_storage* this = get_class_storage(timestamp);

    qdb_timespec_t ts;
    ts.tv_sec  = Z_LVAL_P(&this->seconds);
    ts.tv_nsec = Z_LVAL_P(&this->nanoseconds);
    return ts;
}

zval QdbTimestamp_from_timespec(const qdb_timespec_t* ts)
{
    zval destination;
    object_init_ex(&destination, ce_QdbTimestamp);
    class_storage* this = get_class_storage(&destination);

    ZVAL_LONG(&this->seconds, ts->tv_sec);
    ZVAL_LONG(&this->nanoseconds, ts->tv_nsec);
    return destination;
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_METHOD(seconds)
    ADD_METHOD(nanoseconds)
END_CLASS_MEMBERS()

#include "class_definition.i"
