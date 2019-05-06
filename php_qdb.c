// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <standard/info.h>

#include "php_qdb.h"

#include "src/QdbBatch.h"
#include "src/QdbBatchResult.h"
#include "src/QdbBlob.h"
#include "src/QdbCluster.h"
#include "src/QdbEntry.h"
#include "src/QdbEntryCollection.h"
#include "src/QdbExpirableEntry.h"
#include "src/QdbInteger.h"
#include "src/QdbQuery.h"
#include "src/QdbQueryPoint.h"
#include "src/QdbQueryTable.h"
#include "src/QdbTag.h"
#include "src/QdbTagCollection.h"
#include "src/QdbTimestamp.h"
#include "src/QdbTsBatchColumnInfo.h"
#include "src/QdbTsBatchTable.h"
#include "src/QdbTsColumnInfo.h"
#include "src/QdbTsColumnType.h"
#include "src/connection.h"
#include "src/exceptions.h"
#include "src/globals.h"
#include "src/settings.h"

static PHP_MINIT_FUNCTION(quasardb)
{
    globals_init();
    settings_init(module_number TSRMLS_CC);
    exceptions_init(TSRMLS_C);
    connection_init(TSRMLS_C);
    QdbEntry_registerClass(TSRMLS_C);           // <- before derived classes
    QdbExpirableEntry_registerClass(TSRMLS_C);  // <- before derived classes
    QdbEntryCollection_registerClass(TSRMLS_C);
    QdbBatch_registerClass(TSRMLS_C);
    QdbBatchResult_registerClass(TSRMLS_C);
    QdbBlob_registerClass(TSRMLS_C);
    QdbCluster_registerClass(TSRMLS_C);
    QdbInteger_registerClass(TSRMLS_C);
    QdbQuery_registerClass(TSRMLS_C);
    QdbQueryPoint_registerClass(TSRMLS_C);
    QdbQueryTable_registerClass(TSRMLS_C);
    QdbTag_registerClass(TSRMLS_C);
    QdbTagCollection_registerClass(TSRMLS_C);
    QdbTimestamp_registerClass(TSRMLS_C);
    QdbTsBatchColumnInfo_registerClass(TSRMLS_C);
    QdbTsBatchTable_registerClass(TSRMLS_C);
    QdbTsColumnInfo_registerClass(TSRMLS_C);
    QdbTsColumnType_registerClass(TSRMLS_C);
    init_column_types();
    init_query_point_types();
    return SUCCESS;
}

static PHP_MSHUTDOWN_FUNCTION(quasardb)
{
    connection_shutdown(TSRMLS_C);
    settings_shutdown(module_number TSRMLS_CC);
    return SUCCESS;
}

static PHP_MINFO_FUNCTION(quasardb)
{
    php_info_print_table_start();
    php_info_print_table_row(2, "quasardb php extension version", PHP_QUASARDB_EXTVER);
    php_info_print_table_row(2, "quasardb client version", qdb_version());
    php_info_print_table_row(2, "quasardb client build", qdb_build());
    php_info_print_table_end();
    settings_print_info(zend_module);
}

zend_module_entry quasardb_module_entry = {STANDARD_MODULE_HEADER,
    PHP_QUASARDB_EXTNAME,
    NULL, /* Functions */
    PHP_MINIT(quasardb),
    PHP_MSHUTDOWN(quasardb),
    NULL, /* RINIT */
    NULL, /* RSHUTDOWN */
    PHP_MINFO(quasardb),
    PHP_QUASARDB_EXTVER,
    STANDARD_MODULE_PROPERTIES};

#ifdef COMPILE_DL_QUASARDB
ZEND_GET_MODULE(quasardb)
#endif
