// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_BLOB_H
#define QDB_BLOB_H

#include <zend.h> // include first to avoid conflict with stdint.h

#include <qdb/client.h>

void QdbBlob_registerClass(TSRMLS_D);

void QdbBlob_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC);

#endif /* QDB_BLOB_H */
