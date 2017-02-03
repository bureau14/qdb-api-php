// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "QdbBatch.h"
#include "QdbExpirableEntry.h"
#include "class_definition.h"
#include "exceptions.h"

#include <qdb/client.h>

#define class_name QdbBatch
#define class_storage batch_t


static void grow_buffer_if_needed(batch_t* this)
{
    if (this->length < this->capacity)
        return;

    this->capacity *= 2;
    this->operations = erealloc(this->operations, this->capacity * sizeof(batch_operation_t));
}

static batch_operation_t* alloc_operation(batch_t* this)
{
    grow_buffer_if_needed(this);

    batch_operation_t* op = &this->operations[this->length];
    memset(op, 0, sizeof(batch_operation_t));
    this->length++;

    return op;
}

static void convert_operation(qdb_operation_t* dst, batch_operation_t* src)
{
    dst->type = src->type;
    dst->alias = Z_STRVAL_P(src->alias);

    switch (dst->type)
    {
        case qdb_op_blob_put:
            if (!src->content)
                break;

            dst->blob_put.expiry_time = src->expiry_time;
            dst->blob_put.content = Z_STRVAL_P(src->content);
            dst->blob_put.content_size = Z_STRLEN_P(src->content);
            break;

        case qdb_op_blob_update:
            if (!src->content)
                break;

            dst->blob_update.expiry_time = src->expiry_time;
            dst->blob_update.content = Z_STRVAL_P(src->content);
            dst->blob_update.content_size = Z_STRLEN_P(src->content);
            break;

        case qdb_op_blob_cas:
            if (!src->content)
                break;
            if (!src->comparand)
                break;

            dst->blob_cas.expiry_time = src->expiry_time;
            dst->blob_cas.new_content = Z_STRVAL_P(src->content);
            dst->blob_cas.new_content_size = Z_STRLEN_P(src->content);

            dst->blob_cas.comparand = Z_STRVAL_P(src->comparand);
            dst->blob_cas.comparand_size = Z_STRLEN_P(src->comparand);
            break;

        case qdb_op_blob_get_and_update:
            if (!src->content)
                break;

            dst->blob_get_and_update.expiry_time = src->expiry_time;
            dst->blob_get_and_update.new_content = Z_STRVAL_P(src->content);
            dst->blob_get_and_update.new_content_size = Z_STRLEN_P(src->content);
            break;

        default:
            // warning: enumeration value not handled in switch [-Wswitch]
            break;
    }
}

void QdbBatch_copyOperations(zval* zbatch, qdb_operation_t** operations, size_t* operation_count TSRMLS_DC)
{
    size_t i;
    batch_t* batch = (batch_t*)zend_object_store_get_object(zbatch TSRMLS_CC);

    *operation_count = batch->length;
    *operations = emalloc(batch->length * sizeof(qdb_operation_t));
    qdb_init_operations(*operations, *operation_count);

    for (i = 0; i < batch->length; i++)
    {
        convert_operation(&(*operations)[i], &batch->operations[i]);
    }
}

CLASS_METHOD_0(__construct)
{
    this->length = 0;
    this->capacity = 16;
    this->operations = emalloc(this->capacity * sizeof(batch_operation_t));
}

CLASS_METHOD_0(__destruct)
{
    size_t i;

    for (i = 0; i < this->length; i++)
    {
        batch_operation_t* op = &this->operations[i];

        if (op->alias)
            Z_DELREF_P(op->alias);
        if (op->content)
            Z_DELREF_P(op->content);
        if (op->comparand)
            Z_DELREF_P(op->comparand);
    }

    efree(this->operations);
}

CLASS_METHOD_3_1(compareAndSwap, STRING_ARG(alias), STRING_ARG(content), STRING_ARG(comparand), LONG_ARG(expiry))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(content);
    Z_ADDREF_P(comparand);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->comparand = comparand;
    op->content = content;
    op->expiry_time = expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0;
    op->type = qdb_op_blob_cas;
}

CLASS_METHOD_1(get, STRING_ARG(alias))
{
    Z_ADDREF_P(alias);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->type = qdb_op_blob_get;
}

#if 0
CLASS_METHOD_1(getAndRemove, STRING_ARG(alias))
{
    Z_ADDREF_P(alias);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->type = qdb_op_blob_get_and_remove;
}
#endif

CLASS_METHOD_2_1(getAndUpdate, STRING_ARG(alias), STRING_ARG(content), LONG_ARG(expiry))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(content);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->content = content;
    op->expiry_time = expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0;
    op->type = qdb_op_blob_get_and_update;
}

CLASS_METHOD_2_1(put, STRING_ARG(alias), STRING_ARG(content), LONG_ARG(expiry))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(content);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->content = content;
    op->expiry_time = expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0;
    op->type = qdb_op_blob_put;
}

#if 0
CLASS_METHOD_1(remove, STRING_ARG(alias))
{
    Z_ADDREF_P(alias);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->type = qdb_op_remove;
}

CLASS_METHOD_2(removeIf, STRING_ARG(alias), STRING_ARG(comparand))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(comparand);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->comparand = comparand;
    op->type = qdb_op_blob_remove_if;
}
#endif

CLASS_METHOD_2_1(update, STRING_ARG(alias), STRING_ARG(content), LONG_ARG(expiry))
{
    Z_ADDREF_P(alias);
    Z_ADDREF_P(content);

    batch_operation_t* op = alloc_operation(this);
    op->alias = alias;
    op->content = content;
    op->expiry_time = expiry ? to_expiry_unit(Z_LVAL_P(expiry)) : 0;
    op->type = qdb_op_blob_update;
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(compareAndSwap)
    ADD_METHOD(get)
    // ADD_METHOD(getAndRemove)
    ADD_METHOD(getAndUpdate)
    ADD_METHOD(put)
    // ADD_METHOD(remove)
    // ADD_METHOD(removeIf)
    ADD_METHOD(update)
END_CLASS_MEMBERS()

#include "class_definition.i"
