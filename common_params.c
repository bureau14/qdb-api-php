
/*


 Copyright (c) 2009-2015, quasardb SAS
 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of quasardb nor the names of its contributors may
      be used to endorse or promote products derived from this software
      without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY QUASARDB AND CONTRIBUTORS ``AS IS'' AND ANY
 EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED. IN NO EVENT SHALL THE REGENTS AND CONTRIBUTORS BE LIABLE FOR ANY
 DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


*/

#include <php.h> // include first to avoid conflict with stdint.h 

#include "common_params.h"
#include "exceptions.h"

int check_no_args_n(int num_args TSRMLS_DC)
{
    if (num_args > 0)
    {
        throw_invalid_argument("Too many arguments, expected none");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_alias_n(int num_args, zval** alias TSRMLS_DC)
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

    if (zend_parse_parameters(num_args TSRMLS_CC, "z", alias) == FAILURE)
    {
        throw_invalid_argument("Wrong argument types, expected (string alias)");
        return FAILURE;
    }

    if (Z_TYPE_PP(alias) != IS_STRING)
    {
        throw_invalid_argument("Argument 1 (alias) must be a string");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_alias_exp_n(int num_args, zval** alias, qdb_time_t* expiry TSRMLS_DC)
{
    if (num_args < 2)
    {
        throw_invalid_argument("Not enough arguments, expected exactly 2");
        return FAILURE;
    }

    if (num_args > 2)
    {
        throw_invalid_argument("Too many arguments, expected exactly 2");
        return FAILURE;
    }

    long longExpiry = 0;
    int result = zend_parse_parameters(num_args TSRMLS_CC, "zl", alias, &longExpiry);

    if (result == FAILURE)
    {
        throw_invalid_argument("Argument 2 (expiry) must be an integer");
        return FAILURE;
    }

    if (Z_TYPE_PP(alias) != IS_STRING)
    {
        throw_invalid_argument("Argument 1 (alias) must be a string");
        return FAILURE;
    }

    *expiry = longExpiry;

    return SUCCESS;
}

int parse_alias_cmp_n(int num_args, zval** alias, zval** comparand TSRMLS_DC)
{
    if (num_args < 2)
    {
        throw_invalid_argument("Not enough arguments, expected exactly 2");
        return FAILURE;
    }

    if (num_args > 2)
    {
        throw_invalid_argument("Too many arguments, expected exactly 2");
        return FAILURE;
    }

    int result = zend_parse_parameters(num_args TSRMLS_CC, "zz", alias, comparand);

    if( result == FAILURE)
    {
        throw_invalid_argument("Failed to parse arguments");
        return FAILURE;
    }

    if (Z_TYPE_PP(alias) != IS_STRING)
    {
        throw_invalid_argument("Argument 1 (alias) must be a string");
        return FAILURE;
    }

    if (Z_TYPE_PP(comparand) != IS_STRING)
    {
        throw_invalid_argument("Argument 2 (comparand) must be a string");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_alias_val_o_exp_n(int num_args, zval** alias, zval** content, qdb_time_t* expiry TSRMLS_DC)
{
    if (num_args < 2)
    {
        throw_invalid_argument("Not enough arguments, expected 2 or 3");
        return FAILURE;
    }

    if (num_args > 3)
    {
        throw_invalid_argument("Too many arguments, expected 2 or 3");
        return FAILURE;
    }

    long longExpiry = 0;
    int result = zend_parse_parameters(num_args TSRMLS_CC, "zz|l", alias, content, &longExpiry);

    if (result == FAILURE)
    {
        throw_invalid_argument("Argument 3 (expiry) must be an integer");
        return FAILURE;
    }

    if (Z_TYPE_PP(alias) != IS_STRING)
    {
        throw_invalid_argument("Argument 1 (alias) must be an string");
        return FAILURE;
    }

    if (Z_TYPE_PP(content) != IS_STRING)
    {
        throw_invalid_argument("Argument 2 (content) must be an string");
        return FAILURE;
    }

    *expiry = longExpiry;

    return SUCCESS;
}

int parse_alias_val_cmp_o_exp_n(int num_args, zval** alias, zval** content, zval** comparand, qdb_time_t* expiry TSRMLS_DC)
{
    if (num_args < 3)
    {
        throw_invalid_argument("Not enough arguments, expected 3 or 4");
        return FAILURE;
    }

    if (num_args > 4)
    {
        throw_invalid_argument("Too many arguments, expected 3 or 4");
        return FAILURE;
    }

    long longExpiry = 0;
    int result = zend_parse_parameters(num_args TSRMLS_CC, "zzz|l", alias, content, comparand, &longExpiry);

    if (result == FAILURE)
    {
        throw_invalid_argument("Argument 4 (expiry) must be an integer");
        return FAILURE;
    }

    if (Z_TYPE_PP(alias) != IS_STRING)
    {
        throw_invalid_argument("Argument 1 (alias) must be an string");
        return FAILURE;
    }

    if (Z_TYPE_PP(content) != IS_STRING)
    {
        throw_invalid_argument("Argument 2 (content) must be an string");
        return FAILURE;
    }

    if (Z_TYPE_PP(comparand) != IS_STRING)
    {
        throw_invalid_argument("Argument 3 (comparand) must be an string");
        return FAILURE;
    }

    *expiry = longExpiry;

    return SUCCESS;
}

int parse_offset_n(int num_args, long* offset TSRMLS_DC)
{
    if (zend_parse_parameters(num_args TSRMLS_CC, "l", offset) == FAILURE)
    {
        throw_invalid_argument("Expected integer");
        return FAILURE;
    }

    return SUCCESS;
}

int parse_val_n(int num_args, zval** content TSRMLS_DC)
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

    if (zend_parse_parameters(num_args TSRMLS_CC, "z", content) == FAILURE)
    {
        throw_invalid_argument("Wrong argument types, expected (string content)");
        return FAILURE;
    }

    if (Z_TYPE_PP(content) != IS_STRING)
    {
        throw_invalid_argument("Argument 1 (content) must be a string");
        return FAILURE;
    }

    return SUCCESS;
}
