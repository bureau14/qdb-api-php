// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "batch_result.h"
#include "class_definition.h"
#include "exceptions.h"
#include "common_params.h"

#include <qdb/client.h>

#define class_name          QdbBlob
#define class_storage       blob_t


typedef struct {
    zend_object std;
    qdb_handle_t handle;
    zval* alias;
} blob_t;

extern zend_class_entry* ce_QdbBlob;


void QdbBlob_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    blob_t* this;

    object_init_ex(destination, ce_QdbBlob);
    this = (blob_t*)zend_object_store_get_object(destination TSRMLS_CC);

    Z_ADDREF_P(alias);

    this->alias = alias;
    this->handle = handle;
}


BEGIN_CLASS_METHOD_0(__destruct)
{
    Z_ADDREF_P(this->alias);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(alias)
{
    RETURN_ZVAL(this->alias, /*copy=*/0, /*dtor=*/0);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_2_1(compareAndSwap, STRING_ARG(content), STRING_ARG(comparand), LONG_ARG(expiry))
{
    const char* result;
    size_t result_len;

    qdb_error_t error = qdb_compare_and_swap(
        this->handle,
        Z_STRVAL_P(this->alias),
        Z_STRVAL_P(content), Z_STRLEN_P(content),
        Z_STRVAL_P(comparand), Z_STRLEN_P(comparand),
        expiry ? Z_LVAL_P(expiry) : 0,
        &result, &result_len);

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
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(expiresAt, LONG_ARG(expiry))
{
    qdb_error_t error = qdb_expires_at(this->handle, Z_STRVAL_P(this->alias), Z_LVAL_P(expiry));

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(expiresFromNow, LONG_ARG(expiry))
{
    qdb_error_t error = qdb_expires_from_now(this->handle, Z_STRVAL_P(this->alias), Z_LVAL_P(expiry));

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(get)
{
    const char* result;
    size_t result_len;

    qdb_error_t error = qdb_get_buffer(this->handle, Z_STRVAL_P(this->alias), &result, &result_len);

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
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(getExpiryTime)
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
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(getRemove)
{
    const char* result;
    size_t result_len;

    qdb_error_t error = qdb_get_remove(this->handle, Z_STRVAL_P(this->alias), &result, &result_len);

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
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1_1(getUpdate, STRING_ARG(content), LONG_ARG(expiry))
{
    const char* result;
    size_t result_len;

    qdb_error_t error = qdb_get_buffer_update(this->handle,
        Z_STRVAL_P(this->alias),
        Z_STRVAL_P(content), Z_STRLEN_P(content),
        expiry ? Z_LVAL_P(expiry) : 0,
        &result, &result_len);

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
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1_1(put, STRING_ARG(content), LONG_ARG(expiry))
{
    qdb_error_t error = qdb_put(this->handle,
        Z_STRVAL_P(this->alias),
        Z_STRVAL_P(content), Z_STRLEN_P(content),
        expiry ? Z_LVAL_P(expiry) : 0);

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(remove)
{
    qdb_error_t error = qdb_remove(this->handle, Z_STRVAL_P(this->alias));

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(removeIf, STRING_ARG(comparand))
{
    qdb_error_t error = qdb_remove_if(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(comparand), Z_STRLEN_P(comparand));

    if (error == 0)
    {
        RETURN_TRUE;
    }
    else if (error == qdb_e_unmatched_content)
    {
        RETURN_FALSE;
    }
    else
    {
        throw_qdb_error(error);
    }
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1_1(update, STRING_ARG(content), LONG_ARG(expiry))
{
    qdb_error_t error = qdb_update(this->handle,
        Z_STRVAL_P(this->alias),
        Z_STRVAL_P(content), Z_STRLEN_P(content),
        expiry ? Z_LVAL_P(expiry) : 0);

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(alias)
    ADD_METHOD(compareAndSwap)
    ADD_METHOD(expiresAt)
    ADD_METHOD(expiresFromNow)
    ADD_METHOD(get)
    ADD_METHOD(getExpiryTime)
    ADD_METHOD(getRemove)
    ADD_METHOD(getUpdate)
    ADD_METHOD(put)
    ADD_METHOD(remove)
    ADD_METHOD(removeIf)
    ADD_METHOD(update)
END_CLASS_MEMBERS()

#include "class_definition.c"
