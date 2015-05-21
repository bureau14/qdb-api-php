// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_SETTINGS_H
#define QDB_SETTINGS_H

#include <php.h> // include first to avoid conflict with stdint.h 

void settings_init(int module_number TSRMLS_DC);
void settings_shutdown(int module_number TSRMLS_DC);
void settings_print_info(zend_module_entry *module);

#endif /* QDB_SETTINGS_H */