
/*


 Copyright (c) 2009-2015, quasardb SAS
 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of quasardb nor the names of its contributors may
      be used to endorse or promote products derived from this software
      without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY QUASARDB AND CONTRIBUTORS ``AS IS'' AND ANY
 EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED. IN NO EVENT SHALL THE REGENTS AND CONTRIBUTORS BE LIABLE FOR ANY
 DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


*/

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

void XCONCAT(register_, class_name)(TSRMLS_D)
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
