// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h> // include first to avoid conflict with stdint.h

#include <stdio.h> // for sprintff

#include "args_parser.h"
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

static int check_arg_count(int actual, int min, int max TSRMLS_DC)
{
    if (min <= actual && actual <= max)
        return SUCCESS;

    char message[64];

    if (actual < min)
    {
        if (min == max)
            sprintf(message, "Not enough arguments, expected exactly %d", min);
        else
            sprintf(message, "Not enough arguments, expected at least %d", min);
    }
    else
    {
        if (min == max)
            sprintf(message, "Too many arguments, expected exactly %d", max);
        else
            sprintf(message, "Too many arguments, expected at most %d", max);
    }

    throw_invalid_argument(message);
    return FAILURE;
}

int parse_args_1(int num_args, zval** arg1 TSRMLS_DC)
{
    if (check_arg_count(num_args, 1, 1 TSRMLS_CC) == FAILURE)
        return FAILURE;

    if (zend_parse_parameters(num_args TSRMLS_CC, "z", arg1) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_args_1_1(int num_args, zval** arg1, zval** optarg1 TSRMLS_DC)
{
    if (check_arg_count(num_args, 1, 2 TSRMLS_CC) == FAILURE)
        return FAILURE;

    *optarg1 = NULL;

    if (zend_parse_parameters(num_args TSRMLS_CC, "z|z", arg1, optarg1) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_args_2(int num_args, zval** arg1, zval** arg2 TSRMLS_DC)
{
    if (check_arg_count(num_args, 2, 2 TSRMLS_CC) == FAILURE)
        return FAILURE;

    if (zend_parse_parameters(num_args TSRMLS_CC, "zz", arg1, arg2) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_args_2_1(int num_args, zval** arg1, zval** arg2, zval** optarg1 TSRMLS_DC)
{
    if (check_arg_count(num_args, 2, 3 TSRMLS_CC) == FAILURE)
        return FAILURE;

    *optarg1 = NULL;

    if (zend_parse_parameters(num_args TSRMLS_CC, "zz|z", arg1, arg2, optarg1) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_args_3_1(int num_args, zval** arg1, zval** arg2, zval** arg3, zval** optarg1 TSRMLS_DC)
{
    if (check_arg_count(num_args, 3, 4 TSRMLS_CC) == FAILURE)
        return FAILURE;

    *optarg1 = NULL;

    if (zend_parse_parameters(num_args TSRMLS_CC, "zzz|z", arg1, arg2, arg3, optarg1) == FAILURE)
    {
        throw_invalid_argument("Invalid arguments");
        return FAILURE;
    }

    return SUCCESS;
}
