// Copyright (c) 2009-2022, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_GLOBALS_H
#define QDB_GLOBALS_H

#include "php_include.h"
#include <qdb/client.h>
#include <qdb/log.h>

ZEND_BEGIN_MODULE_GLOBALS(qdb)
    qdb_log_level_t log_level;
    zend_bool persistent;
    HashTable connections;
ZEND_END_MODULE_GLOBALS(qdb)

#ifdef ZTS
#define QDB_G(v) TSRMG(qdb_globals_id, zend_qdb_globals*, v)
extern int qdb_globals_id;
#else
#define QDB_G(v) (qdb_globals.v)
extern zend_qdb_globals qdb_globals;
#endif

void globals_init();

#endif
