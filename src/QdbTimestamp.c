// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

struct zval_timestamp_t {
    zend_object std;
    zval* seconds;
    zval* nanoseconds;
};

#define class_name QdbTimestamp
#define class_storage struct zval_timestamp_t

extern zend_class_entry* ce_QdbTimestamp;

CLASS_METHOD_2(__construct, LONG_ARG(seconds), LONG_ARG(nanoseconds))
{
    Z_ADDREF_P(seconds);
    Z_ADDREF_P(nanoseconds);

    this->seconds     = seconds;
    this->nanoseconds = nanoseconds;
}

CLASS_METHOD_0(__destruct)
{
    Z_DELREF_P(this->seconds);
    Z_DELREF_P(this->nanoseconds);
}

CLASS_METHOD_0(seconds)
{
    Z_ADDREF_P(this->seconds);
    *return_value = *this->seconds;
}

CLASS_METHOD_0(nanoseconds)
{
    Z_ADDREF_P(this->nanoseconds);
    *return_value = *this->nanoseconds;
}

qdb_timespec_t QdbTimestamp_make_timespec(zval* timestamp)
{
    CHECK_TYPE_OF_OBJECT_ARG(QdbTimestamp, timestamp);
    class_storage* this = (class_storage*) Z_OBJ_P(timestamp);

    qdb_timespec_t ts;
    ts.tv_sec  = Z_LVAL_P(this->seconds);
    ts.tv_nsec = Z_LVAL_P(this->nanoseconds);
    return ts;
}

zval* QdbTimestamp_from_timespec(qdb_timespec_t* ts)
{
    zval* destination;
    MAKE_STD_ZVAL(destination);
    object_init_ex(destination, ce_QdbTimestamp);
    class_storage* this = (class_storage*)Z_OBJ_P(destination);

    MAKE_STD_ZVAL(this->seconds);
    ZVAL_LONG(this->seconds, ts->tv_sec);

    MAKE_STD_ZVAL(this->nanoseconds);
    ZVAL_LONG(this->nanoseconds, ts->tv_nsec);

    return destination;
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_CONSTRUCTOR(__destruct)
    ADD_METHOD(seconds)
    ADD_METHOD(nanoseconds)
END_CLASS_MEMBERS()

#include "class_definition.i"
