// Copyright (c) 2009-2019, quasardb SAS
// All rights reserved.

#ifndef QDB_INTEGER_H
#define QDB_INTEGER_H

#include "php_include.h"
#include <qdb/client.h>

extern zend_class_entry* ce_QdbInteger;

void QdbInteger_registerClass();

void QdbInteger_createInstance(zval* destination, qdb_handle_t handle, zval* alias);

#endif /* QDB_INTEGER_H */
