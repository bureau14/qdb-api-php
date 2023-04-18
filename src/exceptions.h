// Copyright (c) 2009-2023, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_EXCEPTIONS_H
#define QDB_EXCEPTIONS_H

#include "php_include.h"
#include <qdb/client.h>

void exceptions_init();

void throw_qdb_error_(qdb_error_t error);
#define throw_qdb_error(x) throw_qdb_error_(x)

void throw_invalid_argument_(const char* message);
#define throw_invalid_argument(x) throw_invalid_argument_(x)

void throw_out_of_range_(const char* message);
#define throw_out_of_range(x) throw_out_of_range_(x)

void throw_out_of_bounds_(const char* message);
#define throw_out_of_bounds(x) throw_out_of_bounds_(x)

void throw_bad_function_call_(const char* message);
#define throw_bad_function_call(x) throw_bad_function_call_(x)

#endif /* QDB_EXCEPTIONS_H */
