
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

#include "settings.h"

#include "globals.h"
#include "log.h"

static ZEND_INI_MH(OnUpdateLogLevel)
{
#ifndef ZTS
    char *base = (char*)mh_arg2;
#else
    char *base = (char*)ts_resource(*((int*)mh_arg2));
#endif

    int* log_level = (int*) (base+(size_t) mh_arg1);
    *log_level = log_level_to_int(new_value, 0);

    return SUCCESS;
}

PHP_INI_BEGIN()
    STD_PHP_INI_ENTRY("qdb.log_level", "panic", PHP_INI_ALL, OnUpdateLogLevel, log_level, zend_qdb_globals, qdb_globals)
PHP_INI_END()

void settings_init(int module_number TSRMLS_DC)
{
    REGISTER_INI_ENTRIES();
}

void settings_shutdown(int module_number TSRMLS_DC)
{
    UNREGISTER_INI_ENTRIES();
}

void settings_print_info(zend_module_entry *module)
{
    display_ini_entries(module);
}
