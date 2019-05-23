QDB_API_URL=https://download.quasardb.net/quasardb/nightly/api/c/qdb-3.3.0master-linux-64bit-c-api.tar.gz

sudo apt-get update
sudo apt-get install -y git php5-dev libpcre3-dev

mkdir quasardb
curl $QDB_API_URL | tar xz -C quasardb

git clone https://github.com/bureau14/qdb-api-php.git
cd qdb-api-php
phpize
./configure --with-quasardb=$HOME/quasardb
make