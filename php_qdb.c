
/*


 Copyright (c) 2009-2015, quasardb SAS
 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of quasardb nor the names of its contributors may
      be used to endorse or promote products derived from this software
      without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY QUASARDB AND CONTRIBUTORS ``AS IS'' AND ANY
 EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED. IN NO EVENT SHALL THE REGENTS AND CONTRIBUTORS BE LIABLE FOR ANY
 DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


*/

#include <php.h> // include first to avoid conflict with stdint.h 
#include <standard/info.h>
 
#include "php_qdb.h"

#include "batch.h"
#include "batch_result.h"
#include "cluster.h"
#include "exceptions.h"
#include "globals.h"
#include "queue.h"
#include "settings.h"

static PHP_MINIT_FUNCTION(qdb)
{
    globals_init();
    settings_init(module_number TSRMLS_CC);
    exceptions_init(TSRMLS_C);
    register_QdbBatch(TSRMLS_C);
    register_QdbBatchResult(TSRMLS_C);
    register_QdbCluster(TSRMLS_C);
    register_QdbQueue(TSRMLS_C);
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
