#!/bin/bash
ps -eaf |grep "RPC merchant Server" | grep -v "grep"| awk '{print $2}'|xargs kill -9
ps -eaf |grep "server_merchant" | grep -v "grep"| awk '{print $2}'|xargs kill -9
ps -eaf |grep "server_http_merchant" | grep -v "grep"| awk '{print $2}'|xargs kill -9