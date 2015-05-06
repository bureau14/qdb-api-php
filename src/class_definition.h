// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_CLASS_DEFINITION_H
#define QDB_CLASS_DEFINITION_H

#include "exceptions.h"

int check_arg_count(int actual, int min, int max TSRMLS_DC);

#define STR(X) #X
#define XSTR(X) STR(X)
#define CONCAT(X,Y) X##Y
#define XCONCAT(X,Y) CONCAT(X, Y)
#define ARGINFO(X,Y) arginfo_##X##_##Y
#define XARGINFO(X,Y) ARGINFO(X,Y)
#define UNUSED(x) (void)(x)

#define ARRAY_ARG(name) name
#define LONG_ARG(name) name
#define MIXED_ARG(name) name
#define OBJECT_ARG(classname,name) name
#define STRING_ARG(name) name

#define NAME_OF_ARRAY_ARG(name) name
#define NAME_OF_LONG_ARG(name) name
#define NAME_OF_MIXED_ARG(name) name
#define NAME_OF_OBJECT_ARG(classname,name) name
#define NAME_OF_STRING_ARG(name) name

#define DECLARE_ARRAY_ARG(name)            zval* name = NULL;
#define DECLARE_LONG_ARG(name)             zval* name = NULL;
#define DECLARE_MIXED_ARG(name)            zval* name = NULL;
#define DECLARE_OBJECT_ARG(classname,name) zval* name = NULL;
#define DECLARE_STRING_ARG(name)           zval* name = NULL;

#define INFO_FOR_ARRAY_ARG(name)            ZEND_ARG_ARRAY_INFO(0, name, 0)
#define INFO_FOR_LONG_ARG(name)             ZEND_ARG_INFO(0, name)
#define INFO_FOR_MIXED_ARG(name)            ZEND_ARG_INFO(0, name)
#define INFO_FOR_OBJECT_ARG(classname,name) ZEND_ARG_OBJ_INFO(0, name, classname, 0)
#define INFO_FOR_STRING_ARG(name)           ZEND_ARG_INFO(0, name)

#define CHECK_ARG_COUNT(min,max) \
    if (check_arg_count(ZEND_NUM_ARGS(), min, max TSRMLS_CC) == FAILURE) return;

#define PARSE_ARGS(spec, ...) \
    zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, spec, __VA_ARGS__);

#define CHECK_TYPE(name, type_enum, type_name)                                  \
    if (name != NULL && Z_TYPE_P(name) != type_enum)                            \
    {                                                                           \
        throw_invalid_argument("Argument " XSTR(name) " must be a " type_name); \
        return;                                                                 \
    }

#define CHECK_TYPE_OF_ARRAY_ARG(name)         CHECK_TYPE(name, IS_ARRAY,  "array")
#define CHECK_TYPE_OF_LONG_ARG(name)          CHECK_TYPE(name, IS_LONG,   "integer")
#define CHECK_TYPE_OF_MIXED_ARG(name)
#define CHECK_TYPE_OF_OBJECT_ARG(classname,name)
#define CHECK_TYPE_OF_STRING_ARG(name)        CHECK_TYPE(name, IS_STRING, "string")

#define BEGIN_CLASS_METHOD_(class_name, method_name)                                             \
    PHP_METHOD(class_name, method_name)                                                          \
    {                                                                                            \
        class_storage *this = (class_storage*)zend_object_store_get_object(getThis() TSRMLS_CC);

#define BEGIN_CLASS_METHOD_0(method_name)                               \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 0)  \
    ZEND_END_ARG_INFO()                                                 \
    BEGIN_CLASS_METHOD_(class_name, method_name)                        \
        CHECK_ARG_COUNT(0, 0)

#define BEGIN_CLASS_METHOD_1(method_name, arg1)                         \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 1)  \
        INFO_FOR_ ## arg1                                               \
    ZEND_END_ARG_INFO()                                                 \
    BEGIN_CLASS_METHOD_(class_name, method_name)                        \
        DECLARE_ ## arg1                                                \
        CHECK_ARG_COUNT(1, 1)                                           \
        PARSE_ARGS("z", &arg1)                                          \
        CHECK_TYPE_OF_ ## arg1

#define BEGIN_CLASS_METHOD_2(method_name, arg1, arg2)                   \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 2)  \
        INFO_FOR_ ## arg1                                               \
        INFO_FOR_ ## arg2                                               \
    ZEND_END_ARG_INFO()                                                 \
    BEGIN_CLASS_METHOD_(class_name, method_name)                        \
        DECLARE_ ## arg1                                                \
        DECLARE_ ## arg2                                                \
        CHECK_ARG_COUNT(2, 2)                                           \
        PARSE_ARGS("zz", &arg1, &arg2)                                  \
        CHECK_TYPE_OF_ ## arg1                                          \
        CHECK_TYPE_OF_ ## arg2

#define BEGIN_CLASS_METHOD_1_1(method_name, arg1, optarg1)              \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 1)  \
        INFO_FOR_ ## arg1                                               \
        INFO_FOR_ ## optarg1                                            \
    ZEND_END_ARG_INFO()                                                 \
    BEGIN_CLASS_METHOD_(class_name, method_name)                        \
        DECLARE_ ## arg1                                                \
        DECLARE_ ## optarg1                                             \
        CHECK_ARG_COUNT(1, 2)                                           \
        PARSE_ARGS("z|z", &arg1, &optarg1)                              \
        CHECK_TYPE_OF_ ## arg1                                          \
        CHECK_TYPE_OF_ ## optarg1

#define BEGIN_CLASS_METHOD_2_1(method_name, arg1, arg2, optarg1)        \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 2)  \
        INFO_FOR_ ## arg1                                               \
        INFO_FOR_ ## arg2                                               \
        INFO_FOR_ ## optarg1                                            \
    ZEND_END_ARG_INFO()                                                 \
    BEGIN_CLASS_METHOD_(class_name, method_name)                        \
        DECLARE_ ## arg1                                                \
        DECLARE_ ## arg2                                                \
        DECLARE_ ## optarg1                                             \
        CHECK_ARG_COUNT(2, 3)                                           \
        PARSE_ARGS("zz|z", &arg1, &arg2, &optarg1)                      \
        CHECK_TYPE_OF_ ## arg1                                          \
        CHECK_TYPE_OF_ ## arg2                                          \
        CHECK_TYPE_OF_ ## optarg1

#define BEGIN_CLASS_METHOD_3_1(method_name, arg1, arg2, arg3, optarg1)  \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 3)  \
        INFO_FOR_ ## arg1                                               \
        INFO_FOR_ ## arg2                                               \
        INFO_FOR_ ## arg3                                               \
        INFO_FOR_ ## optarg1                                            \
    ZEND_END_ARG_INFO()                                                 \
    BEGIN_CLASS_METHOD_(class_name, method_name)                        \
        DECLARE_ ## arg1                                                \
        DECLARE_ ## arg2                                                \
        DECLARE_ ## arg3                                                \
        DECLARE_ ## optarg1                                             \
        CHECK_ARG_COUNT(3, 4)                                           \
        PARSE_ARGS("zzz|z", &arg1, &arg2, &arg3, &optarg1)              \
        CHECK_TYPE_OF_ ## arg1                                          \
        CHECK_TYPE_OF_ ## arg2                                          \
        CHECK_TYPE_OF_ ## arg3                                          \
        CHECK_TYPE_OF_ ## optarg1

#define END_CLASS_METHOD() }


#define BEGIN_CLASS_MEMBERS() \
    static zend_function_entry methods[] = {

#define ADD_CONSTRUCTOR_(class_name, method_name) \
        PHP_ME(class_name, method_name, ARGINFO(class_name, method_name), ZEND_ACC_PUBLIC | ZEND_ACC_CTOR)

#define ADD_CONSTRUCTOR(method_name) \
        ADD_CONSTRUCTOR_(class_name, method_name)

#define ADD_DESTRUCTOR_(class_name, method_name) \
        PHP_ME(class_name, method_name,  ARGINFO(class_name, method_name), ZEND_ACC_PUBLIC | ZEND_ACC_DTOR)

#define ADD_DESTRUCTOR(method_name) \
        ADD_DESTRUCTOR_(class_name, method_name)

#define ADD_METHOD_(class_name, method_name) \
        PHP_ME(class_name, method_name, ARGINFO(class_name, method_name), ZEND_ACC_PUBLIC)

#define ADD_METHOD(method_name) \
        ADD_METHOD_(class_name, method_name)

#define END_CLASS_MEMBERS() \
        {NULL, NULL, NULL}  \
    };

#endif /* QDB_CLASS_DEFINITION_H */
