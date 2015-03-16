set(PHP_API_SRCS
    batch.c
    batch.h
    batch_result.c
    batch_result.h
    class_definition.c
    class_definition.h
    cluster.c
    cluster.h
    cluster_params.c
    cluster_params.h
    common_params.c
    common_params.h
    exceptions.c
    exceptions.h
    globals.c
    globals.h
    log.c
    log.h
    php_qdb.c
    php_qdb.h
    settings.c
    settings.h
)

if (WIN32)

    # Buidling a PHP extension on Windows
    # 
    # Requirements:
    #   php source code
    #
    # To build
    #   buildconf.bat --add-modules-dir=...
    #   configure.bat --enable-qdb
    #   nmake php_qdb.dll

    if(NOT DEFINED ENV{PHP_SRC})
        message(WARNING "Environment variable PHP_SRC not set - PHP API will not be built")
        return()
    endif()

    string(REPLACE "\\" "/" PHP_SRC_DIR $ENV{PHP_SRC}) 

    if(NOT EXISTS "${PHP_SRC_DIR}/buildconf.bat")
        message(FATAL_ERROR "Environment variable PHP_SRC is not set correctly")
    endif()

    set(MAKEFILE "${PHP_SRC_DIR}/Makefile")
    set(PHP_QDB_EXT "${QDB_OUTPUT_DIRECTORY}/php_qdb.dll")
    set(PHPTS_QDB_EXT "${QDB_OUTPUT_DIRECTORY}/php_qdb_ts.dll")

    if(QDB_CPU_ARCH STREQUAL "64bit")
        set(PHP_BUILD_DIR "${PHP_SRC_DIR}/x64/Release/")
        set(PHPTS_BUILD_DIR "${PHP_SRC_DIR}/x64/Release_TS/")
    else()
        set(PHP_BUILD_DIR "${PHP_SRC_DIR}/Release/")        
        set(PHPTS_BUILD_DIR "${PHP_SRC_DIR}/Release_TS/")
    endif()

    macro(add_php_extension output builddir label flags)
        add_custom_command(
            COMMENT "Building PHP extension (${label})"
            COMMAND buildconf.bat --add-modules-dir=${CMAKE_CURRENT_SOURCE_DIR}/..
            COMMAND configure.bat --disable-all ${flags} --enable-cli --enable-phar --with-qdb --with-extra-libs=$<TARGET_FILE_DIR:qdb_api> --with-extra-includes=${CMAKE_CURRENT_SOURCE_DIR}/../include
            COMMAND nmake
            COMMAND ${CMAKE_COMMAND} -E copy "${builddir}/php_qdb.dll" "${output}"
            DEPENDS ${PHP_API_SRCS} config.w32 CMakeLists.txt
            OUTPUT ${output}
            WORKING_DIRECTORY ${PHP_SRC_DIR}
        )
    endmacro() 

    add_php_extension(${PHP_QDB_EXT} ${PHP_BUILD_DIR} "non thread-safe" "--disable-zts")
    add_php_extension(${PHPTS_QDB_EXT} ${PHPTS_BUILD_DIR} "thread-safe" "--enable-zts")

else()

    # Buidling a PHP extension on Unix
    #
    # Requirements:
    #   php-devel
    #
    # To build:
    #   phpize
    #   ./configure --enable-qdb
    #   make

    find_program(PHPIZE phpize)

    if(NOT EXISTS "${PHPIZE}")
        message(WARNING "php-devel not installed - PHP API will not be built")
        return()
    endif()

    set(PHP_QDB_EXT "${CMAKE_CURRENT_BINARY_DIR}/modules/qdb.so")
    set(MAKEFILE "${CMAKE_CURRENT_BINARY_DIR}/Makefile")

    add_custom_command(
        COMMENT "Generating PHP extension's Makefile"
        COMMAND phpize
        COMMAND cd ${CMAKE_CURRENT_BINARY_DIR}
        COMMAND ${CMAKE_CURRENT_SOURCE_DIR}/configure CFLAGS="-I${CMAKE_CURRENT_SOURCE_DIR}/../include" LDFLAGS="-L$<TARGET_FILE_DIR:qdb_api>"
        DEPENDS config.m4 CMakeLists.txt
        OUTPUT ${MAKEFILE}
        WORKING_DIRECTORY ${CMAKE_CURRENT_SOURCE_DIR}
    )

    add_custom_command(
        COMMENT "Building PHP extension"
        COMMAND make build-modules
        DEPENDS ${MAKEFILE} ${PHP_API_SRCS}
        OUTPUT ${PHP_QDB_EXT}
        WORKING_DIRECTORY ${CMAKE_CURRENT_BINARY_DIR}
    )

endif()

add_custom_target(qdb_php_api ALL
    DEPENDS ${PHP_QDB_EXT} ${PHPTS_QDB_EXT}
    DEPENDS qdb_api qdbd # client library and the daemon are required to run the tests
    SOURCES ${PHP_API_SRCS}
)

set_target_properties(qdb_php_api 
    PROPERTIES 
    FOLDER "api"
)

add_subdirectory(test)
