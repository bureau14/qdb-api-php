
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

#include <php.h> // include first to avoid conflict with stdint.h 
 
#include "batch_result.h"
#include "cluster_params.h"
#include "exceptions.h"
#include "common_params.h"
#include "class_definition.h"
#include "log.h"
#include "queue.h"

#include <qdb/client.h>

#define class_name      QdbCluster
#define class_storage   cluster_t

typedef struct {
    zend_object std;
    qdb_handle_t handle;
} cluster_t;

CLASS_METHOD_1(__construct, ARRAY_ARG(nodes))
{
    cluster_t *this = get_this();
    qdb_remote_node_t* nodes;
    int node_count;
    int i;

    if (parse_node_list(&nodes, &node_count) == SUCCESS)
    {
        this->handle = qdb_open_tcp();

        log_attach(this->handle);

        size_t connections = qdb_multi_connect(this->handle, nodes, node_count);

        for(i=0; i<node_count; i++) {
            if(nodes[i].error){
                char buffer[32];
                qdb_error(nodes[i].error, buffer, sizeof(buffer));
                zend_error(E_WARNING, "Connection to %s (port %d) failed: %s", nodes[i].address, nodes[i].port, buffer);
            }
        }

        if (connections <= 0)
            throw_cluster_connection_failed();
    }

    efree(nodes);
}

CLASS_METHOD_0(__destruct)
{
    cluster_t *this = get_this();

    qdb_close(this->handle);
}

CLASS_METHOD_3_1(compareAndSwap, STRING_ARG(alias), STRING_ARG(content), STRING_ARG(comparand), LONG_ARG(expiry))
{
    cluster_t *this = get_this();
    zval *alias, *content, *comparand;
    qdb_time_t expiry;
    const char* result;
    size_t result_len;

    if (parse_alias_val_cmp_o_exp(&alias, &content, &comparand, &expiry) == FAILURE)
        return;

    qdb_error_t error = qdb_compare_and_swap(
        this->handle,
        Z_STRVAL_P(alias),
        Z_STRVAL_P(content), Z_STRLEN_P(content),
        Z_STRVAL_P(comparand), Z_STRLEN_P(comparand),
        expiry,
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

CLASS_METHOD_2(expiresAt, STRING_ARG(alias), LONG_ARG(expiry))
{
    cluster_t *this = get_this();
    zval* alias;
    qdb_time_t expiry;

    if (parse_alias_exp(&alias, &expiry) == FAILURE)
        return;
    
    qdb_error_t error = qdb_expires_at(this->handle, Z_STRVAL_P(alias), expiry);

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_2(expiresFromNow, STRING_ARG(alias), LONG_ARG(expiry))
{
    cluster_t *this = get_this();
    zval* alias;
    qdb_time_t expiry;

    if (parse_alias_exp(&alias, &expiry) == FAILURE)
        return;
    
    qdb_error_t error = qdb_expires_from_now(this->handle, Z_STRVAL_P(alias), expiry);

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_1(get, STRING_ARG(alias))
{
    cluster_t *this = get_this();
    zval *alias;
    const char* result;
    size_t result_len;

    if (parse_alias(&alias) == FAILURE)
        return;

    qdb_error_t error = qdb_get_buffer(this->handle, Z_STRVAL_P(alias), &result, &result_len);

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

CLASS_METHOD_1(getExpiryTime, STRING_ARG(alias))
{
    cluster_t *this = get_this();
    zval *alias;
    qdb_time_t expiry;

    if (parse_alias(&alias) == FAILURE)
        return;

    qdb_error_t error = qdb_get_expiry_time(this->handle, Z_STRVAL_P(alias), &expiry);

    if (error)
    {
        throw_qdb_error(error);
    }
    else
    {
        RETURN_LONG(expiry);
    }
}

CLASS_METHOD_1(getQueue, STRING_ARG(alias))
{
    cluster_t *this = get_this();
    zval *alias;

    if (parse_alias(&alias) == FAILURE)
        return;

    create_QdbQueue(return_value, this->handle, alias TSRMLS_CC);
}

CLASS_METHOD_1(getRemove, STRING_ARG(alias))
{
    cluster_t *this = get_this();
    zval *alias;
    const char* result;
    size_t result_len;

    if (parse_alias(&alias) == FAILURE)
        return;

    qdb_error_t error = qdb_get_remove(this->handle, Z_STRVAL_P(alias), &result, &result_len);

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

CLASS_METHOD_2_1(getUpdate, STRING_ARG(alias), STRING_ARG(content), LONG_ARG(expiry))
{
    cluster_t *this = get_this();
    zval *alias, *content;
    qdb_time_t expiry;
    const char* result;
    size_t result_len;

    if (parse_alias_val_o_exp(&alias, &content, &expiry) == FAILURE)
        return;

    qdb_error_t error = qdb_get_buffer_update(
        this->handle,
        Z_STRVAL_P(alias),
        Z_STRVAL_P(content), Z_STRLEN_P(content),
        expiry,
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

CLASS_METHOD_2_1(put, STRING_ARG(alias), STRING_ARG(content), LONG_ARG(expiry))
{
    cluster_t *this = get_this();
    zval *alias, *content;
    qdb_time_t expiry;

    if (parse_alias_val_o_exp(&alias, &content, &expiry) == FAILURE)
        return;

    qdb_error_t error = qdb_put(this->handle, Z_STRVAL_P(alias), Z_STRVAL_P(content), Z_STRLEN_P(content), expiry);

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_1(remove, STRING_ARG(alias))
{
    cluster_t *this = get_this();
    zval *alias;

    if (parse_alias(&alias) == FAILURE)
        return;

    qdb_error_t error = qdb_remove(this->handle, Z_STRVAL_P(alias));

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_2(removeIf, STRING_ARG(alias), STRING_ARG(comparand))
{
    cluster_t *this = get_this();
    zval *alias, *comparand;

    if (parse_alias_cmp(&alias, &comparand) == FAILURE)
        return;

    qdb_error_t error = qdb_remove_if(this->handle, Z_STRVAL_P(alias), Z_STRVAL_P(comparand), Z_STRLEN_P(comparand));

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

CLASS_METHOD_1(runBatch, OBJECT_ARG(QdbBatch,batch))
{
    cluster_t *this = get_this();
    qdb_operation_t* operations;
    size_t operations_len;

    if (parse_batch(&operations, &operations_len) == FAILURE)
        return;

    qdb_run_batch(this->handle, operations, operations_len);

    create_QdbBatchResult(return_value, this->handle, operations, operations_len TSRMLS_CC);
}

CLASS_METHOD_2_1(update, STRING_ARG(alias), STRING_ARG(content), LONG_ARG(expiry))
{
    cluster_t *this = get_this();
    zval *alias, *content;
    qdb_time_t expiry;

    if (parse_alias_val_o_exp(&alias, &content, &expiry) == FAILURE)
        return;

    qdb_error_t error = qdb_update(this->handle, Z_STRVAL_P(alias), Z_STRVAL_P(content), Z_STRLEN_P(content), expiry);

    if (error)
        throw_qdb_error(error);
}

CLASS_BEGIN_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(compareAndSwap)
    ADD_METHOD(expiresAt)
    ADD_METHOD(expiresFromNow)
    ADD_METHOD(get)
    ADD_METHOD(getExpiryTime)
    ADD_METHOD(getQueue)
    ADD_METHOD(getRemove)
    ADD_METHOD(getUpdate)
    ADD_METHOD(put)
    ADD_METHOD(remove)
    ADD_METHOD(removeIf)
    ADD_METHOD(runBatch)
    ADD_METHOD(update)
CLASS_END_MEMBERS()

#include "class_definition.c"
