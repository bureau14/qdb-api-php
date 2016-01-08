// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_TAG_H
#define QDB_TAG_H

#include <zend.h>  // include first to avoid conflict with stdint.h

#include <qdb/client.h>

void QdbTag_registerClass(TSRMLS_D);

void QdbTag_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC);

int QdbTag_isInstance(zval* object TSRMLS_DC);

#endif /* QDB_TAG_H */
