// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#include "QdbLastError.h"
#include "class_definition.h"
#include <qdb/client.h>

typedef struct
{
    zval message;
} _zval_last_error_t;

#define class_name QdbLastError
#define class_storage _zval_last_error_t

extern zend_class_entry* ce_QdbLastError;

CLASS_METHOD_0(__construct)
{
    qdb_string_t message;
    qdb_get_last_error(NULL, &message);

    ZVAL_STRINGL(&this->message, message.data, message.length);
}

CLASS_METHOD_0(message)
{
    ZVAL_COPY(return_value, &this->message);
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_METHOD(message)
END_CLASS_MEMBERS()

#include "class_definition.i"
