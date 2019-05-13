
// The API is object-oriented.
// Each new class must be implemented in it's own source file (an example is given below).
// For advanced usage :
//  - Read the code
//  - Find a good documentation (https://phpinternals.net/docs can help)
//  - Visit the PHP7 source

#include "class_definition.h"

// The PHP class name.
#define class_name Person

// The PHP class associated storage, available in methods as 'this'.
#define class_storage _person_t
typedef struct
{
    // PHP values are represented with the 'zval' type.
    zval name; // PHP string
    zval age;  // PHP long
} _person_t;

// Optionals macros :
// #define class_parent     <parent_class_name>
// #define class_interfaces <num_of_interfaces, interface_names...>

static init_fields(_person_t* this, zval* name, zval* age) {
    // zval can be initialized with this macro.
    ZVAL_COPY(&this->name, name);
    // ZVAL_COPY_VALUE do not increment reference count, which is not used for simple types like longs.
    ZVAL_COPY_VALUE(&this->age, age);
}

// Created by 'class_definition.i'.
// Used to call 'object_init_ex(zval* dst, class_entry* ce)'.
extern zend_class_entry* ce_Person;

// Needs 'destination' to points to an existing zval.
void create_person(zval* destination, zval* name, zval* age) {
    // Allocate a new object. Not needed in constructors.
    object_init_ex(destination, ce_Person);
    // After the allocation, we can access the object data.
    class_storage* this = get_class_storage(destination);

    init_fields(this, name, age);
}

// Alternative to 'create_person', to construct one directly in PHP.
// This macro gives access to 'this' with the type 'class_storage*'.
// The '_2' corresponds to the two mandatory parameters.
// The PHP parameters of type 'zval*' can be checked with the following macros :
//  - LONG_ARG(x)
//  - DOUBLE_ARG(x)
//  - STRING_ARG(x)
//  - ARRAY_ARG(x)
//  - MIXED_ARG(x)
//  - OBJECT_ARG(class_name, x)
CLASS_METHOD_2(__construct, STRING_ARG(name), LONG_ARG(age))
{
    init_fields(this, name, age);
}

// A destructor can be implemented if native resources need to be released :
// CLASS_METHOD_0(__destruct) {...}

// 'return_value' is a 'zval*' pointing to an uninitialized zval.
CLASS_METHOD_0(getName) {
    ZVAL_COPY(return_value, name);
}

CLASS_METHOD_0(getAge) {
    ZVAL_COPY_VALUE(return_value, age);
}

// These macros will define the class content.
BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_METHOD(getName)
    ADD_METHOD(getAge)
END_CLASS_MEMBERS()

// This file will generate the class definition, based on the defined macros and the given class members.
#include "class_definition.i"
