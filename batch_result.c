
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

#define class_name          QdbBatchResult
#define class_storage       batch_result_t
#define class_interfaces    2,spl_ce_Countable,zend_ce_arrayaccess


typedef struct {
    zend_object std;
    qdb_handle_t handle;
    qdb_operation_t * operations;
    size_t operations_count;
} batch_result_t;

extern zend_class_entry* ce_QdbBatchResult;


void create_QdbBatchResult(zval* destination, qdb_handle_t handle, qdb_operation_t * operations, size_t operations_count TSRMLS_DC)
{
    batch_result_t* this;

    object_init_ex(destination, ce_QdbBatchResult);
    this = (batch_result_t*)zend_object_store_get_object(destination TSRMLS_CC);

    this->handle = handle;
    this->operations = operations;
    this->operations_count = operations_count;
}


CLASS_METHOD_0(__destruct)
{
    batch_result_t* this = get_this();

    qdb_free_operations(this->handle, this->operations, this->operations_count);
    efree(this->operations);
}

CLASS_METHOD_0(count) // inherited from Countable
{
    batch_result_t* this = get_this();

    RETURN_LONG(this->operations_count);
}

CLASS_METHOD_1(offsetExists, offset) // inherited from ArrayAccess
{
    batch_result_t* this = get_this();
    long offset;

    if (parse_offset(&offset) == FAILURE)
        return;
    
    RETURN_BOOL(offset>=0 && offset<(long)this->operations_count);
}

CLASS_METHOD_1(offsetGet, offset) // inherited from ArrayAccess
{
    batch_result_t* this = get_this();
    long offset;

    if (parse_offset(&offset) == FAILURE)
        return;

    qdb_operation_t *op = &this->operations[offset];

    if (offset < 0){
        throw_out_of_range("Offset must be positive of zero");
    }
    else if (offset >= (long)this->operations_count) {
        throw_out_of_bounds("Offset must be smaller than the number of operations");
    }
    else if (op->type == qdb_op_remove_if && op->error == qdb_e_unmatched_content){
        RETURN_FALSE;
    }
    else if (op->type == qdb_op_remove_if && op->error == 0){
        RETURN_TRUE;
    }
    else if (op->error){
        throw_qdb_error(op->error);
    }
    else if (op->result){
        RETURN_STRINGL(op->result, op->result_size, /*duplicate=*/1);
    }
}

CLASS_METHOD_2(offsetSet, offset, value) // inherited from ArrayAccess
{
    throw_bad_function_call("Batch result is read-only");
}

CLASS_METHOD_1(offsetUnset, offset) // inherited from ArrayAccess
{
    throw_bad_function_call("Batch result is read-only");
}

CLASS_BEGIN_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(count)
    ADD_METHOD(offsetExists)
    ADD_METHOD(offsetGet)
    ADD_METHOD(offsetSet)
    ADD_METHOD(offsetUnset)
CLASS_END_MEMBERS()

#include "class_definition.c"
