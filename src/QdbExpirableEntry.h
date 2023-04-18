// Copyright (c) 2009-2023, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_EXPIRABLE_ENTRY
#define QDB_EXPIRABLE_ENTRY

#include "php_include.h"
#include <qdb/client.h>

void QdbExpirableEntry_registerClass();

void QdbExpirableEntry_constructInstance(zval* destination, qdb_handle_t handle, zval* alias);

qdb_time_t to_expiry_unit(long long seconds);

#endif /* QDB_EXPIRABLE_ENTRY */
