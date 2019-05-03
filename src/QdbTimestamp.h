// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_TIMESTAMP_H
#define QDB_TIMESTAMP_H

#include <zend.h>  // include first to avoid conflict with stdint.h
#include <qdb/client.h>

void QdbTimestamp_registerClass(TSRMLS_D);

extern zend_class_entry* ce_QdbTimestamp;

#endif /* QDB_TIMESTAMP_H */
