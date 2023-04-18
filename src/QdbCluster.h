// Copyright (c) 2009-2023, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_CLUSTER_H
#define QDB_CLUSTER_H

#include "php_include.h"
#include <qdb/client.h>

void QdbCluster_registerClass();
int  QdbCluster_convertNodes(zval* znodes, qdb_remote_node_t** nodes, int* node_count);
void QdbCluster_logNodes(qdb_remote_node_t* nodes, int node_count);

#endif /* QDB_CLUSTER_H */
