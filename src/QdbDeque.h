// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_DEQUE_H
#define QDB_DEQUE_H

#include <zend.h>  // include first to avoid conflict with stdint.h

#include <qdb/client.h>

void QdbDeque_registerClass(TSRMLS_D);

void QdbDeque_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC);

#endif /* QDB_DEQUE_H */
