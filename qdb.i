%module qdb
%{

#include <qdb/client.h>

%}
/* we do things differently in PHP, much closer than C code */
/* this means there is no garbage collection and the user will have to be extra careful with resources ! */
/* we don't rename as well to be coherent with what happens in PHP */

%include phppointers.i

typedef struct qdb_session * qdb_handle_t;
%nodefaultdtor qdb_handle_t;

enum qdb_error_t
{
    qdb_e_ok = 0,
    qdb_e_system = 1,                         /* check errno or GetLastError() for actual error */
    qdb_e_internal = 2,
    qdb_e_no_memory = 3,
    qdb_e_invalid_protocol = 4,
    qdb_e_host_not_found = 5,
    qdb_e_invalid_option = 6,
    qdb_e_alias_too_long = 7,
    qdb_e_alias_not_found = 8,
    qdb_e_alias_already_exists = 9,
    qdb_e_timeout = 10,
    qdb_e_buffer_too_small = 11,
    qdb_e_invalid_command = 12,
    qdb_e_invalid_input = 13,
    qdb_e_connection_refused = 14,
    qdb_e_connection_reset = 15,
    qdb_e_unexpected_reply = 16,
    qdb_e_not_implemented = 17,
    qdb_e_unstable_hive = 18,
    qdb_e_protocol_error = 19,
    qdb_e_outdated_topology = 20,
    qdb_e_wrong_peer = 21,
    qdb_e_invalid_version = 22,
    qdb_e_try_again = 23,
    qdb_e_invalid_argument = 24,
    qdb_e_out_of_bounds = 25,
    qdb_e_conflict = 26,
    qdb_e_not_connected = 27,
    qdb_e_invalid_handle = 28,
    qdb_e_reserved_alias = 29
} ;

enum qdb_option_t
{
    qdb_o_operation_timeout = 0,                /* int */
    qdb_o_stream_buffer_size
};

qdb_handle_t qdb_open_tcp();

qdb_error_t qdb_close(qdb_handle_t handle);

qdb_error_t qdb_connect(
        qdb_handle_t handle,   /* [in] API handle */
        const char * host,        /* [in] host name or IP address */
        unsigned short port       /* [in] port number */
    );

qdb_error_t
qdb_get_buffer(
        qdb_handle_t handle,   /* [in] API handle */
        const char * REF,       /* [in] unique identifier of existing entry */
        char ** REF,          /* [out] receives pointer to new buffer */
        size_t * REF   /* [out] size of content, in bytes */
    );
    
qdb_error_t
qdb_put(
        qdb_handle_t handle,   /* [in] API handle */
        const char * REF,       /* [in] unique identifier for new entry */
        const char * REF,     /* [in] content for new entry */
        size_t content_length     /* [in] size of content, in bytes */
    );
    
qdb_error_t
qdb_update(
        qdb_handle_t handle,   /* [in] API handle */
        const char * REF,       /* [in] unique identifier of existing entry */
        const char * REF,     /* [in] new content for entry */
        size_t content_length     /* [in] size of content, in bytes */
    );

qdb_error_t
qdb_remove(
        qdb_handle_t handle,   /* [in] API handle */
        const char * REF        /* [in] unique identifier of existing entry */
    );

qdb_error_t
qdb_remove_all(
        qdb_handle_t handle    /* [in] API handle */
    );