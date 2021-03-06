// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#include "exceptions.h"

int check_arg_count(int actual, int min, int max)
{
    if (min <= actual && actual <= max) return SUCCESS;

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
