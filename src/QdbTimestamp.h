// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_TIMESTAMP_H
#define QDB_TIMESTAMP_H

#include "php_include.h"
#include <qdb/client.h>

void QdbTimestamp_registerClass();

qdb_timespec_t QdbTimestamp_make_timespec(const zval* timestamp);

zval QdbTimestamp_from_timespec(const qdb_timespec_t* ts);

#endif /* QDB_TIMESTAMP_H */
