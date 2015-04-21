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

#define class_name          QdbQueue
#define class_storage       queue_t


typedef struct {
    zend_object std;
    qdb_handle_t handle;
    zval* alias;
} queue_t;

extern zend_class_entry* ce_QdbQueue;


void QdbQueue_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    queue_t* this;

    object_init_ex(destination, ce_QdbQueue);
    this = (queue_t*)zend_object_store_get_object(destination TSRMLS_CC);

    Z_ADDREF_P(alias);

    this->alias = alias;
    this->handle = handle;
}


BEGIN_CLASS_METHOD_0(alias)
{
    RETURN_ZVAL(this->alias, /*copy=*/0, /*dtor=*/0);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(__destruct)
{
    Z_ADDREF_P(this->alias);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(popBack)
{
    const char* result;
    size_t result_len;

    qdb_error_t error = qdb_list_pop_back(this->handle, Z_STRVAL_P(this->alias), &result, &result_len);

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


BEGIN_CLASS_METHOD_0(popFront)
{
    const char* result;
    size_t result_len;

    qdb_error_t error = qdb_list_pop_front(this->handle, Z_STRVAL_P(this->alias), &result, &result_len);

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


BEGIN_CLASS_METHOD_1(pushBack, STRING_ARG(content))
{
    qdb_error_t error = qdb_list_push_back(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(pushFront, STRING_ARG(content))
{
    qdb_error_t error = qdb_list_push_front(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(alias)
    ADD_METHOD(popBack)
    ADD_METHOD(popFront)
    ADD_METHOD(pushBack)
    ADD_METHOD(pushFront)
END_CLASS_MEMBERS()

#include "class_definition.c"
