
// Need the following defines :
// #define class_storage <the class's associated struct>
// #define class_name    <the PHP class name>
// #define class_parent  <optional, the PHP parent class>

#define CLASS_ENTRY XCONCAT(ce_,class_name)
zend_class_entry *CLASS_ENTRY = NULL;

#ifdef class_parent
#define BASE_CLASS_ENTRY XCONCAT(ce_,class_parent)
extern zend_class_entry *BASE_CLASS_ENTRY;
#endif

static zend_object_handlers object_handlers;

#define BOXED_STORAGE XCONCAT(_boxed_,class_storage)

typedef struct {
    class_storage storage;
    zend_object std;
} BOXED_STORAGE;

static BOXED_STORAGE* get_boxed_storage(zend_object *obj) {
    return (BOXED_STORAGE*)((char*)(obj) - offsetof(BOXED_STORAGE, std));
}

static void free_object_storage(zend_object *obj) {
    BOXED_STORAGE* box = get_boxed_storage(obj);
	zend_object_std_dtor(&box->std);
}

static zend_object* create_object(zend_class_entry *ce)
{
	BOXED_STORAGE* box = zend_object_alloc(sizeof(BOXED_STORAGE), ce);
	zend_object_std_init(&box->std, ce);
	object_properties_init(&box->std, ce);
	box->std.handlers = &object_handlers;
    return box->std;
}

void XCONCAT(class_name, _registerClass)()
{
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
	object_handlers.free_obj  = free_object_storage;


#ifdef class_interfaces
    zend_class_implements(CLASS_ENTRY, class_interfaces);
#endif
}

int XCONCAT(class_name, _isInstance)(zval* object)
{
    return Z_TYPE_P(object) == IS_OBJECT && instanceof_function(Z_OBJCE_P(object), CLASS_ENTRY);
}
