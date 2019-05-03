// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

#define class_name QdbTimestamp
#define class_storage qdb_timespec_t

CLASS_METHOD_0(__construct, LONG_ARG(seconds), LONG_ARG(nanoseconds))
{
    
}

BEGIN_CLASS_MEMBERS()
    ADD_CONSTRUCTOR(__construct)
    ADD_METHOD(timeseries)
    ADD_METHOD(column)
END_CLASS_MEMBERS()

#include "class_definition.i"
