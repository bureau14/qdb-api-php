// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "batch.h"

static void extract_one_operation(qdb_operation_t *dst, batch_operation_t* src);

void QdbBatch_copyOperations(zval* zbatch, qdb_operation_t** operations, size_t* operation_count TSRMLS_DC)
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
