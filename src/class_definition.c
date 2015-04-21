// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#define CLASS_ENTRY XCONCAT(ce_,class_name)

zend_class_entry *CLASS_ENTRY;
static zend_object_handlers object_handlers;

static void free_object_storage(void *object TSRMLS_DC)
{
    zend_object_std_dtor((zend_object*)object TSRMLS_CC);
    efree(object);
}

static zend_object_value alloc_object_storage(zend_class_entry *ce TSRMLS_DC)
{
    zend_object_value retval;
    zend_object *this;

    this = (zend_object*)ecalloc(1, sizeof(class_storage));
    zend_object_std_init(this, ce TSRMLS_CC);

#if PHP_VERSION_ID < 50399
    zend_hash_copy(this->properties, &ce->default_properties, (copy_ctor_func_t)zval_add_ref, NULL, sizeof(zval*));
#else
    object_properties_init(this, ce);
#endif

    retval.handle = zend_objects_store_put(this, NULL, free_object_storage, NULL TSRMLS_CC);
    retval.handlers = &object_handlers;
    return retval;
}

void XCONCAT(class_name, _registerClass)(TSRMLS_D)
{
    zend_class_entry ce;
    INIT_CLASS_ENTRY(ce, XSTR(class_name), methods);
    CLASS_ENTRY = zend_register_internal_class(&ce TSRMLS_CC);
    CLASS_ENTRY->create_object = alloc_object_storage;
    memcpy(&object_handlers, zend_get_std_object_handlers(), sizeof(zend_object_handlers));
    object_handlers.clone_obj = NULL;

#ifdef class_interfaces
    zend_class_implements(CLASS_ENTRY TSRMLS_CC, class_interfaces);
#endif
}
