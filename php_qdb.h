// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.
 
#ifndef PHP_QDB_H
#define PHP_QDB_H

#define PHP_QDB_EXTNAME  "qdb"
#define PHP_QDB_EXTVER   "2.0.0"

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>

extern zend_module_entry qdb_module_entry;
#define phpext_qdb_ptr &qdb_module_entry;

#endif /* PHP_QDB_H */
