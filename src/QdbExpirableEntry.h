// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_EXPIRABLE_ENTRY
#define QDB_EXPIRABLE_ENTRY

#include <php.h>  // include first to avoid conflict with stdint.h
#include <qdb/client.h>

void QdbExpirableEntry_registerClass();

void QdbExpirableEntry_constructInstance(zval* destination, qdb_handle_t handle, zval* alias);

qdb_time_t to_expiry_unit(long long seconds);

#endif /* QDB_EXPIRABLE_ENTRY */
