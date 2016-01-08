// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_exceptions.h>
#include <zend_exceptions.h>

#include "exceptions.h"

typedef struct
{
    qdb_error_t code;
    const char* name;
    zend_class_entry* class_entry;
} qdb_exception_t;

static zend_class_entry* ce_QdbException;

// clang-format off
static qdb_exception_t qdb_exceptions[] = {
    { qdb_e_alias_already_exists,        "QdbAliasAlreadyExistsException"        },
    { qdb_e_alias_not_found,             "QdbAliasNotFoundException"             },
    { qdb_e_buffer_too_small,            "QdbBufferTooSmallException"            },
    { qdb_e_conflict,                    "QdbConflictException"                  },
    { qdb_e_connection_refused,          "QdbConnectionRefusedException"         },
    { qdb_e_connection_reset,            "QdbConnectionResetException"           },
    { qdb_e_container_empty,             "QdbContainerEmptyException"            },
    { qdb_e_container_full,              "QdbContainerFullException"             },
    { qdb_e_element_already_exists,      "QdbElementAlreadyExistsException"      },
    { qdb_e_element_not_found,           "QdbElementNotFoundException"           },
    { qdb_e_entry_too_large,             "QdbEntryTooLargeException"             },
    { qdb_e_host_not_found,              "QdbHostNotFoundException"              },
    { qdb_e_incompatible_type,           "QdbIncompatibleTypeException"          },
    { qdb_e_internal_local,              "QdbInternalLocalException"             },
    { qdb_e_internal_remote,             "QdbInternalRemoteException"            },
    { qdb_e_invalid_argument,            "QdbInvalidArgumentException"           },
    { qdb_e_invalid_handle,              "QdbInvalidHandleException"             },
    { qdb_e_invalid_iterator,            "QdbInvalidIteratorException"           },
    { qdb_e_invalid_protocol,            "QdbInvalidProtocolException"           },
    { qdb_e_invalid_version,             "QdbInvalidVersionException"            },
    { qdb_e_no_memory_local,             "QdbNoMemoryLocalException"             },
    { qdb_e_no_memory_remote,            "QdbNoMemoryRemoteException"            },
    { qdb_e_not_connected,               "QdbNotConnectedException"              },
    { qdb_e_not_implemented,             "QdbNotImplementedException"            },
    { qdb_e_operation_disabled,          "QdbOperationDisabledException"         },
    { qdb_e_out_of_bounds,               "QdbOutOfBoundsException"               },
    { qdb_e_outdated_topology,           "QdbOutdatedTopologyException"          },
    { qdb_e_overflow,                    "QdbOverflowException"                  },
    { qdb_e_protocol_error,              "QdbProtocolErrorException"             },
    { qdb_e_reserved_alias,              "QdbReservedAliasException"             },
    { qdb_e_resource_locked,             "QdbResourceLockedException"            },
    { qdb_e_skipped,                     "QdbSkippedException"                   },
    { qdb_e_system_local,                "QdbSystemLocalException"               },
    { qdb_e_system_remote,               "QdbSystemRemoteException"              },
    { qdb_e_tag_already_set,             "QdbTagAlreadySetException"             },
    { qdb_e_tag_not_set,                 "QdbTagNotSetException"                 },
    { qdb_e_timeout,                     "QdbTimeoutException"                   },
    { qdb_e_transaction_partial_failure, "QdbTransactionPartialFailureException" },
    { qdb_e_try_again,                   "QdbTryAgainException"                  },
    { qdb_e_underflow,                   "QdbUnderflowException"                 },
    { qdb_e_unexpected_reply,            "QdbUnexpectedReplyException"           },
    { qdb_e_uninitialized,               "QdbUninitializedException"             },
    { qdb_e_unmatched_content,           "QdbUnmatchedContentException"          },
    { qdb_e_unstable_cluster,            "QdbUnstableClusterException"           },
    { qdb_e_wrong_peer,                  "QdbWrongPeerException"                 }
};
// clang-format on

#define EXCEPTIONS_COUNT (sizeof(qdb_exceptions) / sizeof(qdb_exceptions[0]))

static zend_class_entry* register_exception(const char* class_name, zend_class_entry* base_class TSRMLS_DC)
{
    zend_class_entry ce;

    INIT_CLASS_ENTRY_EX(ce, class_name, strlen(class_name), NULL);
    return zend_register_internal_class_ex(&ce, base_class, NULL TSRMLS_CC);
}

void exceptions_init(TSRMLS_D)
{
    int i;
    ce_QdbException = register_exception("QdbException", zend_exception_get_default(TSRMLS_C) TSRMLS_CC);

    for (i = 0; i < EXCEPTIONS_COUNT; i++)
    {
        qdb_exceptions[i].class_entry = register_exception(qdb_exceptions[i].name, ce_QdbException TSRMLS_CC);
    }
}

static zend_class_entry* get_exception_ce(qdb_error_t code)
{
    int i;

    for (i = 0; i < EXCEPTIONS_COUNT; i++)
    {
        if (qdb_exceptions[i].code == code)
        {
            return qdb_exceptions[i].class_entry;
        }
    }

    // not found => return base class
    return ce_QdbException;
}

void throw_qdb_error_(qdb_error_t code TSRMLS_DC)
{
    zend_throw_exception(get_exception_ce(code), qdb_error(code), 0 TSRMLS_CC);
}

void throw_invalid_argument_(const char* message TSRMLS_DC)
{
    zend_throw_exception(spl_ce_InvalidArgumentException, (char*)message, 0 TSRMLS_CC);
}

void throw_out_of_range_(const char* message TSRMLS_DC)
{
    zend_throw_exception(spl_ce_OutOfRangeException, (char*)message, 0 TSRMLS_CC);
}

void throw_out_of_bounds_(const char* message TSRMLS_DC)
{
    zend_throw_exception(spl_ce_OutOfBoundsException, (char*)message, 0 TSRMLS_CC);
}

void throw_bad_function_call_(const char* message TSRMLS_DC)
{
    zend_throw_exception(spl_ce_BadFunctionCallException, (char*)message, 0 TSRMLS_CC);
}
