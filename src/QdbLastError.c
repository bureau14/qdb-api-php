// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#include "QdbGetLastError.h"
#include "class_definition.h"

typedef struct
{
    zval error;
    zval message;
} _zval_last_error_t;

#define class_name QdbLastError
#define class_storage _zval_last_error_t

extern zend_class_entry* ce_QdbLastError;

CLASS_METHOD_0(__construct)
{
    qdb_error_t error;
    qdb_string_t message;
    qdb_get_last_error(&error, &message);

    ZVAL_LONG(&this->error, error);
    ZVAL_STRINGL(&this->message, message.data, message.length);
}

CLASS_METHOD_0(error)
{
    ZVAL_COPY_VALUE(return_value, &this->error);
}

CLASS_METHOD_0(message)
{
    ZVAL_COPY(return_value, &this->message);
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_METHOD(error)
    ADD_METHOD(message)
END_CLASS_MEMBERS()

#include "class_definition.i"
