// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "class_definition.h"
#include "QdbEntry.h"
#include "QdbExpirableEntry.h"
#include "exceptions.h"

#include <qdb/client.h>

#define class_name QdbExpirableEntry
#define class_storage entry_t
#define class_parent QdbEntry


extern zend_class_entry* ce_QdbExpirableEntry;

void QdbExpirableEntry_constructInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    QdbEntry_constructInstance(destination, handle, alias TSRMLS_CC);
}


CLASS_METHOD_1(expiresAt, LONG_ARG(expiry))
{
    qdb_error_t error = qdb_expires_at(this->handle, Z_STRVAL_P(this->alias), Z_LVAL_P(expiry));

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_1(expiresFromNow, LONG_ARG(expiry))
{
    qdb_error_t error = qdb_expires_from_now(this->handle, Z_STRVAL_P(this->alias), Z_LVAL_P(expiry));

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_0(getExpiryTime)
{
    qdb_time_t expiry;

    qdb_error_t error = qdb_get_expiry_time(this->handle, Z_STRVAL_P(this->alias), &expiry);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        RETURN_LONG(expiry);
    }
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(expiresAt)
    ADD_METHOD(expiresFromNow)
    ADD_METHOD(getExpiryTime)
END_CLASS_MEMBERS()

#include "class_definition.i"
