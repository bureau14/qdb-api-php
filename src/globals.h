// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h> // include first to avoid conflict with stdint.h 

ZEND_BEGIN_MODULE_GLOBALS(qdb)
    int log_level;
ZEND_END_MODULE_GLOBALS(qdb)

#ifdef ZTS
    #define QDB_G(v) TSRMG(qdb_globals_id, zend_qdb_globals*, v)
    extern int qdb_globals_id;
#else
    #define QDB_G(v) (qdb_globals.v)
    extern zend_qdb_globals qdb_globals;
#endif 

void globals_init();
