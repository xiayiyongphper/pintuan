#!/bin/bash

svn up
cd ./proto
svn up
rm -rf ./service/*
protoc --php_out=. ./*/*.proto
cp -rf ./service ../
rm -rf ./service
rm -rf ./autoload.php
rm -rf ./*/*.proto.php
