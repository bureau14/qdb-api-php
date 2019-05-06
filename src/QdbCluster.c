// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "QdbBatch.h"
#include "QdbBatchResult.h"
#include "QdbBlob.h"
#include "QdbCluster.h"
#include "QdbEntryFactory.h"
#include "QdbInteger.h"
#include "QdbQuery.h"
#include "QdbTag.h"
#include "QdbTsBatchTable.h"
#include "QdbTsBatchColumnInfo.h"
#include "class_definition.h"
#include "connection.h"

#include <qdb/client.h>

#define class_name QdbCluster
#define class_storage cluster_t

typedef struct
{
    zend_object std;
    qdb_handle_t handle;
} cluster_t;


CLASS_METHOD_1(__construct, STRING_ARG(uri))
{
    this->handle = connection_open(uri TSRMLS_CC);
}

CLASS_METHOD_0(__destruct)
{
    connection_close(this->handle TSRMLS_CC);
}

CLASS_METHOD_1(makeBatchTable, ARRAY_ARG(columns_info))
{
    HashTable* range = Z_ARRVAL_P(columns_info);
    long columns_cnt = zend_hash_num_elements(range);

    if (columns_cnt <= 0) throw_invalid_argument
        ("cluster.make_batch_table(columns_info) must get at least one column info");
    // Guard against the stack allocation.
    else if (columns_cnt > 10000) throw_invalid_argument
        ("cluster.make_batch_table(columns_info) cannot use more than 10000 one column info");

    // Allocate data on stack on free the memory automatically when exceptions are occuring.
    qdb_ts_batch_column_info_t* info_copy = alloca(columns_cnt * sizeof(qdb_ts_batch_column_info_t));
    QdbTsBatchColumnInfo_make_native_array(range, info_copy);

    QdbTsBatchTable_createInstance(return_value, this->handle, info_copy, columns_cnt);
}

CLASS_METHOD_1(makeQuery, STRING_ARG(query))
{
    QdbQuery_createInstance(return_value, this->handle, Z_STRVAL_P(query));
}

CLASS_METHOD_1(blob, STRING_ARG(alias))
{
    QdbBlob_createInstance(return_value, this->handle, alias TSRMLS_CC);
}

CLASS_METHOD_1(entry, STRING_ARG(alias))
{
    qdb_error_t error = QdbEntryFactory_createFromAlias(return_value, this->handle, Z_STRVAL_P(alias) TSRMLS_CC);

    if (error) throw_qdb_error(error);
}

CLASS_METHOD_1(integer, STRING_ARG(alias))
{
    QdbInteger_createInstance(return_value, this->handle, alias TSRMLS_CC);
}

CLASS_METHOD_0_1(purgeAll, LONG_ARG(timeout))
{
    int timeout_value = timeout ? Z_LVAL_P(timeout) : 300;

    if (timeout_value <= 0)
    {
        throw_invalid_argument("Argument timeout must be a positive (non-zero) integer");
        return;
    }
    qdb_error_t error = qdb_purge_all(this->handle, timeout_value);

    if (error)
    {
        throw_qdb_error(error);
    }
}

CLASS_METHOD_1(runBatch, OBJECT_ARG(QdbBatch, batch))
{
    qdb_operation_t* ops;
    size_t ops_count;

    QdbBatch_copyOperations(batch, &ops, &ops_count TSRMLS_CC);

    qdb_run_batch(this->handle, ops, ops_count);

    QdbBatchResult_createInstance(return_value, this->handle, ops, ops_count TSRMLS_CC);
}

CLASS_METHOD_1(tag, STRING_ARG(alias))
{
    QdbTag_createInstance(return_value, this->handle, alias TSRMLS_CC);
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(makeBatchTable)
    ADD_METHOD(makeQuery)
    ADD_METHOD(blob)
    ADD_METHOD(entry)
    ADD_METHOD(integer)
    ADD_METHOD(purgeAll)
    ADD_METHOD(runBatch)
    ADD_METHOD(tag)
END_CLASS_MEMBERS()

#include "class_definition.i"
