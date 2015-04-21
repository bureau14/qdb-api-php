// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h> // include first to avoid conflict with stdint.h
#include <standard/info.h>

#include "php_qdb.h"

#include "src/batch.h"
#include "src/batch_result.h"
#include "src/blob.h"
#include "src/cluster.h"
#include "src/exceptions.h"
#include "src/globals.h"
#include "src/queue.h"
#include "src/settings.h"

static PHP_MINIT_FUNCTION(qdb)
{
    globals_init();
    settings_init(module_number TSRMLS_CC);
    exceptions_init(TSRMLS_C);
    QdbBatch_registerClass(TSRMLS_C);
    QdbBatchResult_registerClass(TSRMLS_C);
    QdbBlob_registerClass(TSRMLS_C);
    QdbCluster_registerClass(TSRMLS_C);
    QdbQueue_registerClass(TSRMLS_C);
    return SUCCESS;
}

static PHP_MSHUTDOWN_FUNCTION(qdb)
{
    settings_shutdown(module_number TSRMLS_CC);
    return SUCCESS;
}

static PHP_MINFO_FUNCTION(qdb)
{
    php_info_print_table_start();
    php_info_print_table_row(2, "qdb php api version", PHP_QDB_EXTVER);
    php_info_print_table_row(2, "qdb client version", qdb_version());
    php_info_print_table_row(2, "qdb client build", qdb_build());
    php_info_print_table_end();
    settings_print_info(zend_module);
}

zend_module_entry qdb_module_entry = {
    STANDARD_MODULE_HEADER,
    PHP_QDB_EXTNAME,
    NULL,        /* Functions */
    PHP_MINIT(qdb),
    PHP_MSHUTDOWN(qdb),
    NULL,        /* RINIT */
    NULL,        /* RSHUTDOWN */
    PHP_MINFO(qdb),
    PHP_QDB_EXTVER,
    STANDARD_MODULE_PROPERTIES
};

#ifdef COMPILE_DL_QDB
ZEND_GET_MODULE(qdb)
#endif
