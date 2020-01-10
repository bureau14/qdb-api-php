// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_SETTINGS_H
#define QDB_SETTINGS_H

#include "php_include.h"

void settings_init(int module_number);
void settings_shutdown(int module_number);
void settings_print_info(zend_module_entry* module);

#endif /* QDB_SETTINGS_H */
