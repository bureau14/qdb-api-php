// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_ENTRY_COLLECTION_H
#define QDB_ENTRY_COLLECTION_H

#include <zend.h>  // include first to avoid conflict with stdint.h

#include <qdb/client.h>

void QdbEntryCollection_registerClass();

void QdbEntryCollection_createInstance(
    zval* destination, qdb_handle_t handle, const char** entries, size_t entries_count);

#endif /* QDB_ENTRY_COLLECTION_H */
