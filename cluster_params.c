
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

#include "batch.h"
#include "cluster_params.h"
#include "exceptions.h"

static void extract_operations(qdb_operation_t** operations, size_t* operation_count, zval *zbatch TSRMLS_DC);
static void extract_one_operation(qdb_operation_t *dst, batch_operation_t* src);
static int  extract_each_node(qdb_remote_node_t *nodes, HashTable *hnodes TSRMLS_DC);
static int  extract_one_node(qdb_remote_node_t *node, HashTable *hnode TSRMLS_DC);

int parse_batch_n(int num_args, qdb_operation_t** operations, size_t* operation_count TSRMLS_DC)
{
    zval *zbatch;
    
    if (num_args < 1)
    {
        throw_invalid_argument("Not enough arguments, expected exactly one");
        return FAILURE;
    }

    if (num_args > 1)
    {
        throw_invalid_argument("Too many arguments, expected exactly one");
        return FAILURE;
    }

    if (zend_parse_parameters(num_args TSRMLS_CC, "O", &zbatch, ce_QdbBatch) == FAILURE)
    {
        throw_invalid_argument("Argument 1 (batch) must be a QdbBatch instance");
        return FAILURE;
    }

    extract_operations(operations, operation_count, zbatch TSRMLS_CC);

    return SUCCESS;
}

static void extract_operations(qdb_operation_t** operations, size_t* operation_count, zval *zbatch TSRMLS_DC)
{
    size_t i;
    batch_t* batch = (batch_t*)zend_object_store_get_object(zbatch TSRMLS_CC);

    *operation_count = batch->length;
    *operations = emalloc(batch->length*sizeof(qdb_operation_t));
    qdb_init_operations(*operations, *operation_count);

    for (i=0; i<batch->length; i++)
    {
        extract_one_operation(&(*operations)[i], &batch->operations[i]);
    }
}

static void extract_one_operation(qdb_operation_t *dst, batch_operation_t* src)
{
    dst->type = src->type;
    dst->alias = Z_STRVAL_P(src->alias);
    dst->expiry_time = src->expiry_time;
    
    if (src->content)
    {
       dst->content = Z_STRVAL_P(src->content);
       dst->content_size = Z_STRLEN_P(src->content);
    }

    if (src->comparand)
    {
       dst->comparand = Z_STRVAL_P(src->comparand);
       dst->comparand_size = Z_STRLEN_P(src->comparand);
    }
}

int parse_node_list_n(int num_args, qdb_remote_node_t** nodes, int* node_count TSRMLS_DC)
{
    zval *znodes;

    *nodes = NULL;
    *node_count = 0;

    if (num_args < 1)
    {
        throw_invalid_argument("Not enough arguments, expected exactly one");
        return FAILURE;
    }

    if (num_args > 1)
    {
        throw_invalid_argument("Too many arguments, expected exactly one");
        return FAILURE;
    }

    if (zend_parse_parameters(num_args TSRMLS_CC, "a", &znodes) == FAILURE)
    {
        throw_invalid_argument("Argument 1 (nodes) must be an array");
        return FAILURE;
    }
    
    HashTable *hnodes = Z_ARRVAL_P(znodes);

    *node_count = zend_hash_num_elements(hnodes);

    if (*node_count == 0)
    {
        throw_invalid_argument("Node list is empty.");
        return FAILURE;
    }

    *nodes = ecalloc(*node_count, sizeof(qdb_remote_node_t));

    return extract_each_node(*nodes, hnodes TSRMLS_CC);
}

static int extract_each_node(qdb_remote_node_t *nodes, HashTable *hnodes TSRMLS_DC)
{
    HashPosition pointer;
    zval **znode;

    zend_hash_internal_pointer_reset_ex(hnodes, &pointer);

    while(zend_hash_get_current_data_ex(hnodes, (void**) &znode, &pointer) == SUCCESS)
    {
        if (extract_one_node(nodes++, Z_ARRVAL_PP(znode) TSRMLS_CC) == FAILURE)
            return FAILURE;
        
        zend_hash_move_forward_ex(hnodes, &pointer);
    }

    return SUCCESS;
}

static int extract_one_node(qdb_remote_node_t *node, HashTable *hnode TSRMLS_DC)
{
    zval **zaddress;

    if (zend_hash_find(hnode, "address", sizeof("address"), (void**)&zaddress) == FAILURE) {
        throw_invalid_argument("Node address is missing");
        return FAILURE;
    }
    
    zval **zport = 0;
    zend_hash_find(hnode, "port", sizeof("port"), (void**)&zport);

    node->address = Z_STRVAL_PP(zaddress);
    node->port = zport ? Z_LVAL_PP(zport) : 2836;

    return SUCCESS;
}
