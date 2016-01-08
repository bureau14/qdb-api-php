// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_ENTRY
#define QDB_ENTRY

#include <php.h>  // include first to avoid conflict with stdint.h
#include <qdb/client.h>

typedef struct
{
    zend_object std;
    qdb_handle_t handle;
    zval* alias;
} entry_t;

void QdbEntry_registerClass(TSRMLS_D);

void QdbEntry_constructInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC);

zval* QdbEntry_getAlias(zval* object TSRMLS_DC);

int QdbEntry_isInstance(zval* object TSRMLS_DC);

#endif /* QDB_ENTRY */
