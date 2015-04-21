// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h> // include first to avoid conflict with stdint.h 

#include "common_params.h"
#include "exceptions.h"

int check_no_args(int num_args TSRMLS_DC)
{
    if (num_args > 0)
    {
        throw_invalid_argument("Too many arguments, expected none");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_args_1(int num_args, zval** arg1 TSRMLS_DC)
{
    if (num_args < 1)
    {
        throw_invalid_argument("Not enough arguments, expected exactly one");
        return FAILURE;
    }

    if (num_args > 1)
    {
        throw_invalid_argument("Too many arguments, expected exactly one");
        return FAILURE;
    }

    if (zend_parse_parameters(num_args TSRMLS_CC, "z", arg1) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_args_1_1(int num_args, zval** arg1, zval** optarg1 TSRMLS_DC)
{
    *optarg1 = NULL;

    if (num_args < 1)
    {
        throw_invalid_argument("Not enough arguments, expected at leat one");
        return FAILURE;
    }

    if (num_args > 2)
    {
        throw_invalid_argument("Too many arguments, expected at most two");
        return FAILURE;
    }

    if (zend_parse_parameters(num_args TSRMLS_CC, "z|z", arg1, optarg1) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}


int parse_args_2(int num_args, zval** arg1, zval** arg2 TSRMLS_DC)
{
    if (num_args < 2)
    {
        throw_invalid_argument("Not enough arguments, expected exactly two");
        return FAILURE;
    }

    if (num_args > 2)
    {
        throw_invalid_argument("Too many arguments, expected exactly two");
        return FAILURE;
    }

    if (zend_parse_parameters(num_args TSRMLS_CC, "zz", arg1, arg2) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}


int parse_args_2_1(int num_args, zval** arg1, zval** arg2, zval** optarg1 TSRMLS_DC)
{
    *optarg1 = NULL;

    if (num_args < 2)
    {
        throw_invalid_argument("Not enough arguments, expected at leat two");
        return FAILURE;
    }

    if (num_args > 3)
    {
        throw_invalid_argument("Too many arguments, expected at most three");
        return FAILURE;
    }

    if (zend_parse_parameters(num_args TSRMLS_CC, "zz|z", arg1, arg2, optarg1) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_args_3_1(int num_args, zval** arg1, zval** arg2, zval** arg3, zval** optarg1 TSRMLS_DC)
{
    *optarg1 = NULL;

    if (num_args < 3)
    {
        throw_invalid_argument("Not enough arguments, expected at leat three");
        return FAILURE;
    }

    if (num_args > 4)
    {
        throw_invalid_argument("Too many arguments, expected at most four");
        return FAILURE;
    }

    if (zend_parse_parameters(num_args TSRMLS_CC, "zzz|z", arg1, arg2, arg3, optarg1) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}
