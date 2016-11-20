// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_EXPIRABLE_ENTRY
#define QDB_EXPIRABLE_ENTRY

#include <php.h>  // include first to avoid conflict with stdint.h
#include <qdb/client.h>

void QdbExpirableEntry_registerClass(TSRMLS_D);

void QdbExpirableEntry_constructInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC);

static qdb_time_t to_expiry_unit(long long seconds)
{
    return (qdb_time_t)1000ll * (qdb_time_t)seconds;
}

#endif /* QDB_EXPIRABLE_ENTRY */
