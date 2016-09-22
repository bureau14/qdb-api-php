// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "QdbBatchResult.h"
#include "QdbEntry.h"
#include "QdbExpirableEntry.h"
#include "class_definition.h"
#include "exceptions.h"

#include <qdb/integer.h>

#define class_name QdbInteger
#define class_storage entry_t
#define class_parent QdbExpirableEntry


extern zend_class_entry* ce_QdbInteger;


void QdbInteger_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    object_init_ex(destination, ce_QdbInteger);
    QdbExpirableEntry_constructInstance(destination, handle, alias TSRMLS_CC);
}


CLASS_METHOD_1(add, LONG_ARG(value))
{
    qdb_int_t result;

    qdb_error_t error = qdb_int_add(this->handle, Z_STRVAL_P(this->alias), Z_LVAL_P(value), &result);

    if (error)
        throw_qdb_error(error);
    else
        ZVAL_LONG(return_value, result);
}

CLASS_METHOD_0(get)
{
    qdb_int_t result;

    qdb_error_t error = qdb_int_get(this->handle, Z_STRVAL_P(this->alias), &result);

    if (error)
        throw_qdb_error(error);
    else
        ZVAL_LONG(return_value, result);
}

CLASS_METHOD_1_1(put, LONG_ARG(value), LONG_ARG(expiry))
{
    qdb_error_t error = qdb_int_put(
        this->handle, Z_STRVAL_P(this->alias), Z_LVAL_P(value), expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0);

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_1_1(update, LONG_ARG(value), LONG_ARG(expiry))
{
    qdb_error_t error = qdb_int_update(
        this->handle, Z_STRVAL_P(this->alias), Z_LVAL_P(value), expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0);

    switch (error)
    {
        case qdb_e_ok:
            RETVAL_FALSE;
            break;

        case qdb_e_ok_created:
            RETVAL_TRUE;
            break;

        default:
            throw_qdb_error(error);
            break;
    }
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(add)
    ADD_METHOD(get)
    ADD_METHOD(put)
    ADD_METHOD(update)
END_CLASS_MEMBERS()

#include "class_definition.i"
