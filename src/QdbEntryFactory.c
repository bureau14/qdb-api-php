// Copyright (c) 2009-2019, quasardb SAS
// All rights reserved.

#include "QdbBlob.h"
#include "QdbEntryFactory.h"
#include "QdbExpirableEntry.h"
#include "QdbInteger.h"
#include "QdbTag.h"
#include "exceptions.h"

void QdbEntryFactory_createFromType(
    zval* destination, qdb_handle_t handle, qdb_entry_type_t type, const char* alias)
{
    zend_class_entry* ce = NULL;
    switch (type)
    {
        case qdb_entry_blob:
            ce = ce_QdbBlob;
            break;
        case qdb_entry_integer:
            ce = ce_QdbInteger;
            break;
        case qdb_entry_tag:
            ce = ce_QdbTag;
            break;
        default:
            throw_bad_function_call("Entry type not supported, please update quasardb PHP API.");
    }
    object_init_ex(destination, ce);
    
    zval zalias;
    ZVAL_STRING(&zalias, alias);
    QdbExpirableEntry_constructInstance(destination, handle, &zalias);
}

qdb_error_t QdbEntryFactory_createFromAlias(zval* destination, qdb_handle_t handle, const char* alias)
{
    qdb_entry_metadata_t meta;
    qdb_error_t error = qdb_get_metadata(handle, alias, &meta);

    if (error) return error;

    QdbEntryFactory_createFromType(destination, handle, meta.type, alias);
    return qdb_e_ok;
}
