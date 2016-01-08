// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "class_definition.h"
#include "connection.h"
#include "QdbBatch.h"
#include "QdbBatchResult.h"
#include "QdbBlob.h"
#include "QdbCluster.h"
#include "QdbDeque.h"
#include "QdbEntryFactory.h"
#include "QdbHashSet.h"
#include "QdbInteger.h"
#include "QdbTag.h"

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

CLASS_METHOD_1(blob, STRING_ARG(alias))
{
    QdbBlob_createInstance(return_value, this->handle, alias TSRMLS_CC);
}

CLASS_METHOD_1(deque, STRING_ARG(alias))
{
    QdbDeque_createInstance(return_value, this->handle, alias TSRMLS_CC);
}

CLASS_METHOD_1(entry, STRING_ARG(alias))
{
    qdb_error_t error = QdbEntryFactory_createFromAlias(return_value, this->handle, Z_STRVAL_P(alias) TSRMLS_CC);

    if (error)
        throw_qdb_error(error);
}

CLASS_METHOD_1(hashSet, STRING_ARG(alias))
{
    QdbHashSet_createInstance(return_value, this->handle, alias TSRMLS_CC);
}

CLASS_METHOD_1(integer, STRING_ARG(alias))
{
    QdbInteger_createInstance(return_value, this->handle, alias TSRMLS_CC);
}

CLASS_METHOD_0(purgeAll)
{
    qdb_error_t error = qdb_purge_all(this->handle);

    if (error)
        throw_qdb_error(error);
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
    ADD_METHOD(blob)
    ADD_METHOD(deque)
    ADD_METHOD(entry)
    ADD_METHOD(hashSet)
    ADD_METHOD(integer)
    ADD_METHOD(purgeAll)
    ADD_METHOD(runBatch)
    ADD_METHOD(tag)
END_CLASS_MEMBERS()

#include "class_definition.i"
