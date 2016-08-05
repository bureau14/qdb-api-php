// Copyright (c) 2009-2016, quasardb SAS
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
#include <qdb/blob.h>

#define class_name QdbBlob
#define class_storage entry_t
#define class_parent QdbExpirableEntry

extern zend_class_entry* ce_QdbBlob;


void QdbBlob_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    object_init_ex(destination, ce_QdbBlob);
    QdbExpirableEntry_constructInstance(destination, handle, alias TSRMLS_CC);
}


CLASS_METHOD_2_1(compareAndSwap, STRING_ARG(content), STRING_ARG(comparand), LONG_ARG(expiry))
{
    const char* result;
    qdb_size_t result_len;

    qdb_error_t error = qdb_blob_compare_and_swap(this->handle,
        Z_STRVAL_P(this->alias),
        Z_STRVAL_P(content),
        Z_STRLEN_P(content),
        Z_STRVAL_P(comparand),
        Z_STRLEN_P(comparand),
        expiry ? Z_LVAL_P(expiry) : 0,
        (const void**)&result,
        &result_len);

    switch (error)
    {
        case qdb_e_ok:
            RETVAL_NULL();
            break;

        case qdb_e_unmatched_content:
            RETVAL_STRINGL(result, result_len, /*duplicate=*/1);
            break;

        default:
            throw_qdb_error(error);
    }

    qdb_free_buffer(this->handle, result);
}

CLASS_METHOD_0(get)
{
    const char* result;
    qdb_size_t result_len;

    qdb_error_t error = qdb_blob_get(this->handle, Z_STRVAL_P(this->alias), (const void**)&result, &result_len);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        ZVAL_STRINGL(return_value, result, result_len, /*duplicate=*/1);
    }

    qdb_free_buffer(this->handle, result);
}

CLASS_METHOD_0(getAndRemove)
{
    const char* result;
    qdb_size_t result_len;

    qdb_error_t error = qdb_blob_get_and_remove(this->handle, Z_STRVAL_P(this->alias), (const void**)&result, &result_len);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        ZVAL_STRINGL(return_value, result, result_len, /*duplicate=*/1);
    }

    qdb_free_buffer(this->handle, result);
}

CLASS_METHOD_1_1(getAndUpdate, STRING_ARG(content), LONG_ARG(expiry))
{
    const char* result;
    qdb_size_t result_len;

    qdb_error_t error = qdb_blob_get_and_update(this->handle,
        Z_STRVAL_P(this->alias),
        Z_STRVAL_P(content),
        Z_STRLEN_P(content),
        expiry ? Z_LVAL_P(expiry) : 0,
        (const void**)&result,
        &result_len);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        ZVAL_STRINGL(return_value, result, result_len, /*duplicate=*/1);
    }

    qdb_free_buffer(this->handle, result);
}

CLASS_METHOD_1_1(put, STRING_ARG(content), LONG_ARG(expiry))
{
    qdb_error_t error = qdb_blob_put(
        this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content), expiry ? Z_LVAL_P(expiry) : 0);

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_1(removeIf, STRING_ARG(comparand))
{
    qdb_error_t error =
        qdb_blob_remove_if(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(comparand), Z_STRLEN_P(comparand));

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
    qdb_error_t error = qdb_blob_update(
        this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content), expiry ? Z_LVAL_P(expiry) : 0);

    if (error)
        throw_qdb_error(error);
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
