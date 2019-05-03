// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

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
    RETURN_ZVAL(this->seconds, 0, 0);
}

CLASS_METHOD_0(nanoseconds)
{
    RETURN_ZVAL(this->nanoseconds, 0, 0);
}

qdb_timespec_t QdbTimestamp_make_timespec(zval* timestamp TSRMLS_CC)
{
    CHECK_TYPE_OF_OBJECT_ARG(QdbTimestamp, timestamp);
    class_storage* this = (class_storage*) zend_object_store_get_object(timestamp TSRMLS_CC);

    qdb_timespec_t ts;
    ts.tv_sec  = Z_LVAL_P(this->seconds);
    ts.tv_nsec = Z_LVAL_P(this->nanoseconds);
    return ts;
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_CONSTRUCTOR(__destruct)
    ADD_METHOD(seconds)
    ADD_METHOD(nanoseconds)
END_CLASS_MEMBERS()

#include "class_definition.i"
