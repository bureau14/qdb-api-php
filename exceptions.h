
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

#ifndef QDB_EXCEPTIONS_H
#define QDB_EXCEPTIONS_H

#include <php.h>
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

void throw_cluster_connection_failed_(TSRMLS_D);
#define throw_cluster_connection_failed() throw_cluster_connection_failed_(TSRMLS_C)


#endif /* QDB_EXCEPTIONS_H */
