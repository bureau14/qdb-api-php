// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "class_definition.h"
#include "exceptions.h"
#include "QdbEntry.h"


#define class_name          QdbQueue
#define class_storage       entry_t
#define class_parent        QdbEntry


extern zend_class_entry* ce_QdbQueue;


void QdbQueue_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    object_init_ex(destination, ce_QdbQueue);
    QdbEntry_constructInstance(destination, handle, alias TSRMLS_CC);
}


BEGIN_CLASS_METHOD_0(back)
{
    const char* result = NULL;
    size_t result_len = 0;

    qdb_error_t error = qdb_queue_back(this->handle, Z_STRVAL_P(this->alias), &result, &result_len);

    if (error)
        throw_qdb_error(error);
    else
        ZVAL_STRINGL(return_value, result, result_len, /*duplicate=*/1);

    qdb_free_buffer(this->handle, result);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(front)
{
    const char* result = NULL;
    size_t result_len = 0;

    qdb_error_t error = qdb_queue_front(this->handle, Z_STRVAL_P(this->alias), &result, &result_len);

    if (error)
        throw_qdb_error(error);
    else
        ZVAL_STRINGL(return_value, result, result_len, /*duplicate=*/1);

    qdb_free_buffer(this->handle, result);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(popBack)
{
    const char* result = NULL;
    size_t result_len = 0;

    qdb_error_t error = qdb_queue_pop_back(this->handle, Z_STRVAL_P(this->alias), &result, &result_len);

    if (error)
        throw_qdb_error(error);
    else
        ZVAL_STRINGL(return_value, result, result_len, /*duplicate=*/1);

    qdb_free_buffer(this->handle, result);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(popFront)
{
    const char* result = NULL;
    size_t result_len = 0;

    qdb_error_t error = qdb_queue_pop_front(this->handle, Z_STRVAL_P(this->alias), &result, &result_len);

    if (error)
        throw_qdb_error(error);
    else
        ZVAL_STRINGL(return_value, result, result_len, /*duplicate=*/1);

    qdb_free_buffer(this->handle, result);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(pushBack, STRING_ARG(content))
{
    qdb_error_t error = qdb_queue_push_back(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(pushFront, STRING_ARG(content))
{
    qdb_error_t error = qdb_queue_push_front(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_METHOD(back)
    ADD_METHOD(front)
    ADD_METHOD(popBack)
    ADD_METHOD(popFront)
    ADD_METHOD(pushBack)
    ADD_METHOD(pushFront)
END_CLASS_MEMBERS()

#include "class_definition.i"
