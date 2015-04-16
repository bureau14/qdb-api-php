
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

#ifndef QDB_CLASS_DEFINITION_H
#define QDB_CLASS_DEFINITION_H

#define get_this() \
    (class_storage*)zend_object_store_get_object(getThis() TSRMLS_CC)


#define STR(X) #X
#define XSTR(X) STR(X)
#define CONCAT(X,Y) X##Y
#define XCONCAT(X,Y) CONCAT(X, Y)
#define ARGINFO(X,Y) arginfo_##X##_##Y
#define XARGINFO(X,Y) ARGINFO(X,Y)


#define ARRAY_ARG(name) \
    ZEND_ARG_ARRAY_INFO(0, name, 0)

#define LONG_ARG(name) \
    ZEND_ARG_INFO(0, name)
//  ZEND_ARG_TYPE_INFO(0, name, IS_LONG, 0) <- in a better world, we would write

#define MIXED_ARG(name) \
    ZEND_ARG_INFO(0, name)

#define OBJECT_ARG(classname,name) \
    ZEND_ARG_OBJ_INFO(0, name, classname, 0)

#define STRING_ARG(name) \
    ZEND_ARG_INFO(0, name)
//  ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0) <- in a better world, we would write


#define CLASS_METHOD_(class_name, method_name) \
    PHP_METHOD(class_name, method_name) // <- don't inline this macro, it's required

#define CLASS_METHOD_0(method_name)                          \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 0) \
    ZEND_END_ARG_INFO()                                                \
    CLASS_METHOD_(class_name, method_name)

#define CLASS_METHOD_1(method_name, arg1)                     \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 1) \
        arg1                                                           \
    ZEND_END_ARG_INFO()                                                \
    CLASS_METHOD_(class_name, method_name)

#define CLASS_METHOD_2(method_name, arg1, arg2)              \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 2) \
        arg1                                                           \
        arg2                                                           \
    ZEND_END_ARG_INFO()                                                \
    CLASS_METHOD_(class_name, method_name)

#define CLASS_METHOD_2_1(method_name, arg1, arg2, optarg1)             \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 2) \
        arg1                                                           \
        arg2                                                           \
        optarg1                                                        \
    ZEND_END_ARG_INFO()                                                \
    CLASS_METHOD_(class_name, method_name)

#define CLASS_METHOD_3_1(method_name, arg1, arg2, arg3, optarg1)       \
    ZEND_BEGIN_ARG_INFO_EX(XARGINFO(class_name, method_name), 0, 0, 3) \
        arg1                                                           \
        arg2                                                           \
        arg3                                                           \
        optarg1                                                        \
    ZEND_END_ARG_INFO()                                                \
    CLASS_METHOD_(class_name, method_name)


#define CLASS_BEGIN_MEMBERS() \
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

#define CLASS_END_MEMBERS() \
        {NULL, NULL, NULL}  \
    };

#endif /* QDB_CLASS_DEFINITION_H */
