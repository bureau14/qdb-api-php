// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h> // include first to avoid conflict with stdint.h

#include "batch.h"
#include "batch_result.h"
#include "blob.h"
#include "class_definition.h"
#include "cluster.h"
#include "common_params.h"
#include "exceptions.h"
#include "log.h"
#include "queue.h"

#include <qdb/client.h>

#define class_name      QdbCluster
#define class_storage   cluster_t

typedef struct {
    zend_object std;
    qdb_handle_t handle;
} cluster_t;

BEGIN_CLASS_METHOD_1(__construct, ARRAY_ARG(nodes))
{
    int node_count;
    qdb_remote_node_t*qdb_nodes;

    if (QdbCluster_convertNodes(nodes, &qdb_nodes, &node_count TSRMLS_CC) == FAILURE)
        return;

    this->handle = qdb_open_tcp();

    log_attach(this->handle);

    size_t connections = qdb_multi_connect(this->handle, qdb_nodes, node_count);

    QdbCluster_logNodes(qdb_nodes, node_count TSRMLS_CC);

    if (connections <= 0)
        throw_cluster_connection_failed();

    efree(qdb_nodes);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(__destruct)
{
    qdb_close(this->handle);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(blob, STRING_ARG(alias))
{
    QdbBlob_createInstance(return_value, this->handle, alias TSRMLS_CC);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(queue, STRING_ARG(alias))
{
    QdbQueue_createInstance(return_value, this->handle, alias TSRMLS_CC);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(runBatch, OBJECT_ARG(QdbBatch,batch))
{
    qdb_operation_t* ops;
    size_t ops_count;

    QdbBatch_copyOperations(batch, &ops, &ops_count TSRMLS_CC);

    qdb_run_batch(this->handle, ops, ops_count);

    QdbBatchResult_createInstance(return_value, this->handle, ops, ops_count TSRMLS_CC);
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(blob)
    ADD_METHOD(queue)
    ADD_METHOD(runBatch)
END_CLASS_MEMBERS()

#include "class_definition.c"
