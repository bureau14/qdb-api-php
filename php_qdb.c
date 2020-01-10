// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
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
#include "src/QdbTag.h"
#include "src/QdbTagCollection.h"
#include "src/QdbTimestamp.h"
#include "src/QdbTsBatchColumnInfo.h"
#include "src/QdbTsBatchTable.h"
#include "src/connection.h"
#include "src/exceptions.h"
#include "src/globals.h"
#include "src/settings.h"

static PHP_MINIT_FUNCTION(quasardb)
{
    globals_init();
    settings_init(module_number);
    exceptions_init();
    connection_init();
    QdbEntry_registerClass();           // <- before derived classes
    QdbExpirableEntry_registerClass();  // <- before derived classes
    QdbEntryCollection_registerClass();
    QdbBatch_registerClass();
    QdbBatchResult_registerClass();
    QdbBlob_registerClass();
    QdbCluster_registerClass();
    QdbInteger_registerClass();
    QdbQuery_registerClass();
    QdbQueryPoint_registerClass();
    QdbTag_registerClass();
    QdbTagCollection_registerClass();
    QdbTimestamp_registerClass();
    QdbTsBatchColumnInfo_registerClass();
    QdbTsBatchTable_registerClass();
    init_query_point_types();
    return SUCCESS;
}

static PHP_MSHUTDOWN_FUNCTION(quasardb)
{
    connection_shutdown();
    settings_shutdown(module_number);
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
