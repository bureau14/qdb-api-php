// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef PHP_QUASARDB_H
#define PHP_QUASARDB_H

#define PHP_QUASARDB_EXTNAME  "quasardb"
#define PHP_QUASARDB_EXTVER   "2.0.0"

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>

extern zend_module_entry quasardb_module_entry;
#define phpext_quasardb_ptr &quasardb_module_entry;

#endif /* PHP_QUASARDB_H */
