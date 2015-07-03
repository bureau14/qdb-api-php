#!/bin/bash

set -eu

cd $(dirname $0)/..
rm -rf doc-out
php third-party/apigen.phar generate -s doc -d doc-out --title quasardb --template-config doc-theme/config.neon --no-source-code --debug