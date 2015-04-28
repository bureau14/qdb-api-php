// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "QdbBatchResult.h"
#include "class_definition.h"
#include "QdbEntry.h"
#include "QdbExpirableEntry.h"
#include "exceptions.h"

#include <qdb/client.h>

#define class_name          QdbInteger
#define class_storage       entry_t
#define class_parent        QdbExpirableEntry


extern zend_class_entry* ce_QdbInteger;


void QdbInteger_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    object_init_ex(destination, ce_QdbInteger);
    QdbExpirableEntry_constructInstance(destination, handle, alias TSRMLS_CC);
}


BEGIN_CLASS_METHOD_1(add, LONG_ARG(value))
{
    qdb_int result;

    qdb_error_t error = qdb_int_add(this->handle, Z_STRVAL_P(this->alias), Z_LVAL_P(value), &result);

    if (error)
        throw_qdb_error(error);
    else
        ZVAL_LONG(return_value, result);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(get)
{
    qdb_int result;

    qdb_error_t error = qdb_int_get(this->handle, Z_STRVAL_P(this->alias), &result);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        ZVAL_LONG(return_value, result);
    }
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1_1(put, LONG_ARG(value), LONG_ARG(expiry))
{
    qdb_error_t error = qdb_int_put(
        this->handle,
        Z_STRVAL_P(this->alias),
        Z_LVAL_P(value),
        expiry ? Z_LVAL_P(expiry) : 0);

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1_1(update, LONG_ARG(value), LONG_ARG(expiry))
{
    qdb_error_t error = qdb_int_update(
        this->handle,
        Z_STRVAL_P(this->alias),
        Z_LVAL_P(value),
        expiry ? Z_LVAL_P(expiry) : 0);

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_METHOD(add)
    ADD_METHOD(get)
    ADD_METHOD(put)
    ADD_METHOD(update)
END_CLASS_MEMBERS()

#include "class_definition.c"
