// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_TS_BATCH_COLUMN_INFO_H
#define QDB_TS_BATCH_COLUMN_INFO_H

#include "php_include.h"
#include <qdb/ts.h>

void QdbTsBatchColumnInfo_registerClass();

void QdbTsBatchColumnInfo_make_native_array(HashTable* src, qdb_ts_batch_column_info_t* dst);

#endif /* QDB_TS_BATCH_COLUMN_INFO_H */
