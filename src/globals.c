// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h> // include first to avoid conflict with stdint.h 

#include "globals.h"
#include "log.h"

ZEND_DECLARE_MODULE_GLOBALS(qdb);

static void globals_ctor(zend_qdb_globals *globals) 
{
    globals->log_level = 0;
}

void globals_init()
{
    ZEND_INIT_MODULE_GLOBALS(qdb, globals_ctor, NULL);
}
