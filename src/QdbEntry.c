// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbEntry.h"
#include "QdbTag.h"
#include "QdbTagCollection.h"
#include "class_definition.h"
#include "exceptions.h"
#include <qdb/tag.h>

#define class_name QdbEntry
#define class_storage entry_t


extern zend_class_entry* ce_QdbEntry;

void QdbEntry_constructInstance(zval* destination, qdb_handle_t handle, zval* alias)
{
    entry_t* this = (entry_t*)Z_OBJ_P(destination);

    Z_ADDREF_P(alias);
    this->alias = alias;
    this->handle = handle;
}

zval* QdbEntry_getAlias(zval* object)
{
    entry_t* this = (entry_t*)Z_OBJ_P(object);
    return this->alias;
}

static zval* getTagAlias(zval* tag)
{
    if (Z_TYPE_P(tag) == IS_STRING)
    {
        return tag;
    }
    else if (QdbTag_isInstance(tag))
    {
        return QdbEntry_getAlias(tag);
    }
    else
    {
        // printf("[%d]\n", Z_TYPE_P(tag));
        throw_invalid_argument("Argument tag must be a QdbTag or a string");
        return NULL;
    }
}


CLASS_METHOD_0(__destruct)
{
    Z_DELREF_P(this->alias);
}

CLASS_METHOD_1(attachTag, MIXED_ARG(tag))
{
    zval* tagAlias = getTagAlias(tag);
    if (!tagAlias) return;

    qdb_error_t error = qdb_attach_tag(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(tagAlias));

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

CLASS_METHOD_1(attachTags, ARRAY_ARG(tags))
{
    int tagCount = zend_hash_num_elements(Z_ARRVAL_P(tags));

    if (tagCount <= 0)
    {
        throw_invalid_argument("Argument tags cannot be empty");
        return;
    }

    const char** tagAliases = alloca(tagCount * sizeof(char*));
    zval* tag;
    int i = 0;
    for (zend_hash_internal_pointer_reset(Z_ARRVAL_P(tags));
         (tag = zend_hash_get_current_data(Z_ARRVAL_P(tags)));
         zend_hash_move_forward(Z_ARRVAL_P(tags)))
    {
        zval* tagAlias = getTagAlias(tag);
        if (!tagAlias) return;

        tagAliases[i++] = Z_STRVAL_P(tagAlias);
    }

    qdb_error_t error = qdb_attach_tags(this->handle, Z_STRVAL_P(this->alias), tagAliases, tagCount);

    if (error) throw_qdb_error(error);
}

CLASS_METHOD_0(alias)
{
    Z_ADDREF_P(this->alias);
    *return_value = *this->alias;
}

CLASS_METHOD_0(getTags)
{
    const char** tags;
    size_t tags_count;

    qdb_error_t error = qdb_get_tags(this->handle, Z_STRVAL_P(this->alias), &tags, &tags_count);

    if (error)
    {
        throw_qdb_error(error);
        return;
    }

    QdbTagCollection_createInstance(return_value, this->handle, tags, tags_count);
}

CLASS_METHOD_1(hasTag, MIXED_ARG(tag))
{
    zval* tagAlias = getTagAlias(tag);
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

CLASS_METHOD_0(remove)
{
    qdb_error_t error = qdb_remove(this->handle, Z_STRVAL_P(this->alias));

    if (error) throw_qdb_error(error);
}

CLASS_METHOD_1(detachTag, MIXED_ARG(tag))
{
    zval* tagAlias = getTagAlias(tag);
    if (!tagAlias) return;

    qdb_error_t error = qdb_detach_tag(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(tagAlias));

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
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(attachTag)
    ADD_METHOD(attachTags)
    ADD_METHOD(alias)
    ADD_METHOD(getTags)
    ADD_METHOD(hasTag)
    ADD_METHOD(remove)
    ADD_METHOD(detachTag)
END_CLASS_MEMBERS()

#include "class_definition.i"
