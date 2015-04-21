// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_PARAMETERS_H
#define QDB_PARAMETERS_H

#include <php.h> // include first to avoid conflict with stdint.h 

int check_no_args(int num_args TSRMLS_DC);
int parse_args_1(int num_args, zval** arg1 TSRMLS_DC);
int parse_args_1_1(int num_args, zval** arg1, zval** optarg1 TSRMLS_DC);
int parse_args_2(int num_args, zval** arg1, zval** args2 TSRMLS_DC);
int parse_args_2_1(int num_args, zval** arg1, zval** args2, zval** optarg1 TSRMLS_DC);
int parse_args_3_1(int num_args, zval** arg1, zval** args2, zval** args3, zval** optarg1 TSRMLS_DC);

#endif /* QDB_PARAMETERS_H */
