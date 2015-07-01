// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include <qdb/tag.h>

#include "class_definition.h"
#include "QdbEntry.h"
#include "QdbTag.h"
#include "QdbTagCollection.h"
#include "exceptions.h"

#define class_name          QdbEntry
#define class_storage       entry_t


extern zend_class_entry* ce_QdbEntry;

void QdbEntry_constructInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    entry_t* this = (entry_t*)zend_object_store_get_object(destination TSRMLS_CC);

    Z_ADDREF_P(alias);
    this->alias = alias;
    this->handle = handle;
}

zval* QdbEntry_getAlias(zval* object TSRMLS_DC)
{
    entry_t* this = (entry_t*)zend_object_store_get_object(object TSRMLS_CC);
    return this->alias;
}

static zval* getTagAlias(zval* tag TSRMLS_DC)
{
    if (Z_TYPE_P(tag) == IS_STRING)
    {
        return tag;
    }
    else if (QdbTag_isInstance(tag TSRMLS_CC))
    {
        return QdbEntry_getAlias(tag TSRMLS_CC);
    }
    else
    {
        throw_invalid_argument("Argument tag must be a QdbTag or a string");
        return NULL;
    }
}


BEGIN_CLASS_METHOD_0(__destruct)
{
    Z_DELREF_P(this->alias);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(addTag, MIXED_ARG(tag))
{
    zval* tagAlias = getTagAlias(tag TSRMLS_CC);
    if (!tagAlias) return;

    qdb_error_t error = qdb_add_tag(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(tagAlias));

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


BEGIN_CLASS_METHOD_0(alias)
{
    Z_ADDREF_P(this->alias);
    *return_value = *this->alias;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(getTags)
{
    const char** tags;
    size_t tags_count;

    qdb_error_t error = qdb_get_tags(this->handle, Z_STRVAL_P(this->alias), &tags, &tags_count);

    if (error)
        throw_qdb_error(error);

    QdbTagCollection_createInstance(return_value, this->handle, tags, tags_count TSRMLS_CC);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(hasTag, MIXED_ARG(tag))
{
    zval* tagAlias = getTagAlias(tag TSRMLS_CC);
    if (!tagAlias) return;

    qdb_error_t error = qdb_has_tag(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(tagAlias));

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


BEGIN_CLASS_METHOD_0(remove)
{
    qdb_error_t error = qdb_remove(this->handle, Z_STRVAL_P(this->alias));

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(removeTag, MIXED_ARG(tag))
{
    zval* tagAlias = getTagAlias(tag TSRMLS_CC);
    if (!tagAlias) return;

    qdb_error_t error = qdb_remove_tag(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(tagAlias));

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
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(addTag)
    ADD_METHOD(alias)
    ADD_METHOD(getTags)
    ADD_METHOD(hasTag)
    ADD_METHOD(remove)
    ADD_METHOD(removeTag)
END_CLASS_MEMBERS()

#include "class_definition.i"
