// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_TS_COLUMN_INFO_H
#define QDB_TS_COLUMN_INFO_H

#include <zend.h>  // include first to avoid conflict with stdint.h
#include <qdb/ts.h>

void QdbTsColumnInfo_registerClass(TSRMLS_D);

void QdbTsColumnInfo_make_native_array(HashTable* src, qdb_ts_column_info_t* dst);

#endif /* QDB_TS_COLUMN_INFO_H */
