// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_TAG_H
#define QDB_TAG_H

#include "php_include.h"
#include <qdb/client.h>

extern zend_class_entry* ce_QdbTag;

void QdbTag_registerClass();

void QdbTag_createInstance(zval* destination, qdb_handle_t handle, zval* alias);

int QdbTag_isInstance(zval* object);

#endif /* QDB_TAG_H */
