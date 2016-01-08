// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_INTEGER_H
#define QDB_INTEGER_H

#include <zend.h>  // include first to avoid conflict with stdint.h

#include <qdb/client.h>

void QdbInteger_registerClass(TSRMLS_D);

void QdbInteger_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC);

#endif /* QDB_INTEGER_H */
