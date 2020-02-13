// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

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
#define class_storage _cluster_t

typedef struct
{
    qdb_handle_t handle;
} _cluster_t;


CLASS_METHOD_1(__construct, STRING_ARG(uri))
{
    this->handle = connection_open(uri);
}

CLASS_METHOD_0(__destruct)
{
    connection_close(this->handle);
}

CLASS_METHOD_2(setUserCredentials, STRING_ARG(userName), STRING_ARG(secretKey))
{
    qdb_error_t err = qdb_option_set_user_credentials(this->handle, Z_STRVAL_P(userName), Z_STRVAL_P(secretKey));
    if (err) throw_qdb_error(err);
}

CLASS_METHOD_1(setClusterPublicKey, STRING_ARG(publicKey))
{
    qdb_error_t err = qdb_option_set_cluster_public_key(this->handle, Z_STRVAL_P(publicKey));
    if (err) throw_qdb_error(err);
}

CLASS_METHOD_1(makeBatchTable, ARRAY_ARG(columns_info))
{
    HashTable* range = Z_ARR_P(columns_info);
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
    QdbBlob_createInstance(return_value, this->handle, alias);
}

CLASS_METHOD_1(entry, STRING_ARG(alias))
{
    qdb_error_t err = QdbEntryFactory_createFromAlias(return_value, this->handle, Z_STRVAL_P(alias));
    if (err) throw_qdb_error(err);
}

CLASS_METHOD_1(integer, STRING_ARG(alias))
{
    QdbInteger_createInstance(return_value, this->handle, alias);
}

CLASS_METHOD_0_1(purgeAll, LONG_ARG(timeout))
{
    int timeout_value = timeout ? Z_LVAL_P(timeout) : 300;

    if (timeout_value <= 0)
    {
        throw_invalid_argument("Argument timeout must be a positive (non-zero) integer");
        return;
    }

    qdb_error_t err = qdb_purge_all(this->handle, timeout_value);
    if (err) throw_qdb_error(err);
}

CLASS_METHOD_1(runBatch, OBJECT_ARG(QdbBatch, batch))
{
    qdb_operation_t* ops;
    size_t ops_count;

    QdbBatch_copyOperations(batch, &ops, &ops_count);

    qdb_run_batch(this->handle, ops, ops_count);

    QdbBatchResult_createInstance(return_value, this->handle, ops, ops_count);
}

CLASS_METHOD_1(tag, STRING_ARG(alias))
{
    QdbTag_createInstance(return_value, this->handle, alias);
}

CLASS_METHOD_0(lastError)
{
    qdb_string_t message;
    qdb_error_t error = qdb_get_last_error(this->handle, NULL, &message);
    if (QDB_FAILURE(error)) throw_qdb_error(error);
    
    RETVAL_STRINGL(message.data, message.length);
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(setUserCredentials)
    ADD_METHOD(setClusterPublicKey)
    ADD_METHOD(makeBatchTable)
    ADD_METHOD(makeQuery)
    ADD_METHOD(blob)
    ADD_METHOD(entry)
    ADD_METHOD(integer)
    ADD_METHOD(purgeAll)
    ADD_METHOD(runBatch)
    ADD_METHOD(tag)
    ADD_METHOD(lastError)
END_CLASS_MEMBERS()

#include "class_definition.i"
