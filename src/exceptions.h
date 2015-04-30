// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_EXCEPTIONS_H
#define QDB_EXCEPTIONS_H

#include <php.h> // include first to avoid conflict with stdint.h

#include <qdb/client.h>

void exceptions_init(TSRMLS_D);

void throw_qdb_error_(qdb_error_t error TSRMLS_DC);
#define throw_qdb_error(x) throw_qdb_error_(x TSRMLS_CC)

void throw_invalid_argument_(const char* message TSRMLS_DC);
#define throw_invalid_argument(x) throw_invalid_argument_(x TSRMLS_CC)

void throw_out_of_range_(const char* message TSRMLS_DC);
#define throw_out_of_range(x) throw_out_of_range_(x TSRMLS_CC)

void throw_out_of_bounds_(const char* message TSRMLS_DC);
#define throw_out_of_bounds(x) throw_out_of_bounds_(x TSRMLS_CC)

void throw_bad_function_call_(const char* message TSRMLS_DC);
#define throw_bad_function_call(x) throw_bad_function_call_(x TSRMLS_CC)

#endif /* QDB_EXCEPTIONS_H */
