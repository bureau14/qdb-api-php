// Copyright (c) 2009-2021, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_TS_BATCH_TABLE_H
#define QDB_TS_BATCH_TABLE_H

#include "php_include.h"
#include <qdb/ts.h>

void QdbTsBatchTable_registerClass();

void QdbTsBatchTable_createInstance(zval* destination,
                                    qdb_handle_t handle,
                                    const qdb_ts_batch_column_info_t* columns,
                                    qdb_size_t column_count);

#endif /* QDB_TS_BATCH_TABLE_H */
