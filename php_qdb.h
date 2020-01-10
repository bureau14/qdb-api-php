// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef PHP_QUASARDB_H
#define PHP_QUASARDB_H

#define PHP_QUASARDB_EXTNAME "quasardb"
#define PHP_QUASARDB_EXTVER "3.3.0"

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>

extern zend_module_entry quasardb_module_entry;
#define phpext_quasardb_ptr &quasardb_module_entry;

#endif /* PHP_QUASARDB_H */
