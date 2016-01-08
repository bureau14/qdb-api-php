// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <php_ini.h>

#include "globals.h"
#include "log.h"


struct level_name
{
    qdb_log_level_t level;
    const char* name;
};

static struct level_name _level_names[] = {{qdb_log_detailed, "detailed"},
    {qdb_log_debug, "debug"},
    {qdb_log_info, "info"},
    {qdb_log_warning, "warning"},
    {qdb_log_error, "error"},
    {qdb_log_panic, "panic"}};

static const char* log_level_name(qdb_log_level_t level)
{
    int i;

    for (i = 0; i < sizeof(_level_names) / sizeof(_level_names[0]); i++)
    {
        if (_level_names[i].level == level)
            return _level_names[i].name;
    }

    return "???";
}

qdb_log_level_t log_level_from_name(const char* name)
{
    int i;

    for (i = 0; i < sizeof(_level_names) / sizeof(_level_names[0]); i++)
    {
        if (!strcasecmp(_level_names[i].name, name))
            return _level_names[i].level;
    }

    return 0;
}

static void log_callback(qdb_log_level_t level, const unsigned long* date, unsigned long pid, unsigned long tid,
    const char* msg, size_t msg_len)
{
    TSRMLS_FETCH();

    if (level < QDB_G(log_level))
        return;

    php_printf("qdb: %02ld/%02ld/%04ld-%02ld:%02ld:%02ld %s: %s\n",
        date[1],
        date[2],
        date[0],
        date[3],
        date[4],
        date[5],
        log_level_name(level),
        msg);
}

void log_attach(void)
{
    qdb_option_add_log_callback(log_callback);
}
