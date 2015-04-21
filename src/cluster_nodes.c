// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "cluster.h"
#include "exceptions.h"

static int  extract_each_node(qdb_remote_node_t *nodes, HashTable *hnodes TSRMLS_DC);
static int  extract_one_node(qdb_remote_node_t *node, HashTable *hnode TSRMLS_DC);

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

    return extract_each_node(*nodes, hnodes TSRMLS_CC);
}

static int extract_each_node(qdb_remote_node_t *nodes, HashTable *hnodes TSRMLS_DC)
{
    HashPosition pointer;
    zval **znode;

    zend_hash_internal_pointer_reset_ex(hnodes, &pointer);

    while(zend_hash_get_current_data_ex(hnodes, (void**) &znode, &pointer) == SUCCESS)
    {
        if (extract_one_node(nodes++, Z_ARRVAL_PP(znode) TSRMLS_CC) == FAILURE)
            return FAILURE;

        zend_hash_move_forward_ex(hnodes, &pointer);
    }

    return SUCCESS;
}

static int extract_one_node(qdb_remote_node_t *node, HashTable *hnode TSRMLS_DC)
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