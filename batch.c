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

#include "batch.h"

#include <php.h>
#include <qdb/client.h>
#include "class_definition.h"
#include "exceptions.h"
#include "common_params.h"

#define class_name      QdbBatch
#define class_storage   batch_t


static void grow_buffer_if_needed(batch_t* this)
{
    if (this->length < this->capacity) return;
    
    this->capacity *= 2;
    this->operations = erealloc(this->operations, this->capacity*sizeof(batch_operation_t));
}

static batch_operation_t* next_operation(batch_t* this)
{
    grow_buffer_if_needed(this);

    batch_operation_t* op = &this->operations[this->length];
    memset(op, 0, sizeof(batch_operation_t));
    return op;
}

CLASS_METHOD_0(__construct)
{
    batch_t *this = get_this();

    this->length = 0;
    this->capacity = 16;
    this->operations = emalloc(this->capacity*sizeof(batch_operation_t));
}

CLASS_METHOD_0(__destruct)
{
    batch_t *this = get_this();
    size_t i;

    for (i=0 ; i<this->length; i++)
    {
        batch_operation_t *op = &this->operations[i];

        if (op->alias) Z_DELREF_P(op->alias);
        if (op->content) Z_DELREF_P(op->content);
        if (op->comparand) Z_DELREF_P(op->comparand);
    }

    efree(this->operations);
}

CLASS_METHOD_3_1(compareAndSwap, alias, content, comparand, expiry)
{
    batch_t *this = get_this();
    batch_operation_t* op = next_operation(this);

    if (parse_alias_val_cmp_o_exp(&op->alias, &op->content, &op->comparand, &op->expiry_time) == FAILURE)
        return;

    op->type = qdb_op_cas;
    Z_ADDREF_P(op->alias);
    Z_ADDREF_P(op->content);
    Z_ADDREF_P(op->comparand);
    this->length++;
}

CLASS_METHOD_1(get, alias)
{
    batch_t *this = get_this();
    batch_operation_t* op = next_operation(this);

    if (parse_alias(&op->alias) == FAILURE)
        return;

    op->type = qdb_op_get_alloc;
    Z_ADDREF_P(op->alias);
    this->length++;
}

CLASS_METHOD_1(getRemove, alias)
{
    batch_t *this = get_this();
    batch_operation_t* op = next_operation(this);

    if (parse_alias(&op->alias) == FAILURE)
        return;

    op->type = qdb_op_get_remove;
    Z_ADDREF_P(op->alias);
    this->length++;
}

CLASS_METHOD_2_1(getUpdate, alias, content, expiry)
{
    batch_t *this = get_this();
    batch_operation_t* op = next_operation(this);

    if (parse_alias_val_o_exp(&op->alias, &op->content, &op->expiry_time) == FAILURE)
        return;

    op->type = qdb_op_get_update;
    Z_ADDREF_P(op->alias);
    Z_ADDREF_P(op->content);
    this->length++;
}

CLASS_METHOD_2_1(put, alias, content, expiry)
{
    batch_t *this = get_this();
    batch_operation_t* op = next_operation(this);

    if (parse_alias_val_o_exp(&op->alias, &op->content, &op->expiry_time) == FAILURE)
        return;

    op->type = qdb_op_put;
    Z_ADDREF_P(op->alias);
    Z_ADDREF_P(op->content);
    this->length++;
}

CLASS_METHOD_1(remove, alias)
{
    batch_t *this = get_this();
    batch_operation_t* op = next_operation(this);

    if (parse_alias(&op->alias) == FAILURE)
        return;

    op->type = qdb_op_remove;
    Z_ADDREF_P(op->alias);
    this->length++;
}

CLASS_METHOD_2(removeIf, alias, comparand)
{
    batch_t *this = get_this();
    batch_operation_t* op = next_operation(this);

    if (parse_alias_cmp(&op->alias, &op->comparand) == FAILURE)
        return;

    op->type = qdb_op_remove_if;
    Z_ADDREF_P(op->alias);
    Z_ADDREF_P(op->comparand);
    this->length++;
}

CLASS_METHOD_2_1(update, alias, content, expiry)
{
    batch_t *this = get_this();
    batch_operation_t* op = next_operation(this);

    if (parse_alias_val_o_exp(&op->alias, &op->content, &op->expiry_time) == FAILURE)
        return;

    op->type = qdb_op_update;
    Z_ADDREF_P(op->alias);
    Z_ADDREF_P(op->content);
    this->length++;
}

CLASS_BEGIN_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(compareAndSwap)
    ADD_METHOD(get)
    ADD_METHOD(getRemove)
    ADD_METHOD(getUpdate)
    ADD_METHOD(put)
    ADD_METHOD(remove)
    ADD_METHOD(removeIf)
    ADD_METHOD(update)
CLASS_END_MEMBERS()

#include "class_definition.c"
