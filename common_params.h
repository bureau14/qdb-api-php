
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

#ifndef QDB_PARAMETERS_H
#define QDB_PARAMETERS_H

#include <php.h> // include first to avoid conflict with stdint.h 

#include <qdb/client.h>


int check_no_args_n(int num_args TSRMLS_DC);

#define check_no_args()  check_no_args_n(ZEND_NUM_ARGS() TSRMLS_CC)


int parse_alias_n(int num_args, zval** alias TSRMLS_DC);

#define parse_alias(alias)  parse_alias_n(ZEND_NUM_ARGS(), alias TSRMLS_CC)


int parse_alias_exp_n(int num_args, zval** alias, qdb_time_t* expiry TSRMLS_DC);

#define parse_alias_exp(alias, expiry)  parse_alias_exp_n(ZEND_NUM_ARGS(), alias, expiry TSRMLS_CC)


int parse_alias_cmp_n(int num_args, zval** alias, zval** comparand TSRMLS_DC);

#define parse_alias_cmp(alias, comparand)  parse_alias_cmp_n(ZEND_NUM_ARGS(), alias, comparand TSRMLS_CC)


int parse_alias_val_o_exp_n(int num_args, zval** alias, zval** content, qdb_time_t* expiry TSRMLS_DC);

#define parse_alias_val_o_exp(alias, content, expiry) parse_alias_val_o_exp_n(ZEND_NUM_ARGS(), alias, content, expiry TSRMLS_CC)


int parse_alias_val_cmp_o_exp_n(int num_args, zval** alias, zval** content, zval** comparand, qdb_time_t* expiry TSRMLS_DC);

#define parse_alias_val_cmp_o_exp(alias, content, comparand, expiry) parse_alias_val_cmp_o_exp_n(ZEND_NUM_ARGS(), alias, content, comparand, expiry TSRMLS_CC)


int parse_offset_n(int num_args, long* offset TSRMLS_DC);

#define parse_offset(offset) parse_offset_n(ZEND_NUM_ARGS(), offset TSRMLS_CC)


int parse_val_n(int num_args, zval** val TSRMLS_DC);

#define parse_val(val)  parse_val_n(ZEND_NUM_ARGS(), val TSRMLS_CC)


#endif /* QDB_PARAMETERS_H */
