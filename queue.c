
/*


 Copyright (c) 2009-2015, quasardb SAS
 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of quasardb nor the names of its contributors may
      be used to endorse or promote products derived from this software
      without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY QUASARDB AND CONTRIBUTORS ``AS IS'' AND ANY
 EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED. IN NO EVENT SHALL THE REGENTS AND CONTRIBUTORS BE LIABLE FOR ANY
 DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


*/

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


void create_QdbQueue(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    queue_t* this;

    object_init_ex(destination, ce_QdbQueue);
    this = (queue_t*)zend_object_store_get_object(destination TSRMLS_CC);

    Z_ADDREF_P(alias);

    this->alias = alias;
    this->handle = handle;
}

CLASS_METHOD_0(alias)
{
    queue_t* this = get_this();

    RETURN_ZVAL(this->alias, /*copy=*/0, /*dtor=*/0);
}

CLASS_METHOD_0(__destruct)
{
    queue_t* this = get_this();

    Z_ADDREF_P(this->alias);
}

CLASS_METHOD_0(popBack)
{ 
    queue_t* this = get_this();
    const char* result;
    size_t result_len;

    if (check_no_args() == FAILURE)
        return;

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

CLASS_METHOD_0(popFront)
{ 
    queue_t* this = get_this();
    const char* result;
    size_t result_len;

    if (check_no_args() == FAILURE)
        return;

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

CLASS_METHOD_1(pushBack, STRING_ARG(content))
{ 
    queue_t* this = get_this();
    zval *content;

    if (parse_val(&content) == FAILURE)
        return;

    qdb_error_t error = qdb_list_push_back(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_1(pushFront, STRING_ARG(content))
{ 
    queue_t* this = get_this();
    zval *content;

    if (parse_val(&content) == FAILURE)
        return;

    qdb_error_t error = qdb_list_push_front(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error)
        throw_qdb_error(error);
}

CLASS_BEGIN_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(alias)
    ADD_METHOD(popBack)
    ADD_METHOD(popFront)
    ADD_METHOD(pushBack)
    ADD_METHOD(pushFront)
CLASS_END_MEMBERS()

#include "class_definition.c"
