// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "class_definition.h"
#include "QdbEntry.h"
#include "exceptions.h"

#include <qdb/client.h>

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


BEGIN_CLASS_METHOD_0(alias)
{
    RETURN_ZVAL(this->alias, /*copy=*/0, /*dtor=*/0);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(__destruct)
{
    Z_ADDREF_P(this->alias);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(remove)
{
    qdb_error_t error = qdb_remove(this->handle, Z_STRVAL_P(this->alias));

    if (error)
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(alias)
    ADD_METHOD(remove)
END_CLASS_MEMBERS()

#include "class_definition.c"
