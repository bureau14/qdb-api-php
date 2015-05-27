// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "class_definition.h"
#include "exceptions.h"
#include "QdbEntry.h"


#define class_name          QdbHashSet
#define class_storage       entry_t
#define class_parent        QdbEntry


extern zend_class_entry* ce_QdbHashSet;


void QdbHashSet_createInstance(zval* destination, qdb_handle_t handle, zval* alias TSRMLS_DC)
{
    object_init_ex(destination, ce_QdbHashSet);
    QdbEntry_constructInstance(destination, handle, alias TSRMLS_CC);
}


BEGIN_CLASS_METHOD_1(insert, STRING_ARG(content))
{
    qdb_error_t error = qdb_hset_insert(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error == qdb_e_element_already_exists)
        RETVAL_FALSE;
    else if (error)
        throw_qdb_error(error);
    else
        RETVAL_TRUE;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(erase, STRING_ARG(content))
{
    qdb_error_t error = qdb_hset_erase(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error == qdb_e_element_not_found)
        RETVAL_FALSE;
    else if (error)
        throw_qdb_error(error);
    else
        RETVAL_TRUE;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_1(contains, STRING_ARG(content))
{
    qdb_error_t error = qdb_hset_contains(this->handle, Z_STRVAL_P(this->alias), Z_STRVAL_P(content), Z_STRLEN_P(content));

    if (error == qdb_e_element_not_found)
        RETVAL_FALSE;
    else if (error)
        throw_qdb_error(error);
    else
        RETVAL_TRUE;
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_METHOD(contains)
    ADD_METHOD(erase)
    ADD_METHOD(insert)
END_CLASS_MEMBERS()

#include "class_definition.i"
