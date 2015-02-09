
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

#include "log.h"

#include <php.h>
#include <php_ini.h>

#include "globals.h"


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
