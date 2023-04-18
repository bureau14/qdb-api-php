// Copyright (c) 2009-2023, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_TAG_COLLECTION_H
#define QDB_TAG_COLLECTION_H

#include "php_include.h"
#include <qdb/client.h>

void QdbTagCollection_registerClass();

void QdbTagCollection_createInstance(
    zval* destination, qdb_handle_t handle, const char** tags, size_t tags_count);

#endif /* QDB_TAG_COLLECTION_H */
