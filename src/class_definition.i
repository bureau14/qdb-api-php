// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#define CLASS_ENTRY XCONCAT(ce_,class_name)
zend_class_entry *CLASS_ENTRY;

#ifdef class_parent
#define BASE_CLASS_ENTRY XCONCAT(ce_,class_parent)
extern zend_class_entry *BASE_CLASS_ENTRY;
#endif

static zend_object_handlers object_handlers;

// class_definition.c
void free_object_storage(void* TSRMLS_DC);
zend_object_value alloc_object_storage(zend_class_entry *, zend_object_handlers*, size_t TSRMLS_DC);

static zend_object_value create_object(zend_class_entry *ce TSRMLS_DC)
{
    return alloc_object_storage(ce, &object_handlers, sizeof(class_storage) TSRMLS_CC);
}

void XCONCAT(class_name, _registerClass)(TSRMLS_D)
{
    zend_class_entry ce;
    INIT_CLASS_ENTRY(ce, XSTR(class_name), methods);
#ifdef BASE_CLASS_ENTRY
    CLASS_ENTRY = zend_register_internal_class_ex(&ce, BASE_CLASS_ENTRY, NULL TSRMLS_CC);
#else
    CLASS_ENTRY = zend_register_internal_class(&ce TSRMLS_CC);
#endif
    CLASS_ENTRY->create_object = create_object;
    memcpy(&object_handlers, zend_get_std_object_handlers(), sizeof(zend_object_handlers));
    object_handlers.clone_obj = NULL;

#ifdef class_interfaces
    zend_class_implements(CLASS_ENTRY TSRMLS_CC, class_interfaces);
#endif
}

int XCONCAT(class_name, _isInstance)(zval* object TSRMLS_DC)
{
    return Z_TYPE_P(object) == IS_OBJECT && instanceof_function(Z_OBJCE_P(object), CLASS_ENTRY TSRMLS_CC);
}