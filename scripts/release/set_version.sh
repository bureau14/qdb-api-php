#!/bin/sh
set -eu -o pipefail
IFS=$'\n\t'

if [[ $# -ne 1 ]] ; then
    >&2 echo "Usage: $0 <new_version>"
    exit 1
fi

INPUT_VERSION=$1; shift

MAJOR_VERSION=${INPUT_VERSION%%.*}
WITHOUT_MAJOR_VERSION=${INPUT_VERSION#${MAJOR_VERSION}.}
MINOR_VERSION=${WITHOUT_MAJOR_VERSION%%.*}
WITHOUT_MINOR_VERSION=${INPUT_VERSION#${MAJOR_VERSION}.${MINOR_VERSION}.}
PATCH_VERSION=${WITHOUT_MINOR_VERSION%%.*}

XY_VERSION="${MAJOR_VERSION}.${MINOR_VERSION}"
XYZ_VERSION="${MAJOR_VERSION}.${MINOR_VERSION}.${PATCH_VERSION}"

CURRENT_DATE=`date +%Y-%m-%d`

cd $(dirname -- $0)
cd ${PWD}/../..

# <version>
#   <release>2.1.0</release>
#   <api>2.1.0</api>
# </version>
# <date>2017-08-04</date>
# <notes>
#   This version targets quasardb 2.1
sed -i \
    -e '/<version>/,/<\/version>/ { s|<release>[^<]*</release>|<release>'"${XYZ_VERSION}"'</release>| }' \
    -e '/<version>/,/<\/version>/ { s|<api>[^<]*</api>|<api>'"${XY_VERSION}"'</api>| }' \
    -e 's!<date>[^<]*</date>!<date>'"${CURRENT_DATE}"'</date>!' \
    -e '/<notes>/,/<\/notes>/ { s/\(This version targets quasardb \)[0-9.]*/\1'"${XY_VERSION}"'/ }' \
    package.xml
