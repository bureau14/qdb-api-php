// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbBlob.h"
#include "QdbEntryFactory.h"
#include "QdbInteger.h"
#include "QdbTag.h"
#include "exceptions.h"

void QdbEntryFactory_createFromType(
    zval* destination, qdb_handle_t handle, qdb_entry_type_t type, const char* alias)
{
    zval* zalias;
    MAKE_STD_ZVAL(zalias);
    ZVAL_STRING(zalias, alias);

    switch (type)
    {
        case qdb_entry_blob:
            QdbBlob_createInstance(destination, handle, zalias);
            break;

        case qdb_entry_integer:
            QdbInteger_createInstance(destination, handle, zalias);
            break;

        case qdb_entry_tag:
            QdbTag_createInstance(destination, handle, zalias);
            break;

        default:
            throw_bad_function_call("Entry type not supported, please update quasardb PHP API.");
            break;
    }
}

qdb_error_t QdbEntryFactory_createFromAlias(zval* destination, qdb_handle_t handle, const char* alias)
{
    qdb_entry_metadata_t meta;
    qdb_error_t error = qdb_get_metadata(handle, alias, &meta);

    if (error) return error;

    QdbEntryFactory_createFromType(destination, handle, meta.type, alias);
    return qdb_e_ok;
}
