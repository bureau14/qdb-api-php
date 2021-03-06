// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

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
