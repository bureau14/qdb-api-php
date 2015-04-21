// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h> // include first to avoid conflict with stdint.h 
#include <php_ini.h>

#include "globals.h"
#include "log.h"


int log_level_to_int(const char* level, int def)
{
    const char* levels[] = {
        "detailed", "debug", "info", "warning", "error", "panic"
    };

    int i;
    for (i=0; i<sizeof(levels)/sizeof(levels[0]); i++)
    {
        if (!strcmp(level, levels[i]))
            return i;
    }

    return def;
}

static void log_callback(const char * level, const unsigned long * date, unsigned long pid, unsigned long tid, const char * msg, size_t msg_len)
{
    TSRMLS_FETCH();

    if (log_level_to_int(level,100) < QDB_G(log_level))
        return;

    php_printf("qdb: %02ld/%02ld/%04ld-%02ld:%02ld:%02ld %s: %s\n", date[1], date[2], date[0], date[3], date[4], date[5], level, msg);
}

void log_attach(qdb_handle_t handle)
{
    qdb_set_option(handle, qdb_o_log_callback, log_callback);
}
