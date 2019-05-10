// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_INTEGER_H
#define QDB_INTEGER_H

#include "php_include.h"
#include <qdb/client.h>

void QdbInteger_registerClass();

void QdbInteger_createInstance(zval* destination, qdb_handle_t handle, zval* alias);

#endif /* QDB_INTEGER_H */
