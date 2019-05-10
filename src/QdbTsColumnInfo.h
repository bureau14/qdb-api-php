// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_TS_COLUMN_INFO_H
#define QDB_TS_COLUMN_INFO_H

#include "php_include.h"
#include <qdb/ts.h>

void QdbTsColumnInfo_registerClass();

void QdbTsColumnInfo_make_native_array(HashTable* src, qdb_ts_column_info_t* dst);

#endif /* QDB_TS_COLUMN_INFO_H */
