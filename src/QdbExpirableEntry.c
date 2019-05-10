// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "QdbEntry.h"
#include "QdbExpirableEntry.h"
#include "class_definition.h"
#include "exceptions.h"

#include <qdb/client.h>

#define class_name QdbExpirableEntry
#define class_storage entry_t
#define class_parent QdbEntry


extern zend_class_entry* ce_QdbExpirableEntry;

void QdbExpirableEntry_constructInstance(zval* destination, qdb_handle_t handle, zval* alias)
{
    QdbEntry_constructInstance(destination, handle, alias);
}

CLASS_METHOD_1(expiresAt, LONG_ARG(expiry))
{
    qdb_error_t error = qdb_expires_at(this->handle, Z_STRVAL_P(this->alias), to_expiry_unit(Z_LVAL_P(expiry)));

    if (error) throw_qdb_error(error);
}

CLASS_METHOD_1(expiresFromNow, LONG_ARG(expiry))
{
    qdb_error_t error = qdb_expires_from_now(this->handle, Z_STRVAL_P(this->alias), to_expiry_unit(Z_LVAL_P(expiry)));

    if (error) throw_qdb_error(error);
}

CLASS_METHOD_0(getExpiryTime)
{
    qdb_entry_metadata_t metadata;
    qdb_error_t error = qdb_get_metadata(this->handle, Z_STRVAL_P(this->alias), &metadata);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        RETURN_LONG((long)(metadata.expiry_time.tv_sec));
    }
}

qdb_time_t to_expiry_unit(long long seconds) {
    return (qdb_time_t)1000ll * (qdb_time_t)seconds;
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(expiresAt)
    ADD_METHOD(expiresFromNow)
    ADD_METHOD(getExpiryTime)
END_CLASS_MEMBERS()

#include "class_definition.i"
