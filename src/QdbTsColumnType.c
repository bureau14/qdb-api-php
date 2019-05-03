// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <qdb/ts.h>
#include "class_definition.h"

struct zval_empty_class_t { char unused; };

#define class_name QdbTsColumnType
#define class_storage struct zval_empty_class_t

extern zend_class_entry* ce_QdbTsColumnType;

int init_column_types() {
    return zend_declare_class_constant_long(ce_QdbTsColumnType, "BLOB", 4, qdb_ts_column_blob TSRMLS_DC) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbTsColumnType, "DOUBLE", 6, qdb_ts_column_double TSRMLS_DC) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbTsColumnType, "INT64", 5, qdb_ts_column_int64 TSRMLS_DC) == SUCCESS
        && zend_declare_class_constant_long(ce_QdbTsColumnType, "TIMESTAMP", 9, qdb_ts_column_timestamp TSRMLS_DC) == SUCCESS;
}

BEGIN_CLASS_MEMBERS()
END_CLASS_MEMBERS()

#include "class_definition.i"
