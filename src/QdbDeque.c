// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "class_definition.h"
#include "exceptions.h"
#include "QdbEntry.h"

#include <qdb/deque.h>

#define class_name QdbDeque
#define class_storage entry_t
#define class_parent QdbEntry


extern zend_class_entry* ce_QdbDeque;


void QdbDeque_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    object_init_ex(destination, ce_QdbDeque);
    QdbEntry_constructInstance(destination, handle, alias TSRMLS_CC);
}


CLASS_METHOD_0(back)
{
    const char* result = NULL;
    qdb_size_t result_len = 0;

    qdb_error_t error = qdb_deque_back(this->handle, Z_STRVAL_P(this->alias), (const void**)&result, &result_len);

    if (error)
        throw_qdb_error(error);
    else
        RETVAL_STRINGL(result, (int)result_len, /*duplicate=*/1);

    qdb_free_buffer(this->handle, result);
}

CLASS_METHOD_0(front)
{
    const char* result = NULL;
    qdb_size_t result_len = 0;

    qdb_error_t error = qdb_deque_front(this->handle, Z_STRVAL_P(this->alias), (const void**)&result, &result_len);

    if (error)
        throw_qdb_error(error);
    else
        RETVAL_STRINGL(result, (int)result_len, /*duplicate=*/1);

    qdb_free_buffer(this->handle, result);
}

CLASS_METHOD_0(popBack)
{
    const char* result = NULL;
    qdb_size_t result_len = 0;

    qdb_error_t error = qdb_deque_pop_back(this->handle, Z_STRVAL_P(this->alias), (const void**)&result, &result_len);

    if (error)
        throw_qdb_error(error);
    else
        RETVAL_STRINGL(result, (int)result_len, /*duplicate=*/1);

    qdb_free_buffer(this->handle, result);
}

CLASS_METHOD_0(popFront)
{
    const char* result = NULL;
    qdb_size_t result_len = 0;

    qdb_error_t error = qdb_deque_pop_front(this->handle, Z_STRVAL_P(this->alias), (const void**)&result, &result_len);

    if (error)
        throw_qdb_error(error);
    else
        RETVAL_STRINGL(result, (int)result_len, /*duplicate=*/1);

    qdb_free_buffer(this->handle, result);
}

CLASS_METHOD_1(pushBack, STRING_ARG(content))
{
    qdb_error_t error =
        qdb_deque_push_back(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_1(pushFront, STRING_ARG(content))
{
    qdb_error_t error =
        qdb_deque_push_front(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_0(size)
{
    qdb_size_t size;
    qdb_error_t error = qdb_deque_size(this->handle, Z_STRVAL_P(this->alias), &size);

    if (error)
        throw_qdb_error(error);

    RETVAL_LONG((long)size);
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(back)
    ADD_METHOD(front)
    ADD_METHOD(popBack)
    ADD_METHOD(popFront)
    ADD_METHOD(pushBack)
    ADD_METHOD(pushFront)
    ADD_METHOD(size)
END_CLASS_MEMBERS()

#include "class_definition.i"
