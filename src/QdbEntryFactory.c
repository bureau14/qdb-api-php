// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "exceptions.h"
#include "QdbBlob.h"
#include "QdbDeque.h"
#include "QdbInteger.h"
#include "QdbHashSet.h"
#include "QdbTag.h"
#include "QdbEntryFactory.h"

void QdbEntryFactory_createFromType(
    zval* destination, qdb_handle_t handle, qdb_entry_type_t type, const char* alias TSRMLS_DC)
{
    zval* zalias;
    ALLOC_INIT_ZVAL(zalias);
    ZVAL_STRING(zalias, alias, /*dup=*/1);

    switch (type)
    {
        case qdb_entry_blob:
            QdbBlob_createInstance(destination, handle, zalias TSRMLS_CC);
            break;

        case qdb_entry_hset:
            QdbHashSet_createInstance(destination, handle, zalias TSRMLS_CC);
            break;

        case qdb_entry_integer:
            QdbInteger_createInstance(destination, handle, zalias TSRMLS_CC);
            break;

        case qdb_entry_deque:
            QdbDeque_createInstance(destination, handle, zalias TSRMLS_CC);
            break;

        case qdb_entry_tag:
            QdbTag_createInstance(destination, handle, zalias TSRMLS_CC);
            break;

        default:
            throw_bad_function_call("Entry type not supported, please update quasardb PHP API.");
            break;
    }
}


qdb_error_t QdbEntryFactory_createFromAlias(zval* destination, qdb_handle_t handle, const char* alias TSRMLS_DC)
{
    qdb_entry_type_t type;
    qdb_error_t error = qdb_get_type(handle, alias, &type);

    if (error)
        return error;

    QdbEntryFactory_createFromType(destination, handle, type, alias TSRMLS_CC);
    return qdb_e_ok;
}