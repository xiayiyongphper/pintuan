#!/bin/sh
make clean
phpize
./configure
make
sudo make install
