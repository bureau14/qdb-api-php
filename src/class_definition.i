
// Need the following defines :
// #define class_storage    <the class's associated struct>
// #define class_name       <the PHP class name>
// #define class_parent     <optional, the PHP parent class>
// #define class_interfaces <optional, <number of interfaces>, <interfaces names, ...>>

#define CLASS_ENTRY XCONCAT(ce_,class_name)
zend_class_entry* CLASS_ENTRY = NULL;

#ifdef class_parent
#define BASE_CLASS_ENTRY XCONCAT(ce_,class_parent)
extern zend_class_entry* BASE_CLASS_ENTRY;
#endif

static zend_object_handlers object_handlers;

#define BOXED_STORAGE XCONCAT(_boxed_,class_storage)

typedef struct {
    class_storage storage;
    zend_object std;
} BOXED_STORAGE;

static BOXED_STORAGE* get_boxed_storage(zend_object* obj) {
    return (BOXED_STORAGE*)((char*)(obj) - offsetof(BOXED_STORAGE, std));
}

static void* _get_class_storage(zval* this) {
	return &get_boxed_storage(Z_OBJ_P(this))->storage;
}

static void free_boxed_storage(zend_object* obj) {
    php_printf("Destroy " XSTR(class_name) "...");
    BOXED_STORAGE* box = get_boxed_storage(obj);
	zend_object_std_dtor(&box->std);
    php_printf(" Finished\n");
}

static zend_object* create_object(zend_class_entry* ce)
{
    php_printf("Create " XSTR(class_name) "...");
	zend_object* obj_std = &zend_object_alloc(sizeof(BOXED_STORAGE), ce)->std;
	zend_object_std_init(obj_std, ce);
	object_properties_init(obj_std, ce);
	obj_std->handlers = &object_handlers;
    return obj_std;
    php_printf(" Finished\n");
}

void XCONCAT(class_name, _registerClass)()
{
    php_printf("Register " XSTR(class_name) "...");

    zend_class_entry ce;
    INIT_CLASS_ENTRY(ce, XSTR(class_name), methods);
    ce.create_object = create_object;
#ifdef BASE_CLASS_ENTRY
    CLASS_ENTRY = zend_register_internal_class_ex(&ce, BASE_CLASS_ENTRY);
#else
    CLASS_ENTRY = zend_register_internal_class(&ce);
#endif

    memcpy(&object_handlers, &std_object_handlers, sizeof(zend_object_handlers));
    object_handlers.offset    = offsetof(BOXED_STORAGE, std);
    object_handlers.clone_obj = NULL;
	object_handlers.free_obj  = free_boxed_storage;

#ifdef class_interfaces
    zend_class_implements(CLASS_ENTRY, class_interfaces);
#endif
    php_printf(" Finished\n");
}

int XCONCAT(class_name, _isInstance)(zval* object)
{
    return Z_TYPE_P(object) == IS_OBJECT && instanceof_function(Z_OBJCE_P(object), CLASS_ENTRY);
}
