/* ----------------------------------------------------------------------------
 * This file was automatically generated by SWIG (http://www.swig.org).
 * Version 2.0.7
 * 
 * This file is not intended to be easily readable and contains a number of 
 * coding conventions designed to improve portability and efficiency. Do not make
 * changes to this file unless you know what you are doing--modify the SWIG 
 * interface file instead. 
 * ----------------------------------------------------------------------------- */

#ifndef PHP_QDB_H
#define PHP_QDB_H

extern zend_module_entry qdb_module_entry;
#define phpext_qdb_ptr &qdb_module_entry

#ifdef PHP_WIN32
# define PHP_QDB_API __declspec(dllexport)
#else
# define PHP_QDB_API
#endif

#ifdef ZTS
#include "TSRM.h"
#endif

PHP_MINIT_FUNCTION(qdb);
PHP_MSHUTDOWN_FUNCTION(qdb);
PHP_RINIT_FUNCTION(qdb);
PHP_RSHUTDOWN_FUNCTION(qdb);
PHP_MINFO_FUNCTION(qdb);

ZEND_NAMED_FUNCTION(_wrap_qdb_open_tcp);
ZEND_NAMED_FUNCTION(_wrap_qdb_close);
ZEND_NAMED_FUNCTION(_wrap_qdb_connect);
ZEND_NAMED_FUNCTION(_wrap_qdb_get_buffer);
ZEND_NAMED_FUNCTION(_wrap_qdb_put);
ZEND_NAMED_FUNCTION(_wrap_qdb_update);
ZEND_NAMED_FUNCTION(_wrap_qdb_remove);
ZEND_NAMED_FUNCTION(_wrap_qdb_remove_all);
#endif /* PHP_QDB_H */