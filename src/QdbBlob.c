// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.


#include "QdbBatchResult.h"
#include "QdbBlob.h"
#include "QdbEntry.h"
#include "QdbExpirableEntry.h"
#include "class_definition.h"
#include "exceptions.h"
#include <qdb/blob.h>
#include <qdb/client.h>

#define class_name QdbBlob
#define class_storage entry_t
#define class_parent QdbExpirableEntry

void QdbBlob_createInstance(zval* destination, qdb_handle_t handle, zval* alias)
{
    object_init_ex(destination, ce_QdbBlob);
    QdbExpirableEntry_constructInstance(destination, handle, alias);
}

CLASS_METHOD_2_1(compareAndSwap, STRING_ARG(content), STRING_ARG(comparand), LONG_ARG(expiry))
{
    const char* result;
    qdb_size_t result_len;

    qdb_error_t error = qdb_blob_compare_and_swap(this->handle,
        Z_STRVAL(this->alias),
        Z_STRVAL_P(content),
        Z_STRLEN_P(content),
        Z_STRVAL_P(comparand),
        Z_STRLEN_P(comparand),
        expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0,
        (const void**)&result,
        &result_len);

    switch (error)
    {
        case qdb_e_ok:
            RETVAL_NULL();
            break;

        case qdb_e_unmatched_content:
            RETVAL_STRINGL(result, (int)result_len);
            break;

        default:
            throw_qdb_error(error);
    }

    qdb_release(this->handle, result);
}

CLASS_METHOD_0(get)
{
    const char* result;
    qdb_size_t result_len;

    qdb_error_t error = qdb_blob_get(this->handle, Z_STRVAL(this->alias), (const void**)&result, &result_len);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        RETVAL_STRINGL(result, (int)result_len);
    }

    qdb_release(this->handle, result);
}

CLASS_METHOD_0(getAndRemove)
{
    const char* result;
    qdb_size_t result_len;

    qdb_error_t error =
        qdb_blob_get_and_remove(this->handle, Z_STRVAL(this->alias), (const void**)&result, &result_len);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        RETVAL_STRINGL(result, (int)result_len);
    }

    qdb_release(this->handle, result);
}

CLASS_METHOD_1_1(getAndUpdate, STRING_ARG(content), LONG_ARG(expiry))
{
    const char* result;
    qdb_size_t result_len;

    qdb_error_t error = qdb_blob_get_and_update(this->handle,
        Z_STRVAL(this->alias),
        Z_STRVAL_P(content),
        Z_STRLEN_P(content),
        expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0,
        (const void**)&result,
        &result_len);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        RETVAL_STRINGL(result, (int)result_len);
    }

    qdb_release(this->handle, result);
}

CLASS_METHOD_1_1(put, STRING_ARG(content), LONG_ARG(expiry))
{
    qdb_error_t error = qdb_blob_put(this->handle,
        Z_STRVAL(this->alias),
        Z_STRVAL_P(content),
        Z_STRLEN_P(content),
        expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0);

    if (error) throw_qdb_error(error);
}

CLASS_METHOD_1(removeIf, STRING_ARG(comparand))
{
    qdb_error_t error =
        qdb_blob_remove_if(this->handle, Z_STRVAL(this->alias), Z_STRVAL_P(comparand), Z_STRLEN_P(comparand));

    switch (error)
    {
        case qdb_e_ok:
            RETVAL_TRUE;
            break;

        case qdb_e_unmatched_content:
            RETVAL_FALSE;
            break;

        default:
            throw_qdb_error(error);
            break;
    }
}

CLASS_METHOD_1_1(update, STRING_ARG(content), LONG_ARG(expiry))
{
    qdb_error_t error = qdb_blob_update(this->handle,
        Z_STRVAL(this->alias),
        Z_STRVAL_P(content),
        Z_STRLEN_P(content),
        expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0);

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
    ADD_METHOD(compareAndSwap)
    ADD_METHOD(get)
    ADD_METHOD(getAndRemove)
    ADD_METHOD(getAndUpdate)
    ADD_METHOD(put)
    ADD_METHOD(removeIf)
    ADD_METHOD(update)
END_CLASS_MEMBERS()

#include "class_definition.i"
