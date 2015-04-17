
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
#include <spl/spl_exceptions.h>
#include <zend_exceptions.h>

#include "exceptions.h"

typedef struct {
    qdb_error_t code;
    const char* name;
    zend_class_entry *class_entry;
} qdb_exception_t;

static zend_class_entry *ce_QdbException;
static zend_class_entry *ce_QdbClusterConnectionFailedException;

static qdb_exception_t qdb_exceptions[] = {
    { qdb_e_uninitialized,          "QdbUninitializedException"        },
    { qdb_e_system,                 "QdbSystemException"               },
    { qdb_e_internal,               "QdbInternalException"             },
    { qdb_e_no_memory,              "QdbNoMemoryException"             },
    { qdb_e_invalid_protocol,       "QdbInvalidProtocolException"      },
    { qdb_e_host_not_found,         "QdbHostNotFoundException"         },
    { qdb_e_invalid_option,         "QdbInvalidOptionException"        },
    { qdb_e_alias_too_long,         "QdbAliasTooLongException"         },
    { qdb_e_alias_not_found,        "QdbAliasNotFoundException"        },
    { qdb_e_alias_already_exists,   "QdbAliasAlreadyExistsException"   },
    { qdb_e_timeout,                "QdbTimeoutException"              },
    { qdb_e_buffer_too_small,       "QdbBufferTooSmallException"       },
    { qdb_e_invalid_command,        "QdbInvalidCommandException"       },
    { qdb_e_invalid_input,          "QdbInvalidInputException"         },
    { qdb_e_connection_refused,     "QdbConnectionRefusedException"    },
    { qdb_e_connection_reset,       "QdbConnectionResetException"      },
    { qdb_e_unexpected_reply,       "QdbUnexpectedReplyException"      },
    { qdb_e_not_implemented,        "QdbNotImplementedException"       },
    { qdb_e_unstable_hive,          "QdbUnstableHiveException"         },
    { qdb_e_protocol_error,         "QdbProtocolErrorException"        },
    { qdb_e_outdated_topology,      "QdbOutdatedTopologyException"     },
    { qdb_e_wrong_peer,             "QdbWrongPeerException"            },
    { qdb_e_invalid_version,        "QdbInvalidVersionException"       },
    { qdb_e_try_again,              "QdbTryAgainException"             },
    { qdb_e_invalid_argument,       "QdbInvalidArgumentException"      },
    { qdb_e_out_of_bounds,          "QdbOutOfBoundsException"          },
    { qdb_e_conflict,               "QdbConflictException"             },
    { qdb_e_not_connected,          "QdbNotConnectedException"         },
    { qdb_e_invalid_handle,         "QdbInvalidHandleException"        },
    { qdb_e_reserved_alias,         "QdbReservedAliasException"        },
    { qdb_e_unmatched_content,      "QdbUnmatchedContentException"     },
    { qdb_e_invalid_iterator,       "QdbInvalidIteratorException"      },
    { qdb_e_prefix_too_short,       "QdbPrefixTooShortException"       },
    { qdb_e_skipped,                "QdbSkippedException"              },
    { qdb_e_incompatible_type,      "QdbIncompatibleTypeException"     },
    { qdb_e_empty_container,        "QdbEmptyContainerException"       },
    { qdb_e_container_full,         "QdbContainerFullException"        },
    { qdb_e_element_not_found,      "QdbElementNotFoundException"      },
    { qdb_e_element_already_exists, "QdbElementAlreadyExistsException" },
    { qdb_e_overflow,               "QdbOverflowException"             },
    { qdb_e_underflow,              "QdbUnderflowException"            },
};

#define EXCEPTIONS_COUNT (sizeof(qdb_exceptions)/sizeof(qdb_exceptions[0]))

static zend_class_entry * register_exception(const char* class_name, zend_class_entry* base_class TSRMLS_DC)
{
    zend_class_entry ce;

    INIT_CLASS_ENTRY_EX(ce, class_name, strlen(class_name), NULL);
    return zend_register_internal_class_ex(&ce, base_class, NULL TSRMLS_CC);
}

void exceptions_init(TSRMLS_D)
{
    int i;
    ce_QdbException = register_exception("QdbException", zend_exception_get_default(TSRMLS_C) TSRMLS_CC);
    ce_QdbClusterConnectionFailedException = register_exception("QdbClusterConnectionFailedException", ce_QdbException TSRMLS_CC);

    for (i=0; i<EXCEPTIONS_COUNT; i++) {
        qdb_exceptions[i].class_entry = register_exception(qdb_exceptions[i].name, ce_QdbException TSRMLS_CC);
    }
}

static zend_class_entry * get_exception_ce(qdb_error_t code)
{
    int i;

    for (i=0; i<EXCEPTIONS_COUNT; i++) {
        if (qdb_exceptions[i].code == code) {
            return qdb_exceptions[i].class_entry;
        }
    }

    // not found => return base class
    return ce_QdbException;
}

void throw_qdb_error_(qdb_error_t code TSRMLS_DC)
{
    char message[64];
    qdb_error(code, message, sizeof(message));
    zend_throw_exception_ex(get_exception_ce(code), 0 TSRMLS_CC, message);
}

void throw_invalid_argument_(const char* message TSRMLS_DC)
{
    zend_throw_exception_ex(spl_ce_InvalidArgumentException, 0 TSRMLS_CC, (char*)message);
}

void throw_out_of_range_(const char* message TSRMLS_DC)
{
    zend_throw_exception_ex(spl_ce_OutOfRangeException, 0 TSRMLS_CC, (char*)message);
}

void throw_out_of_bounds_(const char* message TSRMLS_DC)
{
    zend_throw_exception_ex(spl_ce_OutOfBoundsException, 0 TSRMLS_CC, (char*)message);
}

void throw_bad_function_call_(const char* message TSRMLS_DC)
{
    zend_throw_exception_ex(spl_ce_BadFunctionCallException, 0 TSRMLS_CC, (char*)message);
}

void throw_cluster_connection_failed_(TSRMLS_D)
{
    zend_throw_exception_ex(ce_QdbClusterConnectionFailedException, 0 TSRMLS_CC, "Connection to cluster failed.");
}
