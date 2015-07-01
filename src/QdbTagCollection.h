// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_TAG_COLLECTION_H
#define QDB_TAG_COLLECTION_H

#include <zend.h> // include first to avoid conflict with stdint.h

#include <qdb/client.h>

void QdbTagCollection_registerClass(TSRMLS_D);

void QdbTagCollection_createInstance(zval* destination, qdb_handle_t handle, const char ** tags, size_t tags_count TSRMLS_DC);

#endif /* QDB_TAG_COLLECTION_H */
