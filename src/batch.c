// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "batch.h"
#include "class_definition.h"
#include "exceptions.h"
#include "common_params.h"

#include <qdb/client.h>

#define class_name      QdbBatch
#define class_storage   batch_t


static void grow_buffer_if_needed(batch_t* this)
{
    if (this->length < this->capacity) return;

    this->capacity *= 2;
    this->operations = erealloc(this->operations, this->capacity*sizeof(batch_operation_t));
}

static batch_operation_t* alloc_operation(batch_t* this)
{
    grow_buffer_if_needed(this);

    batch_operation_t* op = &this->operations[this->length];
    memset(op, 0, sizeof(batch_operation_t));
    this->length++;

    return op;
}


BEGIN_CLASS_METHOD_0(__construct)
{
    this->length = 0;
    this->capacity = 16;
    this->operations = emalloc(this->capacity*sizeof(batch_operation_t));
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(__destruct)
{
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
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_3_1(compareAndSwap, STRING_ARG(alias), STRING_ARG(content), STRING_ARG(comparand), LONG_ARG(expiry))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(content);
    Z_ADDREF_P(comparand);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->comparand = comparand;
    op->content = content;
    op->expiry_time = expiry ? Z_LVAL_P(expiry) : 0;
    op->type = qdb_op_cas;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(get, STRING_ARG(alias))
{
    Z_ADDREF_P(alias);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->type = qdb_op_get_alloc;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(getRemove, STRING_ARG(alias))
{
    Z_ADDREF_P(alias);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->type = qdb_op_get_remove;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_2_1(getUpdate, STRING_ARG(alias), STRING_ARG(content), LONG_ARG(expiry))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(content);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->content = content;
    op->expiry_time = expiry ? Z_LVAL_P(expiry) : 0;
    op->type = qdb_op_get_update;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_2_1(put, STRING_ARG(alias), STRING_ARG(content), LONG_ARG(expiry))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(content);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->content = content;
    op->expiry_time = expiry ? Z_LVAL_P(expiry) : 0;
    op->type = qdb_op_put;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(remove, STRING_ARG(alias))
{
    Z_ADDREF_P(alias);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->type = qdb_op_remove;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_2(removeIf, STRING_ARG(alias), STRING_ARG(comparand))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(comparand);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->comparand = comparand;
    op->type = qdb_op_remove_if;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_2_1(update, STRING_ARG(alias), STRING_ARG(content), LONG_ARG(expiry))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(content);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->content = content;
    op->expiry_time = expiry ? Z_LVAL_P(expiry) : 0;
    op->type = qdb_op_update;
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
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
END_CLASS_MEMBERS()

#include "class_definition.c"
