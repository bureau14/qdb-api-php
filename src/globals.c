// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "globals.h"
#include "log.h"

ZEND_DECLARE_MODULE_GLOBALS(qdb);

static ZEND_MODULE_GLOBALS_CTOR_D(qdb)
{
    qdb_globals->log_level = 0;
    qdb_globals->persistent = 1;
}

void globals_init()
{
    ZEND_INIT_MODULE_GLOBALS(qdb, ZEND_MODULE_GLOBALS_CTOR_N(qdb), NULL);
}
