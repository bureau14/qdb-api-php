// Copyright (c) 2009-2023, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_BLOB_H
#define QDB_BLOB_H

#include "php_include.h"
#include <qdb/client.h>

extern zend_class_entry* ce_QdbBlob;

void QdbBlob_registerClass();

void QdbBlob_createInstance(zval* destination, qdb_handle_t handle, zval* alias);

#endif /* QDB_BLOB_H */
