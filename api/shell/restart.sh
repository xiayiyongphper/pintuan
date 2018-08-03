#!/bin/bash
cur_dir=$(cd "$(dirname "$0")"; pwd)
ps -eaf |grep "pintuan api Server" | grep -v "grep"| awk '{print $2}'|xargs kill -9
ps -eaf |grep "pintuan api Server" | grep -v "grep"| awk '{print $2}'|xargs kill -9
ps -eaf |grep "pintuan api Server" | grep -v "grep"| awk '{print $2}'|xargs kill -9
sleep 1
cd $cur_dir
cd ..
php http_server.php start
