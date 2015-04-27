// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h> // include first to avoid conflict with stdint.h

#include "class_definition.h"
#include "exceptions.h"
#include "log.h"
#include "QdbBatch.h"
#include "QdbBatchResult.h"
#include "QdbBlob.h"
#include "QdbCluster.h"
#include "QdbInteger.h"
#include "QdbQueue.h"

#include <qdb/client.h>

#define class_name      QdbCluster
#define class_storage   cluster_t

typedef struct {
    zend_object std;
    qdb_handle_t handle;
} cluster_t;

static int convert_one_node(qdb_remote_node_t *node, HashTable *hnode TSRMLS_DC)
{
    zval **zaddress;

    if (zend_hash_find(hnode, "address", sizeof("address"), (void**)&zaddress) == FAILURE) {
        throw_invalid_argument("Node address is missing");
        return FAILURE;
    }

    zval **zport = 0;
    zend_hash_find(hnode, "port", sizeof("port"), (void**)&zport);

    node->address = Z_STRVAL_PP(zaddress);
    node->port = zport ? Z_LVAL_PP(zport) : 2836;

    return SUCCESS;
}

static int convert_each_node(qdb_remote_node_t *nodes, HashTable *hnodes TSRMLS_DC)
{
    HashPosition pointer;
    zval **znode;

    zend_hash_internal_pointer_reset_ex(hnodes, &pointer);

    while(zend_hash_get_current_data_ex(hnodes, (void**) &znode, &pointer) == SUCCESS)
    {
        if (convert_one_node(nodes++, Z_ARRVAL_PP(znode) TSRMLS_CC) == FAILURE)
            return FAILURE;

        zend_hash_move_forward_ex(hnodes, &pointer);
    }

    return SUCCESS;
}

int QdbCluster_convertNodes(zval *znodes, qdb_remote_node_t** nodes, int* node_count TSRMLS_DC)
{
    *nodes = NULL;
    *node_count = 0;

    HashTable *hnodes = Z_ARRVAL_P(znodes);

    *node_count = zend_hash_num_elements(hnodes);

    if (*node_count == 0)
    {
        throw_invalid_argument("Node list is empty.");
        return FAILURE;
    }

    *nodes = ecalloc(*node_count, sizeof(qdb_remote_node_t));

    return convert_each_node(*nodes, hnodes TSRMLS_CC);
}


void QdbCluster_logNodes(qdb_remote_node_t* nodes, int node_count TSRMLS_DC)
{
    int i;
    for(i=0; i<node_count; i++) {
        if(nodes[i].error){
            char buffer[32];
            qdb_error(nodes[i].error, buffer, sizeof(buffer));
            zend_error(E_WARNING, "Connection to %s (port %d) failed: %s", nodes[i].address, nodes[i].port, buffer);
        }
    }
}

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


BEGIN_CLASS_METHOD_1(integer, STRING_ARG(alias))
{
    QdbInteger_createInstance(return_value, this->handle, alias TSRMLS_CC);
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
    ADD_METHOD(integer)
    ADD_METHOD(queue)
    ADD_METHOD(runBatch)
END_CLASS_MEMBERS()

#include "class_definition.c"
