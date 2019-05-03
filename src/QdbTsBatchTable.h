// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_TS_BATCH_TABLE_H
#define QDB_TS_BATCH_TABLE_H

#include <zend.h>  // include first to avoid conflict with stdint.h
#include <qdb/ts.h>

void QdbTsBatchTable_registerClass(TSRMLS_D);

void QdbTsBatchTable_createInstance(zval* destination,
                                    qdb_handle_t handle,
                                    const qdb_ts_batch_column_info_t* columns,
                                    qdb_size_t column_count TSRMLS_DC);

#endif /* QDB_TS_BATCH_TABLE_H */
