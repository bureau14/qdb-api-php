// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbTag.h"
#include "QdbEntry.h"
#include "QdbEntryCollection.h"
#include "class_definition.h"
#include "exceptions.h"
#include <qdb/tag.h>

#define class_name QdbTag
#define class_storage entry_t
#define class_parent QdbEntry

void QdbTag_createInstance(zval* destination, qdb_handle_t handle, zval* alias)
{
    object_init_ex(destination, ce_QdbTag);
    QdbEntry_constructInstance(destination, handle, alias);
}

static zval* getEntryAlias(zval* entry)
{
    if (Z_TYPE_P(entry) == IS_STRING)
    {
        return entry;
    }
    else if (QdbEntry_isInstance(entry))
    {
        return QdbEntry_getAlias(entry);
    }
    else
    {
        throw_invalid_argument("Argument entry must be a QdbEntry or a string");
        return NULL;
    }
}


CLASS_METHOD_1(attachEntry, MIXED_ARG(entry))
{
    zval* entryAlias = getEntryAlias(entry);
    if (!entryAlias) return;

    qdb_error_t error = qdb_attach_tag(this->handle, Z_STRVAL_P(entryAlias), Z_STRVAL_P(this->alias));

    switch (error)
    {
        case qdb_e_ok:
            RETURN_TRUE;
        case qdb_e_tag_already_set:
            RETURN_FALSE;
        default:
            throw_qdb_error(error);
    }
}

CLASS_METHOD_0(getEntries)
{
    const char** entries;
    size_t entries_count;

    qdb_error_t error = qdb_get_tagged(this->handle, Z_STRVAL_P(this->alias), &entries, &entries_count);

    if (error) throw_qdb_error(error);

    QdbEntryCollection_createInstance(return_value, this->handle, entries, entries_count);
}

CLASS_METHOD_1(hasEntry, MIXED_ARG(entry))
{
    zval* entryAlias = getEntryAlias(entry);
    if (!entryAlias) return;

    qdb_error_t error = qdb_has_tag(this->handle, Z_STRVAL_P(entryAlias), Z_STRVAL_P(this->alias));

    switch (error)
    {
        case qdb_e_ok:
            RETURN_TRUE;
        case qdb_e_tag_not_set:
            RETURN_FALSE;
        default:
            throw_qdb_error(error);
    }
}

CLASS_METHOD_1(detachEntry, MIXED_ARG(entry))
{
    zval* entryAlias = getEntryAlias(entry);
    if (!entryAlias) return;

    qdb_error_t error = qdb_detach_tag(this->handle, Z_STRVAL_P(entryAlias), Z_STRVAL_P(this->alias));

    switch (error)
    {
        case qdb_e_ok:
            RETURN_TRUE;
        case qdb_e_tag_not_set:
            RETURN_FALSE;
        default:
            throw_qdb_error(error);
    }
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(attachEntry)
    ADD_METHOD(getEntries)
    ADD_METHOD(hasEntry)
    ADD_METHOD(detachEntry)
END_CLASS_MEMBERS()

#include "class_definition.i"
