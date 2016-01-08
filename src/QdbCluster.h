// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_CLUSTER_H
#define QDB_CLUSTER_H

#include <php.h>

#include <qdb/client.h>

void QdbCluster_registerClass(TSRMLS_D);

int QdbCluster_convertNodes(zval* znodes, qdb_remote_node_t** nodes, int* node_count TSRMLS_DC);

void QdbCluster_logNodes(qdb_remote_node_t* nodes, int node_count TSRMLS_DC);

#endif /* QDB_CLUSTER_H */
