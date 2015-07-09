// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "QdbBatchResult.h"
#include "class_definition.h"
#include "exceptions.h"

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

static void getOperationResult(zval* return_value, qdb_operation_t* op TSRMLS_DC);


void QdbBatchResult_createInstance(zval* destination, qdb_handle_t handle, qdb_operation_t * operations, size_t operations_count TSRMLS_DC)
{
    batch_result_t* this;

    object_init_ex(destination, ce_QdbBatchResult);
    this = (batch_result_t*)zend_object_store_get_object(destination TSRMLS_CC);

    this->handle = handle;
    this->operations = operations;
    this->operations_count = operations_count;
}


BEGIN_CLASS_METHOD_0(__destruct)
{
    qdb_free_operations(this->handle, this->operations, this->operations_count);
    efree(this->operations);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(count) // inherited from Countable
{
    RETURN_LONG(this->operations_count);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(offsetExists, MIXED_ARG(offset)) // inherited from ArrayAccess
{
    long index = Z_LVAL_P(offset);

    RETURN_BOOL(index>=0 && index<(long)this->operations_count);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(offsetGet, MIXED_ARG(offset)) // inherited from ArrayAccess
{
    long index = Z_LVAL_P(offset);

    if (index < 0){
        throw_out_of_range("Offset must be positive of zero");
        return;
    }

    if (index >= (long)this->operations_count) {
        throw_out_of_bounds("Offset must be smaller than the number of operations");
        return;
    }

    getOperationResult(return_value, &this->operations[index] TSRMLS_CC);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_2(offsetSet, MIXED_ARG(offset), MIXED_ARG(value)) // inherited from ArrayAccess
{
    UNUSED(this);
    UNUSED(offset);
    UNUSED(value);
    throw_bad_function_call("Batch result is read-only");
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(offsetUnset, MIXED_ARG(offset)) // inherited from ArrayAccess
{
    UNUSED(this);
    UNUSED(offset);
    throw_bad_function_call("Batch result is read-only");
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(count)
    ADD_METHOD(offsetExists)
    ADD_METHOD(offsetGet)
    ADD_METHOD(offsetSet)
    ADD_METHOD(offsetUnset)
END_CLASS_MEMBERS()

#include "class_definition.i"

static void getCompareAndSwapResult(zval* return_value, qdb_operation_t * op TSRMLS_DC)
{
    switch (op->error)
    {
        case qdb_e_ok:
            RETVAL_NULL();
            break;

        case qdb_e_unmatched_content:
            RETVAL_STRINGL(op->result, op->result_size, /*duplicate=*/1);
            break;

        default:
            throw_qdb_error(op->error);
            break;
    }
}

static void getRemoveIfResult(zval* return_value, qdb_operation_t* op TSRMLS_DC)
{
    switch (op->error)
    {
        case qdb_e_ok:
            RETVAL_TRUE;
            break;

        case qdb_e_unmatched_content:
            RETVAL_FALSE;
            break;

        default:
            throw_qdb_error(op->error);
            break;
    }
}

static void getOtherOperationResult(zval* return_value, qdb_operation_t* op TSRMLS_DC)
{
    switch (op->error)
    {
        case qdb_e_ok:
            if (op->result != NULL)
                RETVAL_STRINGL(op->result, op->result_size, /*duplicate=*/1);
            else
                RETVAL_NULL();
            break;

        default:
            throw_qdb_error(op->error);
            break;
    }
}

static void getOperationResult(zval* return_value, qdb_operation_t* op TSRMLS_DC)
{
    switch(op->type)
    {
        case qdb_op_cas:
            getCompareAndSwapResult(return_value, op TSRMLS_CC);
            break;

        case qdb_op_remove_if:
            getRemoveIfResult(return_value, op TSRMLS_CC);
            break;

        default:
            getOtherOperationResult(return_value, op TSRMLS_CC);
            break;
    }
}
