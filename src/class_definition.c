// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>

#include "exceptions.h"

void free_object_storage(void *object TSRMLS_DC)
{
    zend_object_std_dtor((zend_object*)object TSRMLS_CC);
    efree(object);
}

zend_object_value alloc_object_storage(zend_class_entry *ce, zend_object_handlers *object_handlers, size_t size TSRMLS_DC)
{
    zend_object_value retval;
    zend_object *this;

    this = (zend_object*)ecalloc(1, size);
    zend_object_std_init(this, ce TSRMLS_CC);

#if PHP_VERSION_ID < 50399
    zend_hash_copy(this->properties, &ce->default_properties, (copy_ctor_func_t)zval_add_ref, NULL, sizeof(zval*));
#else
    object_properties_init(this, ce);
#endif

    retval.handle = zend_objects_store_put(this, NULL, free_object_storage, NULL TSRMLS_CC);
    retval.handlers = object_handlers;
    return retval;
}

int check_arg_count(int actual, int min, int max TSRMLS_DC)
{
    if (min <= actual && actual <= max)
        return SUCCESS;

    char message[64];

    if (actual < min)
    {
        if (min == max)
            sprintf(message, "Not enough arguments, expected exactly %d", min);
        else
            sprintf(message, "Not enough arguments, expected at least %d", min);
    }
    else
    {
        if (min == max)
            sprintf(message, "Too many arguments, expected exactly %d", max);
        else
            sprintf(message, "Too many arguments, expected at most %d", max);
    }

    throw_invalid_argument(message);
    return FAILURE;
}