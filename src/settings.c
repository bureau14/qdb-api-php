// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#include "globals.h"
#include "log.h"
#include "settings.h"

static ZEND_INI_MH(OnUpdateLogLevel)
{
#ifndef ZTS
    char* base = (char*)mh_arg2;
#else
    char* base = (char*)ts_resource(*((int*)mh_arg2));
#endif

    int* log_level = (int*)(base + (size_t)mh_arg1);
    *log_level = log_level_from_name(new_value->val);

    return SUCCESS;
}

PHP_INI_BEGIN()
STD_PHP_INI_ENTRY(
    "quasardb.log_level", "panic", PHP_INI_ALL, OnUpdateLogLevel, log_level, zend_qdb_globals, qdb_globals)
STD_PHP_INI_ENTRY("quasardb.persistent", "1", PHP_INI_ALL, OnUpdateBool, persistent, zend_qdb_globals, qdb_globals)
PHP_INI_END()

void settings_init(int module_number)
{
    REGISTER_INI_ENTRIES();
}

void settings_shutdown(int module_number)
{
    UNREGISTER_INI_ENTRIES();
}

void settings_print_info(zend_module_entry* module)
{
    display_ini_entries(module);
}
