// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_exceptions.h>
#include <zend_exceptions.h>

#include "exceptions.h"

// base for all exception
static zend_class_entry* ce_QdbException;

// bases for each error origin
static zend_class_entry* ce_QdbConnectionException;
static zend_class_entry* ce_QdbInputException;
static zend_class_entry* ce_QdbOperationException;
static zend_class_entry* ce_QdbProtocolException;
static zend_class_entry* ce_QdbSystemException;

// specific to some error codes
static zend_class_entry* ce_QdbAliasAlreadyExistsException;
static zend_class_entry* ce_QdbAliasNotFoundException;
static zend_class_entry* ce_QdbContainerEmptyException;  // <- TODO: return null instead of throwing
static zend_class_entry* ce_QdbIncompatibleTypeException;
static zend_class_entry* ce_QdbOperationDisabledException;

static zend_class_entry* register_exception_(const char* class_name, zend_class_entry* base_class TSRMLS_DC)
{
    zend_class_entry ce;

    INIT_CLASS_ENTRY_EX(ce, class_name, strlen(class_name), NULL);
    return zend_register_internal_class_ex(&ce, base_class, NULL TSRMLS_CC);
}

#define register_exception(class_name, base_class) register_exception_(class_name, base_class TSRMLS_CC)

void exceptions_init(TSRMLS_D)
{
    ce_QdbException = register_exception("QdbException", zend_exception_get_default(TSRMLS_C));

    ce_QdbConnectionException = register_exception("QdbConnectionException", ce_QdbException);
    ce_QdbInputException = register_exception("QdbInputException", ce_QdbException);
    ce_QdbOperationException = register_exception("QdbOperationException", ce_QdbException);
    ce_QdbProtocolException = register_exception("QdbProtocolException", ce_QdbException);
    ce_QdbSystemException = register_exception("QdbSystemException", ce_QdbException);

    ce_QdbAliasAlreadyExistsException = register_exception("QdbAliasAlreadyExistsException", ce_QdbOperationException);
    ce_QdbAliasNotFoundException = register_exception("QdbAliasNotFoundException", ce_QdbOperationException);
    ce_QdbContainerEmptyException = register_exception("QdbContainerEmptyException", ce_QdbOperationException);
    ce_QdbIncompatibleTypeException = register_exception("QdbIncompatibleTypeException", ce_QdbOperationException);
    ce_QdbOperationDisabledException = register_exception("QdbOperationDisabledException", ce_QdbOperationException);
}

static zend_class_entry* get_exception_ce(qdb_error_t code)
{
    switch (code)
    {
        case qdb_e_alias_already_exists:
            return ce_QdbAliasAlreadyExistsException;

        case qdb_e_alias_not_found:
            return ce_QdbAliasNotFoundException;

        case qdb_e_container_empty:
            return ce_QdbContainerEmptyException;

        case qdb_e_incompatible_type:
            return ce_QdbIncompatibleTypeException;

        case qdb_e_operation_disabled:
            return ce_QdbOperationDisabledException;
    }

    switch (QDB_ERROR_ORIGIN(code))
    {
        case qdb_e_origin_system_remote:
        case qdb_e_origin_system_local:
            return ce_QdbSystemException;

        case qdb_e_origin_connection:
            return ce_QdbConnectionException;

        case qdb_e_origin_input:
            return ce_QdbInputException;

        case qdb_e_origin_protocol:
            return ce_QdbProtocolException;

        case qdb_e_origin_operation:
            return ce_QdbOperationException;
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
