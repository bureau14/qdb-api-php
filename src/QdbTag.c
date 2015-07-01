// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "class_definition.h"
#include "exceptions.h"
#include "QdbEntry.h"
#include "QdbEntryCollection.h"
#include "QdbTag.h"

#include <qdb/tag.h>

#define class_name          QdbTag
#define class_storage       entry_t
#define class_parent        QdbEntry


extern zend_class_entry* ce_QdbTag;


void QdbTag_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    object_init_ex(destination, ce_QdbTag);
    QdbEntry_constructInstance(destination, handle, alias TSRMLS_CC);
}

static zval* getEntryAlias(zval* entry TSRMLS_DC)
{
    if (Z_TYPE_P(entry) == IS_STRING)
    {
        return entry;
    }
    else if (QdbEntry_isInstance(entry TSRMLS_CC))
    {
        return QdbEntry_getAlias(entry TSRMLS_CC);
    }
    else
    {
        throw_invalid_argument("Argument entry must be a QdbEntry or a string");
        return NULL;
    }
}


BEGIN_CLASS_METHOD_1(addEntry, MIXED_ARG(entry))
{
    zval* entryAlias = getEntryAlias(entry TSRMLS_CC);
    if (!entryAlias) return;

    qdb_error_t error = qdb_add_tag(this->handle, Z_STRVAL_P(entryAlias), Z_STRVAL_P(this->alias));

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
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(getEntries)
{
    const char** entries;
    size_t entries_count;

    qdb_error_t error = qdb_get_tagged(this->handle, Z_STRVAL_P(this->alias), &entries, &entries_count);

    if (error)
        throw_qdb_error(error);

    QdbEntryCollection_createInstance(return_value, this->handle, entries, entries_count TSRMLS_CC);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(hasEntry, MIXED_ARG(entry))
{
    zval* entryAlias = getEntryAlias(entry TSRMLS_CC);
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
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(removeEntry, MIXED_ARG(entry))
{
    zval* entryAlias = getEntryAlias(entry TSRMLS_CC);
    if (!entryAlias) return;

    qdb_error_t error = qdb_remove_tag(this->handle, Z_STRVAL_P(entryAlias), Z_STRVAL_P(this->alias));

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
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_METHOD(addEntry)
    ADD_METHOD(getEntries)
    ADD_METHOD(hasEntry)
    ADD_METHOD(removeEntry)
END_CLASS_MEMBERS()

#include "class_definition.i"
