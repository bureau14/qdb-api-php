// Copyright (c) 2009-2019, quasardb SAS
// All rights reserved.

#ifndef QDB_ENTRY
#define QDB_ENTRY

#include "php_include.h"
#include <qdb/client.h>

typedef struct
{
    qdb_handle_t handle;
    zval alias;
} entry_t;

void QdbEntry_registerClass();

void QdbEntry_constructInstance(zval* destination, qdb_handle_t handle, zval* alias);

zval* QdbEntry_getAlias(zval* object);

int QdbEntry_isInstance(zval* object);

#endif /* QDB_ENTRY */
