#!/bin/bash
echo '更新api>>>>'
cd /home/xia/pintuan/api
git pull
echo '更新crontab>>>'
cd /home/xia/pintuan/crontab/
git pull
echo '更新order>>>'
cd /home/xia/pintuan/order/
git pull
echo '更新pay>>>'
cd /home/xia/pintuan/pay/
git pull
echo '更新product>>>'
cd /home/xia/pintuan/product/
git pull
echo '更新store>>>'
cd /home/xia/pintuan/store/
git pull
echo '更新user>>>'
cd /home/xia/pintuan/user/
git pull
echo '更新framework>>>'
cd /home/xia/pintuan/framework/
git pull
echo '更新protobuf>>>>'
cd /home/xia/pintuan/protobuf/
git pull
echo '更新cms>>>>'
cd /home/xia/pintuan/cms/
git pull

